# time-invoice

Invoice time based services

## development

This library expects `php 7.1`, `gd`, `mbstring`, `make` and `watchexec` installed localy.

Run tests: `make test`
Watch tests: `make test.watch`

If you wan't to see the results directly in the generated
PDF during development you can simply call: 

`make dev.pdf.watch` 

which generates a Invoice to `var/dev.pdf` if any file changed.

### todos until next merge

- how to use section
- how to extend data source section
- how to customize template section

### roadmap

- support multiple recipients 
- provide api to connect various recipient data sources

