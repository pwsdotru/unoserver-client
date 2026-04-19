<?php

declare(strict_types=1);

require_once("vendor/autoload.php");

$host = "127.0.0.1";
$port = "2003";

if (count($argv) != 4) {
    printf("Usage:\n %s oldfile newfile format\n", $argv[0]);
    exit(1);
}

$oldname = $argv[1] ?? "";
$newname = $argv[2] ?? "";
$format =  $argv[3] ?? "";

if (empty($oldname) || !file_exists($oldname)) {
    printf("Error. Filename is incorrect or file %s not exists\n", $oldname);
    exit(2);
}

if (empty($newname) || !file_exists($newname)) {
    printf("Error. Filename is incorrect or file %s not exists\n", $newname);
    exit(2);
}

if (empty($format)) {
    printf("Error. Empty output format\n");
    exit(2);
}

$compare = new UnoserverClient\Compare($host, $port);
$compare->setOutputFormat($format);

if (false === $compare->loadOldFile($oldname)) {
    printf("Error. Can't load old file: %s.\n%s\n", $oldname, implode("\n", $compare->errors()));
    exit(3);
}

if (false === $compare->loadNewFile($newname)) {
    printf("Error. Can't load new file %s.\n%s\n", $newname, implode("\n", $compare->errors()));
    exit(3);
}

if ($compare->call()) {
    $resultfile = "compareresult." . $format;
    if ($compare->saveFile($resultfile)) {
        printf("Compare result: %s\n", $resultfile);
    } else {
        $errors = $compare->errors();
        echo("Save result errors:\n");
        print_r($errors);
    }
} else {
    $errors = $compare->errors();
    echo("Compare errors:\n");
    print_r($errors);
}
