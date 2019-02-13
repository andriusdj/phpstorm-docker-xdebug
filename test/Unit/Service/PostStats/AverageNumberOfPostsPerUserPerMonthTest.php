<?php
declare(strict_types=1);

namespace Test\Unit\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\PostStats\AverageNumberOfPostsPerUserPerMonth;

/**
 * Class AverageNumberOfPostsPerUserPerMonthTest
 *
 * @package Test\Unit\Service\PostStats
 */
class AverageNumberOfPostsPerUserPerMonthTest extends PostStatsTestCase
{
    /** @var AverageNumberOfPostsPerUserPerMonth */
    private $averageNumberOfPostsPerUserPerMonth;

    protected function setUp()
    {
        $this->averageNumberOfPostsPerUserPerMonth = new AverageNumberOfPostsPerUserPerMonth($this->getPostsServiceMock());
    }

    /**
     * @test
     */
    public function shouldCalculateAverageNumberOfPostsPerUserPerMonth(): void
    {
        $expected = [
            'user_17' => 1,
            'user_6' => 1.5,
            'user_12' => 1,
        ];

        $result = $this->averageNumberOfPostsPerUserPerMonth->get(3);

        $this->assertEquals($expected, $result);
    }
}
