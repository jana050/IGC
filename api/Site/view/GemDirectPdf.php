<?php

namespace Site\View;

class GemDirectPdf
{
    private $data = [];

    public function __construct($data = [])
    {
        $this->data = is_object($data) ? (array)$data : $data;
    }

    private function get($key, $default = '')
    {
        if (!isset($this->data[$key]) || $this->data[$key] === null) {
            return $default;
        }
        $v = $this->data[$key];
        if (is_array($v) || is_object($v)) {
            return $default;
        }
        $s = trim((string)$v);
        if ($s === '' || strcasecmp($s, 'null') === 0 || strcasecmp($s, 'undefined') === 0) {
            return $default;
        }
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function get_html()
    {
        $created_by = $this->get('created_by', '-');
        $icno = $this->get('created_by_icno');
        $indenterName = $icno ? $created_by . ' / ' . $icno : $created_by;

        $sigHos = $this->get('hos_name');
        if (!$sigHos) { $sigHos = $this->get('hos_org_desc'); }

        $sigChairmanSecretary = $this->get('iibcc_chairman_name');
        $sigVetter = $this->get('vetter_user_name');

        $css = '
<style>
@page { margin: 8mm 12mm; }
body { font-family: "Times New Roman", serif; font-size: 14px; color: #000; line-height: 1.3; }
.form-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
.form-table td, .form-table th { border: 1px solid #000; padding: 6px 8px; vertical-align: top; font-size: 12px; word-wrap: break-word; overflow-wrap: break-word; }
.form-table .lbl { font-weight: bold; }
.form-table .sec { font-weight: bold; text-align: center; background: #e6e6e6; font-size: 13px; padding: 6px; }
.form-table .c   { text-align: center; }
.form-table .tall td { height: 130px; vertical-align: top; }
.form-table .sig td { height: 52px; text-align: center; vertical-align: middle; font-weight: bold; }
.form-table .head-row td { vertical-align: middle; }
.form-table .head-row .org    { text-align: center; font-weight: bold; font-size: 15px; line-height: 1.3; }
.form-table .head-row .badge  { text-align: center; font-weight: bold; font-size: 15px; }
.form-table .head-row .title  { text-align: center; font-weight: bold; font-size: 17px; }
.notes { font-size: 10px; margin-top: 6px; line-height: 1.5; }
.notes div { padding: 2px 0; }
</style>';

        $body = '
<table class="form-table">
<colgroup>
    <col style="width:25%"/>
    <col style="width:25%"/>
    <col style="width:25%"/>
    <col style="width:25%"/>
</colgroup>

<tr class="head-row">
    <td class="badge">MC &amp; MFCG</td>
    <td class="org" colspan="2">
        Indira Gandhi Centre for Atomic Research<br/>
        Materials Chemistry &amp; Metal Fuel Cycle Group<br/>
        <span style="font-weight:normal;font-size:12px;">(GEM Direct Indent Form)</span>
    </td>
    <td class="title">INDENT FORM</td>
</tr>

<tr>
    <td class="lbl">IIBCC No.</td>
    <td colspan="3">'.$this->get('iibcc_no', '-').'</td>
</tr>

<tr><td colspan="4" class="sec">Indenter Details</td></tr>

<tr>
    <td class="lbl">GEM ID / Indent No</td>
    <td colspan="3">'.$this->get('indent_no', '-').'</td>
</tr>

<tr>
    <td class="lbl">Name / ICNO</td>
    <td>'.$indenterName.'</td>
    <td class="lbl">Designation / Sub-Group</td>
    <td>'.$this->get('created_by_designation', '-').'</td>
</tr>

<tr>
    <td class="lbl">Section</td>
    <td>'.$this->get('sd_org_id_desc', '-').'</td>
    <td class="lbl">Division / Sub-Group</td>
    <td>'.$this->get('hod_org_desc', '-').'</td>
</tr>

<tr>
    <td class="lbl">E-mail</td>
    <td>'.$this->get('created_by_email', '-').'</td>
    <td class="lbl">Phone No</td>
    <td>'.$this->get('created_by_mobile_no', '-').'</td>
</tr>

<tr><td colspan="4" class="sec">Item Details</td></tr>

<tr>
    <td class="lbl">Item - Brief Description</td>
    <td colspan="3">'.$this->get('item_brief_description', '-').'</td>
</tr>

<tr>
    <td class="lbl">Head of the Account</td>
    <td colspan="3">'.$this->get('head_of_account', '-').'</td>
</tr>

<tr>
    <td class="lbl">Estimate Source</td>
    <td>'.$this->get('estimate_source', '-').'</td>
    <td class="lbl">Cost (INR)</td>
    <td>'.$this->get('cost', '-').'</td>
</tr>

<tr>
    <td class="lbl">GEM ID (in case item available in GEM)</td>
    <td>'.$this->get('gem_id_item', '-').'</td>
    <td class="lbl">Total Cost (INR)</td>
    <td>'.$this->get('total_cost', '-').'</td>
</tr>

<tr><td colspan="4" class="sec">Justification for the Purchase, Quantity and End Use</td></tr>

<tr class="tall">
    <td colspan="4">'.$this->get('justification_purchase', '').'</td>
</tr>

<tr>
    <td colspan="2" class="sec">Detailed Specifications</td>
    <td class="sec c">Quantity</td>
    <td class="sec c">Unit</td>
</tr>

<tr class="tall">
    <td colspan="2">'.$this->get('detailed_specification', '').'</td>
    <td class="c">'.$this->get('quantity', '-').'</td>
    <td class="c">'.$this->get('unit', '-').'</td>
</tr>

<tr>
    <td colspan="4" style="padding:0;">
        <table style="width:100%;border-collapse:collapse;" border="1">

            <tr>
                <td colspan="6" class="sec">Signatures</td>
            </tr>

            <tr class="sig">
                <td width="20%"><b>Indentor</b></td>
                <td width="10%">'.$created_by.'</td>
                <td width="15%"></td>

                <td width="20%"><b>Approving Authority (HOS)</b></td>
                <td width="10%">'.$sigHos.'</td>
                <td width="15%"></td>
            </tr>

            <tr class="sig">
                <td><b>IIBCC Chairman/Secretary</b></td>
                <td>'.$sigChairmanSecretary.'</td>
                <td>'.($sigChairmanSecretary ? 'Signed Online' : '').'</td>

                <td>
                    <b>Vetted By</b><br/>
                    <span style="font-size:10px;">
                    (Financial Approval / Budget Coordinator)
                    </span>
                </td>
                <td>'.$sigVetter.'</td>
                <td>'.($sigVetter ? 'Signed Online' : '').'</td>
            </tr>

            <tr class="sig">
                <td><b>Budget Coordinator</b></td>
                <td></td>
                <td></td>

                <td><b>Payment Authority Approval</b></td>
                <td></td>
                <td></td>
            </tr>

            <tr class="sig">
                <td><b>Stores Officer</b></td>
                <td></td>
                <td></td>

                
            </tr>

        </table>
    </td>
</tr>

</table>

<div class="notes">
    <div># Non-availability certificate should be obtained from IGCAR Stores before bidding</div>
    <div>* GEM non-availability certificate (duly approved) for custom-bid to be provided</div>
</div>';

        return $css . $body;
    }

    public static function getHtml($data = [])
    {
        return (new self($data))->get_html();
    }
}
