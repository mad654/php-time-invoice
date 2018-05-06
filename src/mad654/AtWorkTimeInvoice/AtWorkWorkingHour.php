<?php


namespace mad654\AtWorkTimeInvoice;

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
     * @param string $fileName
     * @return WorkingHour[]
     * @throws FileNotFoundException
     */
    public static function fromFile(string $fileName) {
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
    protected static function parseFile(string $filePath) {
        $result = [];
        $serializer = new Serializer([], [new CsvEncoder("\t")]);
        $decoded =  $serializer->decode(self::loadFile($filePath), 'csv');

        foreach ($decoded as $item) {
            $result[] = self::fromArray($item);
        }

        return $result;
    }

    /**
     * @param string $filePath
     * @return bool|string
     */
    protected static function loadFile(string $filePath) {
        $contents = mb_convert_encoding(file_get_contents($filePath), 'UTF-8', 'UTF-16LE');
        $splitPos = mb_strpos($contents, 'Gesamt', 0, 'UTF-8');

        if ($splitPos === false) {
            throw new \RuntimeException("Whoop, whoop there must be `Gesamt` in the input file");
        }

        // `Einträge` is first line + one char for linefeed
        $lengthFirstLine = strlen("Einträge") + 1;
        $cleaned = mb_substr($contents, $lengthFirstLine, $splitPos - $lengthFirstLine);
        $cleaned = str_replace('#', 'LfdNummer', $cleaned);
        $cleaned = str_replace(', €', '', $cleaned);

        return $cleaned;
    }

    public static function fromArray(array $data): self {
        $instance = new self();

        foreach ($instance as $key => $currentValues) {
            if (array_key_exists($key, $data)) {
                $instance->$key = $data[$key];
            }
        }

        return $instance;
    }

    public function add(WorkingHour $hour): void {
        // TODO: Implement add() method.
    }

    public function toEuroCent(): int {
        return 0;
    }
}