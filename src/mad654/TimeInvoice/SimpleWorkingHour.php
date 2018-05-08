<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printer;

final class SimpleWorkingHour implements WorkingHour
{
    private $amountHundredth = 0;
    private $priceEuroCent = 0;
    private $totalEuroCent = 0;

    public function __construct(int $amountHundredth, int $priceEuroCent) {
        $this->amountHundredth = $amountHundredth;
        $this->priceEuroCent = $priceEuroCent;
        $this->totalEuroCent = $amountHundredth / 100 * $priceEuroCent;
    }

    public function add(WorkingHour $hour): void {
        $this->totalEuroCent += $hour->toEuroCent();
    }

    public function toEuroCent(): int {
        return $this->totalEuroCent;
    }

    public function print(Printer $printer): Printer {
        // @todo text and pos should be dynamically
        return $printer
            ->with('pos', 1)
            ->with('text', '')
            ->with('priceEuroCent', $this->priceEuroCent)
            ->with('amountHundredth', $this->amountHundredth)
            ->with('rowTotalEuroCent', $this->toEuroCent())
            ;
    }
}