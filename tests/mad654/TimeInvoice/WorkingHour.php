<?php

namespace mad654\TimeInvoice;

interface WorkingHour
{
    public function add(WorkingHour $hour): void;

    public function toEuroCent(): int;
}