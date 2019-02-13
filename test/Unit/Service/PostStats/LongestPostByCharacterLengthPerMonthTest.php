<?php
declare(strict_types=1);

namespace Test\Unit\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\PostStats\LongestPostByCharacterLengthPerMonth;
use Test\Unit\Service\PostsManagerTest;

/**
 * Class LongestPostByCharacterLengthPerMonthTest
 *
 * @package Test\Unit\Service\PostStats
 */
class LongestPostByCharacterLengthPerMonthTest extends PostStatsTestCase
{
    /** @var LongestPostByCharacterLengthPerMonth */
    private $longestPostByCharacterLengthPerMonth;

    protected function setUp(): void
    {
        $this->longestPostByCharacterLengthPerMonth = new LongestPostByCharacterLengthPerMonth($this->getPostsServiceMock());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldReturnLongestPostsByCharacterLengthPerMonth(): void
    {
        $testPostsPage1 = PostsManagerTest::postsTestSample(1);
        $testPostsPage4 = PostsManagerTest::postsTestSample(4);

        $expected = [
            'January, 2019' => $testPostsPage1['post5c63cd7eb65fd_6f41ee3f'],
            'March, 2019' => $testPostsPage4['post5c63cd7eb6612_d6d81505'],
        ];

        $longestPosts = $this->longestPostByCharacterLengthPerMonth->get(4);

        $this->assertEquals($expected, $longestPosts);
    }
}
