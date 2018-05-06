<?php


namespace mad654\printable;


interface Printer
{
    public function with($key, $value): Printer;
}