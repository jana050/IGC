<?php

namespace Site\view;

/**
 * Payment Release Letter for a GEM Direct purchase.
 *
 * Format: Government of India - Indira Gandhi Centre for Atomic Research -
 * Materials Chemistry & Metal Fuel Cycle Group - Subject: Payment release.
 *
 * Expected $data keys (from GemDirectHelper::getOneData):
 *   indent_no, gem_contract_no, gem_contract_date, tax_invoice_no, tax_invoice_date,
 *   schedule_delivery_date, actual_delivery_date, payment_value, liquidated_damages,
 *   payable_value, firm_name, firm_address, firm_bank_account, firm_ifsc,
 *   user_comments, created_by, created_by_designation, sd_org_id_desc,
 *   consignee_name, consignee_designation, buyer_name, buyer_designation,
 *   approving_authority
 */
class GemDirectPaymentPdf
{
    private $data = [];

    public function __construct($data = [])
    {
        $this->data = is_object($data) ? (array) $data : $data;
    }

    private function get($key)
    {
        return isset($this->data[$key]) && $this->data[$key] !== null
            ? htmlspecialchars((string) $this->data[$key])
            : '';
    }

    private function fmtDate($key)
    {
        $v = isset($this->data[$key]) ? $this->data[$key] : '';
        if (!$v) return '';
        $t = strtotime($v);
        return $t ? date("d-m-Y", $t) : htmlspecialchars((string) $v);
    }

    private function fmtMoney($key)
    {
        $v = isset($this->data[$key]) ? $this->data[$key] : 0;
        return number_format((float) $v, 2, '.', ',');
    }

    private function numberToWords($number)
    {
        // Indian-style number-to-words (basic, rounded to whole rupees).
        $number = (int) round((float) $number);
        if ($number === 0) return "Zero";
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety',
        ];
        $twoDigit = function ($n) use ($words) {
            if ($n < 20) return $words[$n];
            $t = intdiv($n, 10) * 10;
            $u = $n % 10;
            return $words[$t] . ($u ? ' ' . $words[$u] : '');
        };
        $threeDigit = function ($n) use ($words, $twoDigit) {
            $h = intdiv($n, 100);
            $r = $n % 100;
            $out = $h ? $words[$h] . ' Hundred' : '';
            if ($r) $out .= ($out ? ' and ' : '') . $twoDigit($r);
            return $out;
        };
        $parts = [];
        $crore = intdiv($number, 10000000);
        $number %= 10000000;
        $lakh = intdiv($number, 100000);
        $number %= 100000;
        $thousand = intdiv($number, 1000);
        $number %= 1000;
        $rest = $number;
        if ($crore) $parts[] = $twoDigit($crore) . ' Crore';
        if ($lakh) $parts[] = $twoDigit($lakh) . ' Lakh';
        if ($thousand) $parts[] = $twoDigit($thousand) . ' Thousand';
        if ($rest) $parts[] = $threeDigit($rest);
        return trim(implode(' ', $parts));
    }

    public function get_html()
    {
        $indent = $this->get('indent_no');
        $contractNo = $this->get('gem_contract_no');
        $contractDate = $this->fmtDate('gem_contract_date');
        $invoiceNo = $this->get('tax_invoice_no');
        $invoiceDate = $this->fmtDate('tax_invoice_date');
        $schedDate = $this->fmtDate('schedule_delivery_date');
        $actualDate = $this->fmtDate('actual_delivery_date');
        $value = $this->fmtMoney('payment_value');
        $ld = $this->fmtMoney('liquidated_damages');
        $payable = $this->fmtMoney('payable_value');
        $payableWords = $this->numberToWords($this->data['payable_value'] ?? 0);
        $firmName = $this->get('firm_name');
        $firmAddress = $this->get('firm_address');
        $account = $this->get('firm_bank_account');
        $ifsc = $this->get('firm_ifsc');
        $valueWords = $this->numberToWords($this->data['payment_value'] ?? 0);
        $userComments = $this->get('user_comments');
        $printDate = date("d-m-Y");

        $indentor = $this->get('created_by');
        $indentorDesig = $this->get('created_by_designation');
        $org = $this->get('sd_org_id_desc');

        $consignee = $this->get('consignee_name');
        $consigneeDesig = $this->get('consignee_designation');

        $authority = $this->get('approving_authority');

        $buyer = $this->get('buyer_name');
        $buyerDesig = $this->get('buyer_designation');

        $ldLine = ((float) ($this->data['liquidated_damages'] ?? 0) > 0)
            ? "LD amount is Rs. {$ld}/-"
            : "No LD applicable.";

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Release - GEM Direct</title>
<style>
    body { font-family: "Times New Roman", serif; font-size: 12pt; color: #000; }
    .container { width: 100%; padding: 30px 40px; }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    .right { text-align: right; }
    .underline { text-decoration: underline; }
    .muted { color: #333; }
    p { margin: 8px 0; text-align: justify; line-height: 1.5; }
    table.seller { border-collapse: collapse; width: 60%; margin-top: 10px; }
    table.seller td { border: 1px solid #000; padding: 4px 8px; }
    .seller-header { background: #eee; text-align: center; font-weight: bold; }
    .signline { margin-top: 40px; }
</style>
</head>
<body>
<div class="container">

    <div class="center bold">GOVERNMENT OF INDIA</div>
    <div class="center bold">INDIRA GANDHI CENTRE FOR ATOMIC RESEARCH</div>
    <div class="center bold">MATERIALS CHEMISTRY &amp; METAL FUEL CYCLE GROUP</div>

    <p>
        <span class="underline">Ref: GeM Indent No {$indent}</span>
        <span style="float:right">Dated: {$printDate}</span>
    </p>

    <p><span class="underline">Ref: GeM Contract No {$contractNo}</span></p>

    <p><span class="bold">Sub:</span> Payment release &ndash; reg</p>

    <p>
        Please find enclosed herewith the Tax invoice No. <span class="bold">{$invoiceNo}</span>
        dated <span class="bold">{$invoiceDate}</span> for
        Rs. <span class="bold">{$value}/-</span>
        ( Rupees <span class="bold">{$valueWords} only</span> ) of
        <span class="bold">{$firmName}</span>,
        <span class="bold">{$firmAddress}</span>, duly certified against GeM contract No
        <span class="bold">{$contractNo}</span> dated <span class="bold">{$contractDate}</span>.
    </p>

    <p>
        Schedule date of delivery as per the contract is by <span class="bold">{$schedDate}</span>.
        Actual delivery date of product was on <span class="bold">{$actualDate}</span>.
        {$ldLine}
    </p>

    <p><span class="bold">User Comments:</span> {$userComments}</p>

    <p>
        The firm has supplied the items as per GeM order and it is inspected and accepted by us.
        Total amount payable is Rs. <span class="bold">{$payable}/-</span>
        (Rupees <span class="bold">{$payableWords} only</span>).
        PAO, IGCAR may kindly release the payment of Rs. <span class="bold">{$payable}/-</span>
        to the supplier and file may please be regularized accordingly.
    </p>

    <table class="seller">
        <tr><td colspan="2" class="seller-header">SELLER DETAILS</td></tr>
        <tr><td class="bold">Firm Name</td><td>{$firmName}</td></tr>
        <tr><td class="bold">Account Number</td><td>{$account}</td></tr>
        <tr><td class="bold">IFSC Code</td><td>{$ifsc}</td></tr>
    </table>

    <div class="signline right">
        <div>{$indentor}, {$indentorDesig}</div>
        <div>{$org}</div>
    </div>

    <p class="signline">Thro</p>
    <p><span class="bold">Consignee:</span> {$consignee} {$consigneeDesig}</p>

    <p class="signline"><span class="bold">Approving Authority:</span> {$authority}</p>

    <p><span class="bold">Buyer:</span> {$buyer} {$buyerDesig}</p>

    <p class="signline">
        To<br>
        &nbsp;&nbsp;&nbsp;&nbsp;PAO, IGCAR.<br>
        &nbsp;&nbsp;&nbsp;&nbsp;(For payment release)
    </p>

</div>
</body>
</html>
HTML;
    }

    public static function getHtml($data = [])
    {
        return (new self($data))->get_html();
    }
}
