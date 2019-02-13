<?php
declare(strict_types = 1);

namespace Test\Unit\Service;

use AndriusJankevicius\Supermetrics\Api\Posts as PostsApi;
use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use AndriusJankevicius\Supermetrics\Service\PostsManager;
use AndriusJankevicius\Supermetrics\Service\PostsStorage;
use AndriusJankevicius\Supermetrics\Service\TokenManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Unit\Api\PostsTest as PostsApiTest;

/**
 * Class PostsTest
 *
 * @package Test\Unit\Service
 */
class PostsManagerTest extends TestCase
{
    /** @var PostsManager */
    private $posts;

    /** @var MockObject */
    private $postsApi;
    /** @var MockObject */
    private $tokenManager;
    /** @var MockObject */
    private $postsStorage;

    protected function setUp(): void
    {
        $this->postsApi = $this->createMock(PostsApi::class);
        $this->tokenManager = $this->createMock(TokenManager::class);
        $this->postsStorage = $this->createMock(PostsStorage::class);

        $this->posts = new PostsManager($this->postsApi, $this->tokenManager, $this->postsStorage);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldReceivePostsFromPersistedStorage(): void
    {
        $expected = self::postsTestSample(3);

        $this->postsStorage->method('findPersistedPosts')
            ->willReturn(PostsApiTest::postsFromApiTestSample(3));

        $this->postsStorage->expects($this->once())
            ->method('findPersistedPosts')
            ->with(3);

        $this->postsApi->expects($this->never())
            ->method('getPosts');

        $result = $this->posts->getPosts(3);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldReceivePostsFromApiOnce(): void
    {
        $expected = self::postsTestSample(3);
        $apiTestSample = PostsApiTest::postsFromApiTestSample(3);

        $this->postsStorage->method('findPersistedPosts')
            ->willReturn([]);

        $this->tokenManager
            ->method('getToken')
            ->willReturn('test_token');

        $this->postsApi->method('getPosts')
            ->willReturn($apiTestSample);

        $this->postsApi->expects($this->once())
            ->method('getPosts')
            ->with('test_token', 3);

        $this->postsStorage->expects($this->once())
            ->method('save')
            ->with(3, $apiTestSample);

        $result1 = $this->posts->getPosts(3);
        $result2 = $this->posts->getPosts(3);

        $this->assertEquals($expected, $result1);
        $this->assertEquals($expected, $result2);
    }

    /**
     * @test
     * @throws InvalidApiResponseException
     */
    public function shouldReceiveNoPostsWhenUnavailable(): void
    {
        $expected = [];

        $this->postsStorage->method('findPersistedPosts')
            ->willReturn([]);

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
