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


### PHP

For work need module [**xmlrpc**](https://www.php.net/manual/en/book.xmlrpc.php)

*XML-RPC support in PHP is not enabled by default. You will need to use the --with-xmlrpc[=DIR] configuration option when compiling PHP to enable XML-RPC support.*

Or install module

php 7.4

````
dnf install php74-php-xmlrpc
````

php 8.3

````
dnf install php83-php-pecl-xmlrpc
````