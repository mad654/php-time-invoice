<?php


namespace mad654\printable;


final class TestPrinter implements Printer
{
    public $printedValues = [];


    public function with($key, $value): Printer {
        if (array_key_exists($key, $this->printedValues)) {
            throw new \RuntimeException("$key was already printed");
        }

        $this->printedValues[$key] = $this->print($value);

        return $this;
    }

    /**
     *
     * Prints given values and merges its key/value pairs
     * into current structure
     *
     * only works for printable or arrays
     *
     * @param $value
     * @return Printer
     */
    public function withMerged($value): Printer {
        if (!is_array($value) && ! $value instanceof Printable) {
            throw new \RuntimeException("Only arrays and `Printable`s supported here");
        }

        $value = $this->print($value);
        foreach ($value as $key => $value) {
            $this->with($key, $value);
        }

        return $this;
    }

    private function print($value) {
        if (is_array($value)) {
            foreach ($value as $index => $arrayValue) {
                $value[$index] = $this->print($arrayValue);
            }
        }

        if ($value instanceof Printable) {
            $subPrinter = new self();
            $value->print($subPrinter);
            $value = $subPrinter->printedValues;
        }

        return $value;
    }
}