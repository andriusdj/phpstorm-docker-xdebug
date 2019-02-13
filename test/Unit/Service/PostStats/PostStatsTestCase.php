<?php
declare(strict_types=1);

namespace Test\Unit\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\Posts;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Unit\Service\PostsTest;

/**
 * Class PostStatsTestCase
 *
 * @package Test\Unit\Service\PostStats
 */
class PostStatsTestCase extends TestCase
{
    /**
     * @return MockObject|Posts
     * @see \Test\Unit\Api\PostsTest::postsFromApiTestSample() for data samples
     */
    protected function getPostsServiceMock(): MockObject
    {
        $posts = $this->createMock(Posts::class);
        $posts->method('getPosts')
            ->willReturnCallback(function ($argument) { return PostsTest::postsTestSample($argument); });

        return $posts;
    }
}
