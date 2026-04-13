<?php

declare(strict_types=1);

require_once("vendor/autoload.php");

$host = "127.0.0.1";
$port = "2003";

if (count($argv) <= 1) {
    printf("Usage file name for convert as argument for command line\n");
    exit(1);
}

$filename = $argv[1] ?? "";

if (empty($filename) || !file_exists($filename)) {
    printf("Error. Filename is incorrect or file %s not exists\n", $filename);
    exit(2);
}

$convert = new UnoserverClient\Convert($host, $port);
$convert->setOutputFormat("pdf");
$convert->loadFile($filename);

if ($convert->call()) {
    $resultfile = $filename . ".pdf";
    $convert->saveFile($resultfile);
    printf("Convert result: %s\n", $resultfile);
} else {
    $errors = $convert->errors();
    echo("Convert errors:\n");
    print_r($errors);
}
