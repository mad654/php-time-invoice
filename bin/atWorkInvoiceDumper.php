#!/usr/bin/env php

<?php

use mad654\AtWorkTimeInvoice\AtWorkWorkingHour;
use mad654\printable\TestPrinter;
use mad654\TimeInvoice\Invoice;

require __DIR__ . '/../vendor/autoload.php';

$inputfile = realpath($argv[1]);
if ($inputfile === false) {
    die($argv[1] . " not found");
}

$invoice = Invoice::fromWorkingHours(AtWorkWorkingHour::fromFile($inputfile));
$printer = new TestPrinter();
$invoice->print($printer);

var_dump($printer->printedValues);
