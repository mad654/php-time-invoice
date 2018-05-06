<?php


namespace mad654\AtWorkTimeInvoice;

use mad654\TimeInvoice\WorkingHour;

/**
 * Implements `mad654\TimeInvoice\WorkingHour` for `atWork` ios app exports
 */
class AtWorkWorkingHour implements WorkingHour
{

    /**
     * AtWorkWorkingHour constructor.
     */
    public function __construct() {
    }

    /**
     * @param string $fileName
     * @return WorkingHour[]
     * @throws FileNotFoundException
     */
    public static function fromFile(string $fileName) {
        $filePath = realpath($fileName);

        if ($filePath === false || !is_file($filePath)) {
            throw new FileNotFoundException($fileName);
        }

        return [1,2,3,4,5];
    }

    public function add(WorkingHour $hour): void {
        // TODO: Implement add() method.
    }

    public function toEuroCent(): int {
        return 0;
    }
}