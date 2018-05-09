<?php


namespace mad654\AtWorkTimeInvoice;

use mad654\printable\TestPrinter;
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
    public function fromFile_fromFixtures_returnsArrayWithFiveWorkingHourObjects() {
        $actual = $this->instanceFromFixtures();

        $this->assertCount(5, $actual);
        $this->assertInstanceOf(WorkingHour::class, $actual[0]);
        $this->assertInstanceOf(WorkingHour::class, $actual[1]);
        $this->assertInstanceOf(WorkingHour::class, $actual[2]);
        $this->assertInstanceOf(WorkingHour::class, $actual[3]);
        $this->assertInstanceOf(WorkingHour::class, $actual[4]);
    }

    /**
     * @test
     * @throws FileNotFoundException
     */
    public function fromFile_fromFixtures_firstItemWasParsedCorrectly() {
        $expected = AtWorkWorkingHour::fromArray(array(
            'LfdNummer' => '1',
            'Anfang' => '2018-04-27 12:17',
            'Ende' => '2018-04-27 16:37',
            'Pause' => '0',
            'Dauer' => '4,333',
            'Stundensatz' => '36,00',
            'Zuschlag' => '0,00',
            'Verdienst' => '156,00',
            'Kunde' => 'Muster ',
            'Projekt' => 'GA',
            'Aufgabe' => 'Fahrzeit',
            'Notiz' => NULL,
        ));

        $this->assertEquals($expected, $this->instanceFromFixtures()[0]);
    }

    /**
     * @test
     * @throws FileNotFoundException
     */
    public function fromFile_fromFixtures_itemsToEuroCentReturnsCorrectValues() {
        $this->assertSame(15600, $this->instanceFromFixtures()[0]->toEuroCent());
        $this->assertSame(360, $this->instanceFromFixtures()[1]->toEuroCent());
        $this->assertSame(23280, $this->instanceFromFixtures()[3]->toEuroCent());
        $this->assertSame(720, $this->instanceFromFixtures()[4]->toEuroCent());
    }

    /**
     * @test
     * @throws FileNotFoundException
     */
    public function fromFile_fromFixturesWithPause_thirdItemToEuroCentReturns16680() {
        $this->assertSame(16668, $this->instanceFromFixtures()[2]->toEuroCent());
    }

    /**
     * @test
     * @throws FileNotFoundException
     */
    public function fromFile_fromFixtures_addAllItemsReturns56628EuroCent() {
        $actual = new AtWorkWorkingHour();
        $hours = $this->instanceFromFixtures();

        foreach ($hours as $hour) {
            $actual->add($hour);
        }

        $this->assertSame(56628, $actual->toEuroCent());
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
     * @test
     */
    public function print_always_printsUsefullDefaults() {
        $workingHour = new AtWorkWorkingHour();
        $printer = new TestPrinter();
        $workingHour->print($printer);

        $actual = $printer->printedValues;

        $this->assertCount(4, $actual);
        $this->assertSame('', $actual['text']);
        $this->assertSame(0, $actual['priceEuroCent']);
        $this->assertSame(0.0, $actual['amountHundredth']);
        $this->assertSame(0, $actual['rowTotalEuroCent']);
    }

    /**
     * @test
     */
    public function print_fromFixtures_rowOneContainsExpectedText() {
        $hours = $this->instanceFromFixtures();
        $printer = new TestPrinter();
        $hours[0]->print($printer);

        $actual = $printer->printedValues;

        $this->assertSame('Muster/GA Fahrzeit', $actual['text']);
    }
    // @todo verify print returns expected values

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
    protected function instanceFromFixtures() {
        return AtWorkWorkingHour::fromFile(
            __DIR__ . '/fixtures/excel-export-atwork-2018-04-30-10_36_18.csv'
        );
    }
}