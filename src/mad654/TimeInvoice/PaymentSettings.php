<?php


namespace mad654\TimeInvoice;


use mad654\printable\Printable;
use mad654\printable\Printer;

class PaymentSettings implements Printable, InvoiceBankAccount
{
    /**
     * @var string
     */
    private $dueDateText;

    /**
     * @var string
     */
    private $accountOwner;

    /**
     * @var string
     */
    private $bank;

    /**
     * @var string
     */
    private $iban;

    /**
     * @var string
     */
    private $bic;

    /**
     * EmptyInvoiceBankAccount constructor.
     * @param string $dueDateText
     * @param string $accountOwner
     * @param string $bank
     * @param string $iban
     * @param string $bic
     */
    public function __construct(string $dueDateText, string $accountOwner, string $bank, string $iban, string $bic) {
        $this->dueDateText = $dueDateText;
        $this->accountOwner = $accountOwner;
        $this->bank = $bank;
        $this->iban = $iban;
        $this->bic = $bic;
    }

    public function print(Printer $printer): Printer {
        return $printer->withMerged([
            'dueDateText' => $this->dueDateText,
            'accountOwner' => $this->accountOwner,
            'bank' => $this->bank,
            'iban' => $this->iban,
            'bic' => $this->bic,
            'purposeOfPayment' => '',
        ]);
    }
}