<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service\PostStats;

use AndriusJankevicius\Supermetrics\Service\Posts;

/**
 * Class TotalPostsSplitByWeek
 *
 * @package AndriusJankevicius\Supermetrics\Service\PostStats
 */
class TotalPostsSplitByWeek
{
    /**
     * @var Posts
     */
    private $posts;

    /**
     * TotalPostsSplitByWeek constructor.
     *
     * @param Posts $posts
     */
    public function __construct(Posts $posts)
    {
        $this->posts = $posts;
    }

    public function get(): array
    {

    }
}
