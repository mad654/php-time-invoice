<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printer;

final class Invoice
{
    private $invoiceRecipient = [];
    private $paymentInformation = [];
    private $rows = [];
    private $totalEuroCent = 0;
    private $taxEuroCent = 0;
    private $totalInclTaxEuroCent = 0;

    public static function fromWorkingHours(array $workingHours): Invoice {
        $invoice = new self();
        $total = new SimpleWorkingHour(0, 0);

        foreach ($workingHours as $hour) {
            $total->add($hour);
        }

        $invoice->totalEuroCent = $total->toEuroCent();
        return $invoice;
    }

    public function print(Printer $printer): Printer {
        return $printer
            ->with('invoiceRecipient', $this->invoiceRecipient)
            ->with('paymentInformation', $this->paymentInformation)
            ->with('rows', $this->rows)
            ->with('totalEuroCent', $this->totalEuroCent)
            ->with('taxEuroCent', $this->taxEuroCent)
            ->with('totalInclTaxEuroCent', $this->totalInclTaxEuroCent)
            ;
    }
}