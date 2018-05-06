<?php


namespace mad654\AtWorkTimeInvoice;

use mad654\TimeInvoice\SimpleWorkingHour;
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
        $contents = file_get_contents($filePath);

        $splitPos = mb_strpos($contents, 'Gesamt');
        if ($splitPos === false) {
            throw new \RuntimeException("Whoop, whoop there must be `Gesamt` in the input file");
        }

        $contents = mb_substr($contents, 0, $splitPos - 1);
        return $serializer->deserialize($contents, self::class, 'csv');
    }

    public function add(WorkingHour $hour): void {
        // TODO: Implement add() method.
    }

    public function toEuroCent(): int {
        return 0;
    }
}