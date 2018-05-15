<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printable;
use mad654\printable\Printer;

final class TaxFreeInvoice implements Printable, Invoice
{

    /**
     * @var InvoiceNumber
     */
    private $number;

    /**
     * @var InvoiceAddress
     */
    private $creator;

    /**
     * @var InvoiceAddress
     */
    private $recipient;

    /**
     * @var InvoiceAddress
     */
    private $bankAccount;

    /**
     * @var WorkingHour[]
     */
    private $rows = [];

    /**
     * @var int
     */
    private $totalEuroCent = 0;

    /**
     * @var int
     */
    private $taxEuroCent = 0;

    public static function fromWorkingHours(
        array $workingHours,
        InvoiceNumber $number,
        InvoiceAddress $creator,
        InvoiceBankAccount $account,
        InvoiceAddress $recipient
    ): Invoice {
        $invoice = new self();
        $invoice->number = $number;
        $invoice->bankAccount = $account;
        $invoice->creator = $creator;
        $invoice->recipient = $recipient;

        $counter = 1;
        foreach ($workingHours as $workingHour) {
            // @todo mad654 refactor this so we can reuse it for other invoice types
            $invoice->rows[] = new WorkingHourInvoicePosition($counter, $workingHour);
            $counter++;
        }

        $invoice->totalEuroCent = self::calculateTotal($workingHours)->toEuroCent();

        return $invoice;
    }

    /**
     * @param array $workingHours
     * @return SimpleWorkingHour
     *
     * @todo mad654 refactor this so we can reuse it for other invoice types
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
            ->withMerged($this->number)  // @todo mad654: refactor this
            ->with('creator', $this->creator)
            ->with('invoiceRecipient', $this->recipient)
            ->with('paymentInformation', $this->bankAccount)
            ->with('rows', $this->rows)
            ->with('totalEuroCent', $this->totalEuroCent)
            ->with('taxEuroCent', $this->taxEuroCent)
            ->with('totalInclTaxEuroCent', $this->totalEuroCent + $this->taxEuroCent)
            ;
    }
}