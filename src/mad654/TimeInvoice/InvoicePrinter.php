<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printer;
use mad654\printable\TestPrinter;
use Spipu\Html2Pdf\Html2Pdf;

class InvoicePrinter implements Printer
{
    /**
     * @var TestPrinter
     */
    private $printer;

    /**
     * @var string
     */
    private $template;

    public function __construct(string $templateFile) {
        $this->printer = new TestPrinter();
        $this->template = realpath($templateFile);

        if ($this->template === false) {
            throw new \InvalidArgumentException("Template not found: `$templateFile`");
        }
    }

    public function with($key, $value): Printer {
        $this->printer->with($key, $value);
        return $this;
    }

    /**
     *
     * Prints given values and merges its key/value pairs
     * into current structure
     *
     * only work for printable or arrays
     *
     * @param $value
     * @return Printer
     */
    public function withMerged($value): Printer {
        $this->printer->withMerged($value);
        return $this;
    }

    /**
     * @param $path
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     */
    public function toPdf($path): void {
        $html = $this->renderHtml();
        $html2pdf = new HTML2PDF("P","A4","de", true, "UTF-8", array(20, 10, 20, 10));
        $html2pdf->WriteHTML($html);
        $html2pdf->output($path, 'F');
    }

    private function renderHtml(): string {
        $dueDate = new \DateTime();
        $dueDate->add(new \DateInterval("P2W"));

        // $data is used by $this->template
        $data = $this->printer->printedValues;

        ob_start();
        include $this->template;
        return ob_get_clean();
    }
}