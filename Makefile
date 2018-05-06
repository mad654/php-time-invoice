test: build
	vendor/bin/phpunit -c tests/phpunit.xml

test.watch:
	watchexec -e php make test

build: vendor

vendor: composer.lock
	composer install

composer.lock: composer.json
	composer update
	touch composer.lock