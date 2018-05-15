<?php

namespace mad654\TimeInvoice;

use mad654\printable\Printer;

interface Invoice
{
    public static function fromWorkingHours(
        array $workingHours,
        InvoiceNumber $number,
        InvoiceAddress $creator,
        InvoiceBankAccount $account,
        InvoiceAddress $recipient
    ): Invoice;

    public function print(Printer $printer): Printer;
}