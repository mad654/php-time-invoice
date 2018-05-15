<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printable;
use mad654\printable\Printer;

class SimpleDateInvoiceNumber implements Printable, InvoiceNumber
{
    public function print(Printer $printer): Printer {
        return $printer->with('number', 'RG-' . date('Y-m-01'));
    }
}