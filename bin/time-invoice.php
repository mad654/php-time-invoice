#!/usr/bin/env php

<?php

use mad654\app\ConfiguarableInvoiceFactory;
use mad654\AtWorkTimeInvoice\AtWorkWorkingHour;
use mad654\TimeInvoice\InvoicePrinter;

require __DIR__ . '/../vendor/autoload.php';

umask(0);

$templateFile = __DIR__ . "/../etc/invoice_template.php";
$inputFile = __DIR__ . '/../tests/mad654/AtWorkTimeInvoice/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv';
$outputDirName = "var";
$outputFileName = "dev.pdf";

/**
 * @param string $outputDirName
 * @param $outputFileName
 * @param string $inputFileName
 * @param string $templateFile
 * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
 * @throws \mad654\AtWorkTimeInvoice\FileNotFoundException
 */
function main($outputDirName, $outputFileName, $inputFileName, $templateFile): void {
    $outputDir = realpath($outputDirName);

    if ($outputDir === false) {
        mkdir($outputDirName, 0777, true);
        $outputDir = realpath($outputDirName);
    }

    $inputfile = realpath($inputFileName);
    if ($inputfile === false) {
        die($inputFileName . " not found");
    }

    $workingHours = AtWorkWorkingHour::fromFile($inputfile);
    $factory = ConfiguarableInvoiceFactory::instance();
    $invoice = $factory->fromWorkingHours($workingHours);

    $printer = new InvoicePrinter($templateFile);
    $invoice->print($printer);
    $printer->toPdf("$outputDir/$outputFileName");
}

if (isset($argv[1])) {
    $inputFile = $argv[1];
}

if (isset($argv[2])) {
    $outputDirName = dirname($argv[2]);
    $outputFileName = basename($argv[2]);

    if (file_exists($argv[2])) {
        die("Rechnung existiert bereits: $argv[2]");
    }
}

main($outputDirName, $outputFileName, $inputFile, $templateFile);

