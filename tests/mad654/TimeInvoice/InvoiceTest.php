<?php


namespace mad654\TimeInvoice;


use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @test
     */
    public function fromWorkingHours_always_createsInstance() {
        $this->assertInstanceOf(Invoice::class, Invoice::fromWorkingHours([]));
    }
}