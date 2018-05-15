<?php

namespace mad654\TimeInvoice;

interface InvoiceFactory
{
    public static function instance(): InvoiceFactory;

    public function init(string $configurationDir): void;

    /**
     * @param WorkingHour[] $workingHours
     * @return Invoice
     */
    public function fromWorkingHours(array $workingHours): Invoice;
}