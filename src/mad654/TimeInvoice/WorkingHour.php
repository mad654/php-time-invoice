<?php

namespace mad654\TimeInvoice;

use mad654\printable\Printable;

interface WorkingHour extends Printable
{
    public function add(WorkingHour $hour): void;

    public function toEuroCent(): int;
}