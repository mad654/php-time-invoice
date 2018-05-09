<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printable;
use mad654\printable\Printer;

class WorkingHourInvoicePosition implements Printable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var WorkingHour
     */
    private $workingHour;

    /**
     * InvoicePosition constructor.
     * @param string $id
     * @param WorkingHour $workingHour
     */
    public function __construct(string $id, WorkingHour $workingHour) {
        $this->id = $id;
        $this->workingHour = $workingHour;
    }


    public function print(Printer $printer): Printer {
        return $printer
            ->with('pos', $this->id)
            ->withMerged($this->workingHour)
            ;
    }
}