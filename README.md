# time-invoice

Invoice time based services

## getting started

This library expects `php 7.1`, `gd`, `mbstring`, `make` and `watchexec` installed locally.

### Installation

```
git clone git@github.com:mad654/time-invoice.git
cd time-invoice
make build
```

### Example usage

Takes `mad654/AtWorkTimeInvoice/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv` as
input and generates invoice to `var/dev.pdf`:

```
bin/time-invoice.php
```

Custom input and output:

```
bin/time-invoice <atWork_input.csv> <output.pdf>
```

### Settings

All settings related to the generated invoice are located in `etc/app.ini`.
If this file not exists you can copy it from `etc/app.ini.dist` but this should
be already done during `make build`.

### Customize template

The invoice template is located in `etc/invoice_template.php`. You can change it according
your needs. The default template is located `etc/invoice_template.php.dist`.

If you want to see the results directly in the generated
PDF during development you can simply call: 

`make dev.pdf.watch` 

which generates an invoice to `var/dev.pdf` if any file changed.

TODO: how to dump available variables


## development

- Run tests: `make test`
- Watch tests: `make test.watch`

TODO: how to extend data source section






