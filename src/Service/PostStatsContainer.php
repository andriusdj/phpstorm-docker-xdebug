<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;

use AndriusJankevicius\Supermetrics\Service\PostStats\PostStatsInterface;

/**
 * Class PostStats
 *
 * @package AndriusJankevicius\Supermetrics\Service
 */
class PostStatsContainer
{
    /**
     * @var PostStatsInterface[]
     */
    private $postStats = [];

    /**
     * @param PostStatsInterface $postStats
     */
    public function add(PostStatsInterface $postStats): void
    {
        $this->postStats[$postStats->getName()] = $postStats;
    }

    /**
     * @return PostStatsInterface[]
     */
    public function getAll(): array
    {
        if (!$this->postStats) {

            throw new \RuntimeException('No PostStats classes have been loaded!');
        }

        return $this->postStats;
    }
}
