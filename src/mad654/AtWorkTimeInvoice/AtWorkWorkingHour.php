<?php


namespace mad654\AtWorkTimeInvoice;

use DateTime;
use mad654\printable\Printer;
use mad654\TimeInvoice\SimpleWorkingHour;
use mad654\TimeInvoice\WorkingHour;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Implements `mad654\TimeInvoice\WorkingHour` for `atWork` ios app exports
 */
final class AtWorkWorkingHour implements WorkingHour
{
    private $LfdNummer;
    private $Anfang;
    private $Ende;
    private $Pause;
    private $Dauer;
    private $Stundensatz;
    private $Zuschlag;
    private $Verdienst;
    private $Kunde;
    private $Projekt;
    private $Aufgabe;
    private $Notiz;

    /**
     * @var SimpleWorkingHour
     */
    private $addedWorkingHours;

    /**
     * @param string $fileName
     * @return WorkingHour[]
     * @throws FileNotFoundException
     */
    public static function fromFile(string $fileName): array {
        $filePath = realpath($fileName);

        if ($filePath === false || !is_file($filePath)) {
            throw new FileNotFoundException($fileName);
        }

        return self::parseFile($filePath);
    }

    /**
     * @param string $filePath
     * @return WorkingHour[]
     */
    private static function parseFile(string $filePath): array {
        $result = [];
        $serializer = new Serializer([], [new CsvEncoder("\t")]);
        $decoded =  $serializer->decode(self::loadFile($filePath), 'csv');

        foreach ($decoded as $item) {
            $result[] = self::fromArray($item);
        }

        return array_reverse($result, false);
    }

    private static function loadFile(string $filePath): string {
        $contents = mb_convert_encoding(file_get_contents($filePath), 'UTF-8', 'UTF-16LE');

        // `Einträge` is first line + one char for linefeed
        $cleaned = preg_replace('/^.+\n/', '', $contents);
        $splitPos = mb_strpos($cleaned, 'Gesamt', 0, 'UTF-8');

        if ($splitPos === false) {
            throw new \RuntimeException("Whoop, whoop there must be `Gesamt` in the input file");
        }

        $cleaned = mb_substr($cleaned, 0, $splitPos);
        $cleaned = str_replace('#', 'LfdNummer', $cleaned);
        $cleaned = str_replace(', €', '', $cleaned);

        return $cleaned;
    }

    public static function fromArray(array $data): self {
        $instance = new self();

        foreach ($instance as $key => $currentValues) {
            if (array_key_exists($key, $data)) {
                $instance->$key = trim($data[$key]);
            }
        }

        $instance->Stundensatz = str_replace(",", ".", $instance->Stundensatz);

        return $instance;
    }

    public function add(WorkingHour $hour): void {
        if (is_null($this->addedWorkingHours)) {
            $this->addedWorkingHours = new SimpleWorkingHour($this->amountHundredth(), $this->priceEuroCent());
        }

        $this->addedWorkingHours->add($hour);
    }

    public function toEuroCent(): int {
        if (!is_null($this->addedWorkingHours)) {
            return $this->addedWorkingHours->toEuroCent();
        }
        $price = $this->amountHundredth() / 100 * $this->priceEuroCent();
        return round($price, 2);
    }

    private function priceEuroCent(): int {
        return $this->Stundensatz * 100;
    }

    private function amountHundredth(): float {
        return $this->diffHundredth() - $this->breakHundredth();
    }

    private function diffHundredth(): float {
        $start = new \DateTime($this->Anfang);
        $end = new \DateTime($this->Ende);
        $diff = $end->diff($start);

        return ($diff->h * 60 + $diff->i) / 0.6;
    }

    private function breakHundredth(): float {
        return round(floatval(str_replace(',', '.', $this->Pause)) * 100);
    }

    public function print(Printer $printer): Printer {
        return $printer
            ->with('text', $this->renderText())
            ->with('priceEuroCent', $this->priceEuroCent())
            ->with('amountHundredth', $this->amountHundredth())
            ->with('rowTotalEuroCent', $this->toEuroCent())
            ;
    }

    /**
     * @return string
     */
    private function renderText(): string {
        if (!empty($this->Kunde) || !empty($this->Projekt)) {
            $partsBegin = new DateTime($this->Anfang);
            $partsEnd = new DateTime($this->Ende);

            $result = $this->renderWithProject($partsBegin, $partsEnd);
            if (empty($this->Projekt)) {
                $result = $this->renderWithoutProject($partsBegin, $partsEnd);
            }
            if (!empty($this->Aufgabe)) {
                $result = vsprintf('%s >> %s', [$result, $this->Aufgabe]);
            }

            return $result;
        }

        return "";
    }

    /**
     * @param $partsBegin
     * @param $partsEnd
     * @return string
     */
    private function renderWithProject($partsBegin, $partsEnd): string {
        return vsprintf(
            "%s %s bis %s \n %s/%s", [
            $partsBegin->format('d.m.Y'),
            $partsBegin->format('H:i'),
            $partsEnd->format('H:i'),
            $this->Kunde,
            $this->Projekt
        ]
        );
    }

    private function renderWithoutProject($partsBegin, $partsEnd): string {
        return vsprintf(
            "%s %s bis %s \n %s", [
                $partsBegin->format('d.m.Y'),
                $partsBegin->format('H:i'),
                $partsEnd->format('H:i'),
                $this->Kunde
            ]
        );
    }
}
