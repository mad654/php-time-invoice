# php-time-invoice

Generate german tax free pdf invoice from [atWork](https://itunes.apple.com/de/app/atwork-zeiterfassung-stechuhr/id857189697?mt=8) csv exports with php.

## getting started

This library expects `php 7.1`, `gd`, `mbstring`, `make` and `watchexec` installed locally.

### Installation

```
git clone git@github.com:mad654/time-invoice.git
cd time-invoice
make build
```

### Example usage

Takes [`tests/mad654/AtWorkTimeInvoice/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv`](tests/mad654/AtWorkTimeInvoice/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv) as
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
If this file not exists you can copy it from [`etc/app.ini.dist`](etc/app.ini.dist) but this should
be already done during `make build`.

### Customize template

The invoice template is located in `etc/invoice_template.php`. You can change it according
your needs. The default template is located [`etc/invoice_template.php.dist`](etc/invoice_template.php.dist).

The template has access to `$data`. You can dump it to command line with 
`bin/atWorkInvoiceDumper.php [<atWork_input.csv>]`.

If you want to see the results directly in the generated
PDF during development you can simply call: 

`make dev.pdf.watch` 

which generates an invoice to `var/dev.pdf` if any file changed.


## development

- Run tests: `make test`
- Watch tests: `make test.watch`

### How to add a new data source for time records

1. add a new implementation of [`mad654\TimeInvoice\WorkingHour`](src/mad654/TimeInvoice/WorkingHour.php)
2. use this class in [`bin/atWorkInvoiceDumper.php`](bin/atWorkInvoiceDumper.php) and 
   [`bin/time-invoice.php`](bin/time-invoice.php) instead of 
   [`mad654\AtWorkTimeInvoice`](src/mad654/AtWorkTimeInvoice/AtWorkWorkingHour.php)






