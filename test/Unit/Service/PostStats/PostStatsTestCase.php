<?php
declare(strict_types=1);

namespace Test\Unit\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\PostsManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Unit\Service\PostsManagerTest;

/**
 * Class PostStatsTestCase
 *
 * @package Test\Unit\Service\PostStats
 */
class PostStatsTestCase extends TestCase
{
    /**
     * @return MockObject|PostsManager
     * @see \Test\Unit\Api\PostsTest::postsFromApiTestSample() for data samples
     */
    protected function getPostsServiceMock(): MockObject
    {
        $posts = $this->createMock(PostsManager::class);
        $posts->method('getPosts')
            ->willReturnCallback(function ($argument) { return PostsManagerTest::postsTestSample($argument); });

        return $posts;
    }
}
