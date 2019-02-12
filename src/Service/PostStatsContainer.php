<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;

use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Service\PostStats\PostStatsInterface;
use DI\Container;

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
     * PostStats constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $containerContents = $container->getKnownEntryNames();
        foreach ($containerContents as $entry) {
            if ($entry instanceof PostStatsInterface) {
                $this->postStats[] = $entry;
            }
        }
    }

    /**
     * @return PostStatsInterface[]
     */
    public function getAll(): array
    {
        return $this->postStats;
    }
}
