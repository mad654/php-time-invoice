test:
	vendor/bin/phpunit -c tests/phpunit.xml

test.watch:
	watchexec -e php make test
