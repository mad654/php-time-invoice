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

    /**
     * @test
     */
    public function fromWorkingHours_fourWorkingHours_printsFourRows() {
        $actual = $this->printInvoice([
            new SimpleWorkingHour(50, 3600), // 30 Minuten a 36 €
            new SimpleWorkingHour(25, 3600), // 15 Minuten a 36 €
            new SimpleWorkingHour(25, 3600), // 15 Minuten a 36 €
            new SimpleWorkingHour(100, 0), // 60 Minuten a 0 €
        ]);

        $this->assertCount(4, $actual['rows']);
    }

    /**
     * @test
     */
    public function fromWorkingHours_oneRow_printsRowDetails() {
        $printedInvoice = $this->printInvoice([new SimpleWorkingHour(50, 3600)]);
        $actual = $printedInvoice['rows'][0];

        $this->assertArrayHasKey('pos', $actual);
        $this->assertSame(1, $actual['pos']);
        $this->assertArrayHasKey('text', $actual);
        $this->assertSame('', $actual['text']);
        $this->assertArrayHasKey('priceEuroCent', $actual);
        $this->assertSame(3600, $actual['priceEuroCent']);
        $this->assertArrayHasKey('amountHundredth', $actual);
        $this->assertSame(50, $actual['amountHundredth']);
        $this->assertArrayHasKey('rowTotalEuroCent', $actual);
        $this->assertSame(1800, $actual['rowTotalEuroCent']);
    }

    private function printInvoice(array $workingHours): array {
        $invoice = Invoice::fromWorkingHours($workingHours);

        $printer = new TestPrinter();
        $invoice->print($printer);

        return $printer->printedValues;
    }


}