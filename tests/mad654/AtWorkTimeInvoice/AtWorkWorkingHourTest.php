<?php


namespace mad654\AtWorkTimeInvoice;

use mad654\TimeInvoice\WorkingHour;
use PHPUnit\Framework\TestCase;


class AtWorkWorkingHourTest extends TestCase
{
    /**
     * @test
     */
    public function construct_always_returnsAWorkingHour() {
        $this->assertInstanceOf(WorkingHour::class, $this->instance());
    }

    /**
     * @test
     * @throws FileNotFoundException
     */
    public function fromFile_always_returnsArrayWithCountFive() {
        $this->assertCount(5, $this->instanceFromFixtures());
    }

    /**
     * @test
     * @expectedException \mad654\AtWorkTimeInvoice\FileNotFoundException
     * @expectedExceptionMessage Path not exists or is no file: `not-existing-file.name`
     */
    public function fromFile_fileNotExists_throwsException() {
        AtWorkWorkingHour::fromFile('not-existing-file.name');
    }

    /**
     * @return AtWorkWorkingHour
     */
    protected function instance(): AtWorkWorkingHour {
        return new AtWorkWorkingHour();
    }

    /**
     * @return WorkingHour[]
     * @throws FileNotFoundException
     */
    protected function instanceFromFixtures(): array {
        return AtWorkWorkingHour::fromFile(
            __DIR__ . '/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv'
        );
    }
}