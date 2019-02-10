<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics;

use AndriusJankevicius\Supermetrics\Service\TokenManager;

/**
 * Class AssignmentResult
 * @package AndriusJankevicius\Supermetrics
 */
class AssignmentController
{
    /**
     * @var TokenManager
     */
    private $tokenManager;

    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        try {
            $token = $this->tokenManager->getToken();

            return [
                'token' => $token
            ];
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
