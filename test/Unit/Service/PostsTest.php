<?php
declare(strict_types = 1);

namespace Test\Unit\Service;

use AndriusJankevicius\Supermetrics\Api\Posts as PostsApi;
use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use AndriusJankevicius\Supermetrics\Model\PersistedNameValueStore;
use AndriusJankevicius\Supermetrics\Service\Posts;
use AndriusJankevicius\Supermetrics\Service\TokenManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Unit\Api\PostsTest as PostsApiTest;

/**
 * Class PostsTest
 *
 * @package Test\Unit\Service
 */
class PostsTest extends TestCase
{
    /** @var Posts */
    private $posts;

    /** @var MockObject */
    private $postsApi;
    /** @var MockObject */
    private $tokenManager;
    /** @var MockObject */
    private $persistedStorage;

    protected function setUp()
    {
        $this->postsApi = $this->createMock(PostsApi::class);
        $this->tokenManager = $this->createMock(TokenManager::class);
        $this->persistedStorage = $this->createMock(PersistedNameValueStore::class);

        $this->posts = new Posts($this->postsApi, $this->tokenManager, $this->persistedStorage);
    }

    /**
     * @test
     */
    public function shouldReceivePostsFromPersistedStorage(): void
    {
        $expected = self::postsTestSample(3);

        $this->persistedStorage->method('find')
            ->willReturn(PostsApiTest::postsFromApiTestSample(3));

        $this->persistedStorage->expects($this->once())
            ->method('find')
            ->with('posts-3');

        $this->postsApi->expects($this->never())
            ->method('getPosts');

        $result = $this->posts->getPosts(3);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldReceivePostsFromApiOnce(): void
    {
        $expected = self::postsTestSample(3);
        $apiTestSample = PostsApiTest::postsFromApiTestSample(3);

        $this->persistedStorage->method('find')
            ->willThrowException(new \InvalidArgumentException());

        $this->tokenManager
            ->method('getToken')
            ->willReturn('test_token');

        $this->postsApi->method('getPosts')
            ->willReturn($apiTestSample);

        $this->postsApi->expects($this->once())
            ->method('getPosts')
            ->with('test_token', 3);

        $this->persistedStorage->expects($this->once())
            ->method('persist')
            ->with('posts-3', $apiTestSample);

        $this->persistedStorage->expects($this->once())
            ->method('flush');

        $result1 = $this->posts->getPosts(3);
        $result2 = $this->posts->getPosts(3);

        $this->assertEquals($expected, $result1);
        $this->assertEquals($expected, $result2);
    }

    /**
     * @test
     */
    public function shouldReceiveNoPostsWhenUnavailable(): void
    {
        $expected = [];

        $this->persistedStorage->method('find')
            ->willThrowException(new \InvalidArgumentException());

        $this->tokenManager
            ->method('getToken')
            ->willReturn('test_token');

        $this->postsApi->method('getPosts')
            ->willThrowException(new InvalidApiResponseException('Wrong posts page returned'));

        $result = $this->posts->getPosts(3);

        $this->assertSame($expected, $result);
    }

    /**
     * @param int $page
     *
     * @return array
     * @throws \Exception
     */
    public static function postsTestSample(int $page): array
    {
        try {
            $posts = PostsApiTest::postsFromApiTestSample($page);
        } catch (InvalidApiResponseException $e) {

            return [];
        }

        if ($posts) {

            $result = [];
            foreach ($posts as $post) {
                $result[$post['id']] = Post::createFromApi($post);
            }

            return $result;
        }

        return [];
    }
}
