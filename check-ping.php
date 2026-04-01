<?php

declare(strict_types=1);

require_once ("vendor/autoload.php");

$ping = new UnoserverClient\Ping();

$ping->call();