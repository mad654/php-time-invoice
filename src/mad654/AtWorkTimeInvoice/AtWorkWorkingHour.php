<?php


namespace mad654\AtWorkTimeInvoice;

use mad654\TimeInvoice\WorkingHour;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Implements `mad654\TimeInvoice\WorkingHour` for `atWork` ios app exports
 */
class AtWorkWorkingHour implements WorkingHour
{

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
    protected static function parseFile(string $filePath): array {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $contents = self::loadFile($filePath);
        return $serializer->deserialize($contents, self::class, 'csv');
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

        return mb_substr($contents, 0, $splitPos);
    }

    public function add(WorkingHour $hour): void {
        // TODO: Implement add() method.
    }

    public function toEuroCent(): int {
        return 0;
    }
}