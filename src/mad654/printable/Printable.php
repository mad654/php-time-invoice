<?php

namespace mad654\printable;

interface Printable
{
    public function print(Printer $printer): Printer;
}