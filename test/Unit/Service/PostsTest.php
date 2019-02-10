<?php
declare(strict_types=1);

namespace Test\Unit\Service;


use AndriusJankevicius\Supermetrics\Api\Posts as PostsApi;
use AndriusJankevicius\Supermetrics\Service\Posts;
use AndriusJankevicius\Supermetrics\Service\TokenManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    /** @var Posts */
    private $posts;

    /** @var MockObject */
    private $postsApi;
    /** @var MockObject */
    private $tokenManager;

    protected function setUp()
    {
        $this->postsApi = $this->createMock(PostsApi::class);
        $this->tokenManager = $this->createMock(TokenManager::class);

        $this->posts = new Posts($this->postsApi, $this->tokenManager);
    }

    /**
     * @test
     */
    public function shouldReceivePostsFromApiOnce(): void
    {
        $expected = ['test1', 'test2'];
        $this->tokenManager
            ->method('getToken')
            ->willReturn('test_token');

        $this->postsApi->method('getPosts')
            ->willReturn($expected);

        $this->postsApi->expects($this->once())
            ->method('getPosts')
            ->with('test_token', 23);

        $result1 = $this->posts->getPosts(23);
        $result2 = $this->posts->getPosts(23);

        $this->assertSame($expected, $result1);
        $this->assertSame($expected, $result2);
    }
}
