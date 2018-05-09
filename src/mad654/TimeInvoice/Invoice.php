<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printable;
use mad654\printable\Printer;

final class Invoice implements Printable
{
    private $invoiceRecipient = [];
    private $paymentInformation = [];
    private $rows = [];
    private $totalEuroCent = 0;
    private $taxEuroCent = 0;
    private $totalInclTaxEuroCent = 0;

    public static function fromWorkingHours(array $workingHours): Invoice {
        $invoice = new self();

        $counter = 1;
        foreach ($workingHours as $workingHour) {
            $invoice->rows[] = new InvoicePosition($counter, $workingHour);
            $counter++;
        }

        $invoice->totalEuroCent = self::calculateTotal($workingHours)->toEuroCent();

        return $invoice;
    }

    /**
     * @param array $workingHours
     * @return SimpleWorkingHour
     */
    protected static function calculateTotal(array $workingHours): SimpleWorkingHour {
        $total = new SimpleWorkingHour(0, 0);

        foreach ($workingHours as $hour) {
            $total->add($hour);
        }

        return $total;
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