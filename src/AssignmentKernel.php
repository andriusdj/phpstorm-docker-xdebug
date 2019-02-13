<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics;

use AndriusJankevicius\Supermetrics\Service\PostStats\AverageNumberOfCharactersPerPostPerMonth;
use AndriusJankevicius\Supermetrics\Service\PostStats\AverageNumberOfPostsPerUserPerMonth;
use AndriusJankevicius\Supermetrics\Service\PostStats\LongestPostByCharacterLengthPerMonth;
use AndriusJankevicius\Supermetrics\Service\PostStats\TotalPostsSplitByWeek;
use AndriusJankevicius\Supermetrics\Service\PostStatsContainer;
use DI\Container;
use DI\ContainerBuilder;

/**
 * Class HttpKernel
 * @package AndriusJankevicius\Supermetrics
 * @NOTE not part of assignment
 */
class AssignmentKernel
{
    /** @var Container */
    private $container;

    /**
     * HttpKernel constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->enableCompilation('/tmp');
        $this->container = $containerBuilder->build();
    }

    /**
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getContent(): string
    {
        $this->initializeStatsContainer();
        $controller = $this->container->get(AssignmentController::class);
        $content = $controller->getResult();

        return json_encode($content) ?: json_last_error_msg();
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function initializeStatsContainer(): void
    {
        $container = $this->container;
        $postStatsContainer = $container->get(PostStatsContainer::class);

        $postStatsContainer->add($container->get(AverageNumberOfCharactersPerPostPerMonth::class));
        $postStatsContainer->add($container->get(LongestPostByCharacterLengthPerMonth::class));
        $postStatsContainer->add($container->get(TotalPostsSplitByWeek::class));
        $postStatsContainer->add($container->get(AverageNumberOfPostsPerUserPerMonth::class));
    }
}
