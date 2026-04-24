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
        // Guard against non-scalar values slipping in via JOINs / helpers
        // (e.g. status_list arrays) — on PHP 8+ htmlspecialchars() throws a
        // TypeError when given an array, which breaks the generated HTML
        // and can confuse mPDF into producing garbage / many blank pages.
        if (is_array($v) || is_object($v)) {
            return $default;
        }
        $s = (string)$v;
        if ($s === '') return $default;
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function get_html()
    {
        // Signature block for a given approver — shows the name when the
        // stage is completed, otherwise a blank cell.
        $created_by = $this->get('created_by', '-');
        $intercome = $this->get('created_by_intercome');
        $indenterName = $intercome ? $created_by . ' / ' . $intercome : $created_by;

        $sigHos = $this->get('hos_name');
        if ($sigHos) { $sigHos .= ' / Signed'; }
        else { $sigHos = $this->get('hos_org_desc') ? $this->get('hos_org_desc') . ' / Signed' : ''; }

        $sigChairman = $this->get('iibcc_chairman_name');
        if ($sigChairman) { $sigChairman .= ' / Signed'; }
        else if ($this->get('iibcc_no')) { $sigChairman = 'Signed'; }

        $sigVetter = $this->get('vetter_user_name');
        if ($sigVetter) { $sigVetter .= ' / Signed'; }

        // mPDF happily accepts a bare HTML fragment with a leading <style>.
        // We deliberately omit <!DOCTYPE>, <html>, <head>, <body> and
        // `@page` — the wrapping boilerplate has caused intermittent cases
        // where mPDF rendered the document as empty / heavily paginated.
        $css = '
<style>
body { font-family: "Times New Roman", serif; font-size: 13px; color: #000; }
.form-title { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 4px; }
.form-sub { text-align: center; font-size: 14px; margin-bottom: 10px; }
.form-table { width: 100%; border-collapse: collapse; }
.form-table td, .form-table th { border: 1px solid #000; padding: 7px 8px; vertical-align: top; font-size: 13px; word-wrap: break-word; }
.form-table .lbl { font-weight: bold; background: #f7f7f7; width: 22%; }
.form-table .sec { font-weight: bold; text-align: center; background: #e6e6e6; font-size: 14px; padding: 7px; }
.form-table .c   { text-align: center; }
.form-table .tall td { height: 60px; }
.form-table .sig td { height: 48px; text-align: center; vertical-align: middle; font-weight: bold; }
</style>';

        $body = '
<div class="form-title">INDENT FORM</div>
<div class="form-sub">
    Indira Gandhi Centre for Atomic Research<br/>
    Materials Chemistry &amp; Metal Fuel Cycle Group (MC &amp; MFCG)
</div>

<table class="form-table">

<tr>
    <td class="lbl">Indent No</td>
    <td>'.$this->get('indent_no', '-').'</td>
    <td class="lbl">IIBCC No</td>
    <td>'.$this->get('iibcc_no', '-').'</td>
</tr>

<tr><td colspan="4" class="sec">Indenter Details</td></tr>

<tr>
    <td class="lbl">Name</td>
    <td>'.$indenterName.'</td>
    <td class="lbl">Designation</td>
    <td>'.$this->get('created_by_designation', '-').'</td>
</tr>

<tr>
    <td class="lbl">Section</td>
    <td>'.$this->get('hos_org_desc', '-').'</td>
    <td class="lbl">Division</td>
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
    <td class="lbl">GEM ID (if item is on GeM)</td>
    <td>'.$this->get('gem_id_item', '-').'</td>
    <td class="lbl">Total Cost (INR)</td>
    <td>'.$this->get('total_cost', '-').'</td>
</tr>

<tr><td colspan="4" class="sec">Justification for the Purchase, Quantity and End Use</td></tr>

<tr class="tall">
    <td colspan="4">'.$this->get('justification_purchase', '-').'</td>
</tr>

<tr>
    <td class="sec">Detailed Specifications</td>
    <td class="sec c">Quantity</td>
    <td colspan="2" class="sec c">Unit</td>
</tr>

<tr class="tall">
    <td>'.$this->get('detailed_specification', '-').'</td>
    <td class="c">'.$this->get('quantity', '-').'</td>
    <td colspan="2" class="c">'.$this->get('unit', '-').'</td>
</tr>

<tr><td colspan="4" class="sec">Signatures</td></tr>

<tr class="sig">
    <td>Indentor</td>
    <td>'.$created_by.'</td>
    <td>Approving Authority (HOS)</td>
    <td>'.($sigHos ?: '').'</td>
</tr>

<tr class="sig">
    <td>IIBCC Chairman</td>
    <td>'.($sigChairman ?: '').'</td>
    <td>Vetter</td>
    <td>'.($sigVetter ?: '').'</td>
</tr>

</table>';

        return $css . $body;
    }

    public static function getHtml($data = [])
    {
        return (new self($data))->get_html();
    }
}