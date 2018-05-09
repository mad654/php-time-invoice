<?php


namespace mad654\printable;


interface Printer
{
    public function with($key, $value): Printer;

    /**
     *
     * Prints given values and merges its key/value pairs
     * into current structure
     *
     * only work for printable or arrays
     *
     * @param $value
     * @return Printer
     */
    public function withMerged($value): Printer;
}