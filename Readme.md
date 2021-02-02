# simple payeer sdk

## Install
    composer require purt09/payeer
## Unit testing
### Install in your local
    $ composer install
### Run Tests
Fill in the information in PayeerTest
Fill in the information in PayeerClientTest

    $ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/PayeerTest.php
    $ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/PayeerClientTest.php

or

    $ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/PayeerTest.php
    $ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/PayeerClientTest.php