<?php
declare(strict_types=1);

namespace Test\Unit\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\Posts;
use AndriusJankevicius\Supermetrics\Service\PostStats\LongestPostByCharacterLengthPerMonth;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Unit\Service\PostsTest;

/**
 * Class LongestPostByCharacterLengthPerMonthTest
 *
 * @package Test\Unit\Service\PostStats
 */
class LongestPostByCharacterLengthPerMonthTest extends PostStatsTestCase
{
    /** @var LongestPostByCharacterLengthPerMonth */
    private $longestPostByCharacterLengthPerMonth;

    protected function setUp()
    {
        $this->longestPostByCharacterLengthPerMonth = new LongestPostByCharacterLengthPerMonth($this->getPostsServiceMock());
    }

    /**
     * @test
     */
    public function shouldReturnLongestPostsByCharacterLengthPerMonth(): void
    {
        $testPostsPage1 = PostsTest::postsTestSample(1);
        $testPostsPage4 = PostsTest::postsTestSample(4);

        $expected = [
            'January, 2019' => $testPostsPage1['post5c63cd7eb65fd_6f41ee3f'],
            'March, 2019' => $testPostsPage4['post5c63cd7eb6612_d6d81505'],
        ];

        $longestPosts = $this->longestPostByCharacterLengthPerMonth->get(4);

        $this->assertEquals($expected, $longestPosts);
    }
}
