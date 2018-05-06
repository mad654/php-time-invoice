<?php


namespace mad654\AtWorkTimeInvoice;


class FileNotFoundException extends \Exception
{

    /**
     * FileNotFoundException constructor.
     */
    public function __construct(string $fileName) {
        parent::__construct("Path not exists or is no file: `not-existing-file.name`");
    }
}