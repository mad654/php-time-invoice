<?php


namespace mad654\printable;


final class TestPrinter implements Printer
{
    public $printedValues = [];


    public function with($key, $value): Printer {
        if (array_key_exists($key, $this->printedValues)) {
            throw new \RuntimeException("$key was already printed");
        }
        
        $this->printedValues[$key] = $value;
    }
}