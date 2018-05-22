#!/usr/bin/env php

<?php

use mad654\app\ConfiguarableInvoiceFactory;
use mad654\AtWorkTimeInvoice\AtWorkWorkingHour;
use mad654\printable\TestPrinter;

require __DIR__ . '/../vendor/autoload.php';

$input = __DIR__ . '/../tests/mad654/AtWorkTimeInvoice/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv';
if (isset($argv[1]) && !empty($argv[1])) {
    $input = $argv[1];
}

$inputfile = realpath($input);
if ($inputfile === false || !is_file($inputfile)) {
    die("Input file `" . $input . "` not found\n");
}

$hours = AtWorkWorkingHour::fromFile($inputfile);
$factory = ConfiguarableInvoiceFactory::instance();
$invoice = $factory->fromWorkingHours($hours);
$printer = new TestPrinter();
$invoice->print($printer);

var_export($printer->printedValues);
