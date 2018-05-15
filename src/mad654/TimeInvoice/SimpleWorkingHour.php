<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printer;

final class SimpleWorkingHour implements WorkingHour
{
    private $amountHundredth = 0;
    private $priceEuroCent = 0;
    private $totalEuroCent = 0;
    private $text;

    public function __construct(int $amountHundredth, int $priceEuroCent, $text = '') {
        $this->amountHundredth = $amountHundredth;
        $this->priceEuroCent = $priceEuroCent;
        $this->totalEuroCent = $amountHundredth / 100 * $priceEuroCent;
        $this->text = $text;
    }

    public function add(WorkingHour $hour): void {
        $this->totalEuroCent += $hour->toEuroCent();
    }

    public function toEuroCent(): int {
        return $this->totalEuroCent;
    }

    public function print(Printer $printer): Printer {
        return $printer
            ->with('text', $this->text)
            ->with('priceEuroCent', $this->priceEuroCent)
            ->with('amountHundredth', $this->amountHundredth)
            ->with('rowTotalEuroCent', $this->toEuroCent())
            ;
    }
}