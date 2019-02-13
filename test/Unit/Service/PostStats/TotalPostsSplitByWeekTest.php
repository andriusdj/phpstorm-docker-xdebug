<?php
declare(strict_types=1);

namespace Test\Unit\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\PostsManager;
use AndriusJankevicius\Supermetrics\Service\PostStats\TotalPostsSplitByWeek;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Unit\Service\PostsManagerTest;

/**
 * Class TotalPostsSplitByWeekTest
 *
 * @package Test\Unit\Service\PostStats
 */
class TotalPostsSplitByWeekTest extends PostStatsTestCase
{
    /** @var TotalPostsSplitByWeek */
    private $totalPostsSplitByWeek;

    protected function setUp()
    {
        $this->totalPostsSplitByWeek = new TotalPostsSplitByWeek($this->getPostsServiceMock());
    }

    /**
     * @test
     */
    public function shouldReturnTotalPostsSplitByWeek(): void
    {
        $expected = [
            'Week 01, 2019' => 1,
            'Week 03, 2019' => 1,
            'Week 04, 2019' => 2,
            'Week 11, 2019' => 2,
            'Week 13, 2019' => 2
        ];

        $result = $this->totalPostsSplitByWeek->get(5);

        $this->assertEquals($expected, $result);
    }
}
