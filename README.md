## PHP Client for Unoserver

### Unoserver

[Unoserver](https://github.com/unoconv/unoserver) using LibreOffice as a server for converting documents.

[Docker](https://github.com/unoconv/unoserver-docker) for usage Unoserver

#### Run for tests

Docker files for tests in folder [./tests/docker/](./tests/docker/)

File [./docker-compose.yml](./docker-compose.yml) for operations with Docker container

Build container (command in project root) 

```
docker-compose build
```

Start container for tests (command in project root)

```
docker-compose up
```

Unoserve listen on localhost (127.0.0.1) and port 2003

For check that server start run script [check-xmlrpc.php](./check-xmlrpc.php). 
It should output informatino about Unosrver and LibreOffice.

````shell script
php -f check-xmlrpc.php
````

### PHP

For correct work client need module [**xmlrpc**](https://www.php.net/manual/en/book.xmlrpc.php)

*XML-RPC support in PHP is not enabled by default. You will need to use the --with-xmlrpc[=DIR] configuration option when compiling PHP to enable XML-RPC support.*

Or install module

php 7.4

````shell script
dnf install php74-php-xmlrpc
````

php 8.3

````shell script
dnf install php83-php-pecl-xmlrpc
````
###Install

````

````

### Usage

#### Ping (info)

````php
$ping = new UnoserverClient\Ping($host, $port);

if ($ping->call()) {
    $result = $ping->result();
    echo("Ping result:\n");
    print_r($result);
} else {
    $errors = $ping->errors();
    echo("Ping errors:\n");
    print_r($errors);
}
````

#### Convert

````php
$convert = new UnoserverClient\Convert($host, $port);
$convert->setOutputFormat($format);
if (false === $convert->loadFile($filename)) {
    printf("Error. Can't load file.\n%s\n", implode("\n", $convert->errors()));
    exit(3);
}

if ($convert->call()) {
    $resultfile = $filename . "." . $format;
    if ($convert->saveFile($resultfile)) {
        printf("Convert result: %s\n", $resultfile);
    } else {
        $errors = $convert->errors();
        echo("Save result errors:\n");
        print_r($errors);
    }
} else {
    $errors = $convert->errors();
    echo("Convert errors:\n");
    print_r($errors);
}

````

#### Compare

````php
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
````