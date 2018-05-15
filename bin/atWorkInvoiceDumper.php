#!/usr/bin/env php

<?php

use mad654\AtWorkTimeInvoice\AtWorkWorkingHour;
use mad654\printable\TestPrinter;
use mad654\app\ConfiguarableInvoiceFactory;

require __DIR__ . '/../vendor/autoload.php';

$inputfile = realpath($argv[1]);
if ($inputfile === false) {
    die($argv[1] . " not found");
}

$hours = AtWorkWorkingHour::fromFile($inputfile);
$factory = ConfiguarableInvoiceFactory::instance();
$invoice = $factory->fromWorkingHours($hours);
$printer = new TestPrinter();
$invoice->print($printer);

var_dump($printer->printedValues);
