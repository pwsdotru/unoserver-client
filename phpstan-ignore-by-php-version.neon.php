<?php

declare(strict_types=1);

$includes = [];
if ('800' === substr((string)PHP_VERSION_ID, 0, 3)) {
    $includes[] = __DIR__ . '/phpstan-8.0.neon';
}

$config['includes'] = $includes;

return $config;
