<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printable;
use mad654\printable\Printer;

class SimpleInvoiceAddress implements Printable, InvoiceAddress
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $city;

    /**
     * EmptyInvoiceAddress constructor.
     * @param string $name
     * @param string $address
     * @param string $zip
     * @param string $city
     */
    public function __construct(string $name, string $address, string $zip, string $city) {
        $this->name = $name;
        $this->address = $address;
        $this->zip = $zip;
        $this->city = $city;
    }

    public function print(Printer $printer): Printer {
        return $printer->withMerged([
            'name' => $this->name,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
        ]);
    }
}