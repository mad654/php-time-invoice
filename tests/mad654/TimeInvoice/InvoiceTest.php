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

    private function printInvoice(array $workingHours): array {
        $invoice = Invoice::fromWorkingHours($workingHours);

        $printer = new TestPrinter();
        $invoice->print($printer);

        return $printer->printedValues;
    }


}