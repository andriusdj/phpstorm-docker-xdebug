<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics;

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
        $controller = $this->container->get(AssignmentController::class);
        $content = $controller->getResult();

        return json_encode($content) ?: json_last_error_msg();
    }
}
