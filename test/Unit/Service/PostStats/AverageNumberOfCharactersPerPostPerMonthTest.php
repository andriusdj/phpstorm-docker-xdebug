<?php
/**
 * Created by PhpStorm.
 * User: andrius
 * Date: 19.2.13
 * Time: 16.47
 */

namespace Test\Unit\Service\PostStats;


use AndriusJankevicius\Supermetrics\Service\PostStats\AverageNumberOfCharactersPerPostPerMonth;

class AverageNumberOfCharactersPerPostPerMonthTest extends PostStatsTestCase
{
    /** @var AverageNumberOfCharactersPerPostPerMonth */
    private $averageCharactersPerPostPerMonth;

    protected function setUp()
    {
        $this->averageCharactersPerPostPerMonth = new AverageNumberOfCharactersPerPostPerMonth($this->getPostsServiceMock());
    }

    /**
     * @test
     */
    public function shouldCalculateAverageNumberOfCharactersPerPostPerMonth(): void
    {
        $expected = [
            'January, 2019' => 4,
            'March, 2019' => 7,
        ];

        $result = $this->averageCharactersPerPostPerMonth->get(10);

        $this->assertEquals($expected, $result);
    }
}
