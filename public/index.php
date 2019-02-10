<?php

declare(strict_types=1);

use AndriusJankevicius\Supermetrics\AssignmentKernel;

require_once '../vendor/autoload.php';

try {
    $kernel = new AssignmentKernel();
    $content = $kernel->getContent();
} catch (\Throwable $throwable) {
    $content = $throwable->getMessage();
}

$size = strlen($content);

header("Content-Type: application/json");
header("Content-Length: {$size}");

echo $content;
