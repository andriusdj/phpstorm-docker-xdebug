<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service\PostStats;

/**
 * Interface PostStatsInterface
 *
 * @package AndriusJankevicius\Supermetrics\Service\PostStats
 */
interface PostStatsInterface
{
    /**
     * Return the stats information
     *
     * @param int $pages
     *
     * @return array
     */
    public function get(int $pages): array;

    /**
     * The name of the stats entry
     *
     * @return string
     */
    public function getName(): string;
}
