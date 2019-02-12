<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics;

use AndriusJankevicius\Supermetrics\Service\PostStatsContainer;
use AndriusJankevicius\Supermetrics\Service\TokenManager;

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
        $stats = [];
        try {
            foreach ($this->postStatsContainer->getAll() as $postStats) {
                $stats[$postStats->getName()] = $postStats->get();
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
