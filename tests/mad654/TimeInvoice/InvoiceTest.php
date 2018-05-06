<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printer;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @test
     */
    public function fromWorkingHours_always_createsInstance() {
        $this->assertInstanceOf(Invoice::class, Invoice::fromWorkingHours([]));
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function print_always_returnsPrinterInstance() {
        $printer = $this->getMockForAbstractClass(Printer::class);
        $this->assertInstanceOf(Printer::class, Invoice::fromWorkingHours([])->print($printer));
    }
}