<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics;

use AndriusJankevicius\Supermetrics\Service\PostStatsContainer;

/**
 * Class AssignmentResult
 * @package AndriusJankevicius\Supermetrics
 */
class AssignmentController
{
    /**
     * @var PostStatsContainer
     */
    private $postStatsContainer;

    /**
     * AssignmentController constructor.
     *
     * @param PostStatsContainer $postStatsContainer
     */
    public function __construct(PostStatsContainer $postStatsContainer)
    {
        $this->postStatsContainer = $postStatsContainer;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        $maxPages = isset($_GET['max-pages']) ? (int) $_GET['max-pages'] : 10;

        $stats = [];
        try {
            foreach ($this->postStatsContainer->getAll() as $postStats) {
                $stats[$postStats->getName()] = $postStats->get($maxPages);
            }

            return $stats;
        } catch (\Throwable $e) {

            return [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        }

    }
}
