<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printer;
use mad654\printable\TestPrinter;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function print_always_returnsPrinterInstance() {
        $printer = $this->getMockForAbstractClass(Printer::class);
        $this->assertInstanceOf(Printer::class, Invoice::fromWorkingHours([])->print($printer));
    }

    /**
     * @test
     */
    public function fromWorkingHours_always_printsUsefulDefaults() {
        $expected = [
            'invoiceRecipient' => [],
            'paymentInformation' => [],
            'rows' => [],
            'totalEuroCent' => 0,
            'taxEuroCent'   => 0,
            'totalInclTaxEuroCent' => 0
        ];

        $this->assertEquals($expected, $this->printInvoice([]));
    }

    /**
     * @test
     */
    public function fromWorkingHours_someWorkingHours_printsTotal3600EuroCent() {
        $actual = $this->printInvoice([
            new SimpleWorkingHour(50, 3600), // 30 Minuten a 36 €
            new SimpleWorkingHour(25, 3600), // 15 Minuten a 36 €
            new SimpleWorkingHour(25, 3600), // 15 Minuten a 36 €
            new SimpleWorkingHour(100, 0), // 60 Minuten a 0 €
        ]);

        $this->assertSame(3600, $actual['totalEuroCent']);
    }

    // @todo mad654 verify has rowCount = workingHourCount
    // @todo mad654 verify first row has parts: position, text, single price, amount, rowTotal

    private function printInvoice(array $workingHours): array {
        $invoice = Invoice::fromWorkingHours($workingHours);

        $printer = new TestPrinter();
        $invoice->print($printer);

        return $printer->printedValues;
    }


}