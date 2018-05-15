test: build
	vendor/bin/phpunit -c tests/phpunit.xml

test.watch:
	watchexec -e php,lock,json,csv,ini make test

dev.pdf: test
	bin/devTool_atWorkPdfPrinter.php

dev.pdf.watch:
	watchexec -e php,lock,json,csv,ini,png,gif make dev.pdf

build: vendor etc/app.ini

etc/app.ini:
	@cp -v etc/app.ini.dist etc/app.ini

vendor: composer.lock
	composer install

composer.lock: composer.json
	composer update
	touch composer.lock