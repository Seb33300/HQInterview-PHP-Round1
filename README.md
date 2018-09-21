# HQInterview / PHP-Round1

Description here: https://github.com/HQInterview/PHP-Round1


## Requirements

* [Php](http://php.net/) 7.1.3 or higher
* [Composer](https://getcomposer.org)


## Installation

1. Clone project `git clone https://github.com/Seb33300/HQInterview-PHP-Round1.git`
2. Create a .env file `cp .env.dist .env`, edit env variables with your API credentials
3. Install PHP dependencies `composer install`
4. Create database schema `php bin/console doctrine:schema:update --force`
5. Launch web server `php bin/console server:start`

_Application can be executed with SQLite just by keeping the default database configuration: `sqlite:///%kernel.project_dir%/var/data.db`_.


## Running tests

Tests can be run with the following command:
```
php ./bin/phpunit
```


## Notes

Credit card numbers for testing purpose can be found here:
- Paypal: https://www.darkcoding.net/credit-card-numbers/
- Braintree: https://developers.braintreepayments.com/guides/credit-cards/testing-go-live/php

You can easily add new payment gateway by creating a new class implementing `PaymentGatewayInterface` in the `PaymentGateway` folder.
Payment gateways activation and priority can be managed in the `services.yaml` file.
