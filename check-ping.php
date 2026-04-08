<?php

declare(strict_types=1);

require_once ("vendor/autoload.php");

$ping = new UnoserverClient\Ping();


if ($ping->call()) {
    $result = $ping->result();
    echo("Ping result:\n");
    print_r($result);
} else {
    $errors = $ping->errors();
    echo("Ping errors:\n");
    print_r($errors);
}