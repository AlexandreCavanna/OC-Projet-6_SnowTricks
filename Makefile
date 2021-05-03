EXEC_PHP = php
SYMFONY_BIN = symfony
SYMFONY = $(EXEC_PHP) bin/console
PHPUNIT = ./vendor/bin/simple-phpunit
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer

start :
	$(SYMFONY_BIN) server:start

open:
	$(SYMFONY_BIN) open:local

stop:
	$(SYMFONY_BIN) server:stop

clear-cache:
	$(SYMFONY) c:c

fix-php:
	$(EXEC_PHP) $(PHP_CS_FIXER) fix

test-all:
	$(EXEC_PHP) $(PHPUNIT)
