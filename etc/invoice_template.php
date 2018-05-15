<?php
function res($relativePath) {
    $searchPath = __DIR__;
    $realpath = realpath("$searchPath/$relativePath");

    if ($realpath === false) {
        throw new InvalidArgumentException(
            "Resource not found: `$realpath` in `$searchPath`"
        );
    }

    return $realpath;
}

?>


<!--
  Thx to Laurent Minguet https://github.com/spipu/html2pdf
  Basic template from  https://github.com/spipu/html2pdf/blob/master/examples/res/exemple07a.php
-->
<style type="text/css">
    <!--
    table {
        vertical-align: top;
    }

    tr {
        vertical-align: top;
    }

    td {
        vertical-align: top;
    }

    -->
</style>
<page backtop="0" backbottom="50mm" style="font-size: 12pt;font-family: 'courier'">
    <bookmark title="Rechnung" level="0"></bookmark>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div><?php echo $data['creator']['name'] ?></div>
    <div><?php echo $data['creator']['address'] ?></div>
    <div><?php echo $data['creator']['zip']?> <?php echo $data['creator']['city'] ?></div>
    <br>
    <br>
    <div><?php echo $data['invoiceRecipient']['name'] ?></div>
    <div><?php echo $data['invoiceRecipient']['address'] ?></div>
    <div><?php echo $data['invoiceRecipient']['zip']?> <?php echo $data['invoiceRecipient']['city'] ?></div>
    <br>
    <br>
    <div style="text-align:right; "><?php echo $data['creator']['city'] ?>, den <?php echo date('d.m.Y'); ?></div>
    <br>
    <b>Rechnung: <?php echo $data['number'] ?></b><br>
    <br>
    <br>
    <table cellspacing="0"
           style="width: 100%; padding: 5px; border: solid 0px black; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 6%; padding: 5px; text-align: left; border-bottom: solid 0.5px black;">Pos</th>
            <th style="width: 58%; padding: 5px; text-align: left; border-bottom: solid 0.5px black;">Beschreibung</th>
            <th style="width: 13%; padding: 5px; text-align: right; border-bottom: solid 0.5px black;">Preis</th>
            <th style="width: 10%; padding: 5px; text-align: right; border-bottom: solid 0.5px black;">Anzahl</th>
            <th style="width: 13%; padding: 5px; text-align: right; border-bottom: solid 0.5px black;">Summe</th>
        </tr>
        <?php
        $invoiceTotal = round($data['totalInclTaxEuroCent'] / 100, 2);
        foreach ($data['rows'] as $row) {
            $pos = $row['pos'];
            $text = str_replace("\n", "<br>", $row['text']);
            $amount = round($row['amountHundredth'] / 100, 2);
            $price = round($row['priceEuroCent'] / 100, 2);
            $rowTotal = round($row['rowTotalEuroCent'] / 100, 2);
            ?>
            <tr>
                <td style="width: 6%; padding: 5px; text-align: left; border-bottom: solid 0.5px black;"><?php echo $pos; ?></td>
                <td style="width: 58%; padding: 5px; text-align: left; border-bottom: solid 0.5px black;"><?php echo $text; ?></td>
                <td style="width: 13%; padding: 5px; text-align: right; border-bottom: solid 0.5px black;"><?php echo number_format($price, 2, ',', '.'); ?> &euro;</td>
                <td style="width: 10%; padding: 5px; text-align: right; border-bottom: solid 0.5px black;"><?php echo number_format($amount, 2, ',', '.'); ?></td>
                <td style="width: 13%; padding: 5px; text-align: right; border-bottom: solid 0.5px black;"><?php echo number_format($rowTotal, 2, ',', '.'); ?>
                    &euro;
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>
    <br>
    <div style="text-align: right"><b>Gesamtbetrag: <?php echo number_format($invoiceTotal, 2, ',', '.'); ?> &euro;</b></div>
    <br>
    <br>
    <nobreak>
    <div>Der Gesamtbetrag ist ab Erhalt der Rechnung zahlbar innerhalb von <?php echo $data['paymentInformation']['dueDateText'] ?> ohne Abzug auf folgendes Konto:</div>
    <br>
    <table cellspacing="0" style=" text-align: center; font-size: 13pt">
        <tr>
            <td style="width: 30%; text-align: left">Kontoinhaber:</td>
            <td style="width: 70%; text-align: left"><?php echo $data['paymentInformation']['accountOwner'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%; text-align: left">Bank:</td>
            <td style="width: 70%; text-align: left"><?php echo $data['paymentInformation']['bank'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%; text-align: left">IBAN:</td>
            <td style="width: 70%; text-align: left"><?php echo $data['paymentInformation']['iban'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%; text-align: left">BIC:</td>
            <td style="width: 70%; text-align: left"><?php echo $data['paymentInformation']['bic'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%; text-align: left">Verwendungszweck:</td>
            <td style="width: 70%; text-align: left"><?php echo $data['number'] ?></td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <div>
        Es wird gemäß §19 Abs. 1 Umsatzsteuergesetz keine Umsatzsteuer erhoben.
    </div>
    </nobreak>
    <page_footer>
        <div style="text-align: center">
            <?php echo $data['number'] ?> Seite [[page_cu]]/[[page_nb]]
        </div>
        <div style="text-align: center">
            <?php echo $data['creator']['name'] ?>,
            <?php echo $data['creator']['address'] ?>,
            <?php echo $data['creator']['zip']?> <?php echo $data['creator']['city'] ?>
        </div>

    </page_footer>
</page>