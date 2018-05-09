<?php

namespace mad654\TimeInvoice;

use mad654\printable\Printer;

interface Invoice
{
    public static function fromWorkingHours(array $workingHours): Invoice;

    public function print(Printer $printer): Printer;
}