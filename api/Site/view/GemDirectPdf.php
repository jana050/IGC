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
        $s = trim((string)$v);
        // Treat empty string AND the literal text "null"/"undefined" as
        // missing — historical rows have these strings stored when the
        // frontend submitted a null value via String(null).
        if ($s === '' || strcasecmp($s, 'null') === 0 || strcasecmp($s, 'undefined') === 0) {
            return $default;
        }
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function get_html()
    {
        // Signature block for a given approver — shows the name when the
        // stage is completed, otherwise a blank cell.
        $created_by = $this->get('created_by', '-');
        // Indenter is shown as "Name / ICNO" — ICNO comes from the user's
        // euserid (employee number). Falls back to just the name when ICNO
        // isn't joined.
        $icno = $this->get('created_by_icno');
        $indenterName = $icno ? $created_by . ' / ' . $icno : $created_by;

        // Signature cells just show the approver's name when the stage is
        // completed (the name is the signature). Falls back to the org
        // descriptor for HOS when no name is joined; chairman / vetter
        // stay blank until they actually act.
        $sigHos = $this->get('hos_name');
        if (!$sigHos) { $sigHos = $this->get('hos_org_desc'); }

        $sigChairman = $this->get('iibcc_chairman_name');

        $sigVetter = $this->get('vetter_user_name');

        // mPDF happily accepts a bare HTML fragment with a leading <style>.
        // We deliberately omit <!DOCTYPE>, <html>, <head>, <body> and
        // `@page` — the wrapping boilerplate has caused intermittent cases
        // where mPDF rendered the document as empty / heavily paginated.
        $css = '
<style>
body { font-family: "Times New Roman", serif; font-size: 13px; color: #000; }
.form-table { width: 100%; border-collapse: collapse; }
.form-table td, .form-table th { border: 1px solid #000; padding: 7px 8px; vertical-align: top; font-size: 13px; word-wrap: break-word; }
.form-table .lbl { font-weight: bold; width: 22%; }
.form-table .sec { font-weight: bold; text-align: center; background: #e6e6e6; font-size: 14px; padding: 7px; }
.form-table .c   { text-align: center; }
.form-table .tall td { height: 60px; }
.form-table .sig td { height: 55px; text-align: center; vertical-align: middle; font-weight: bold; }
.form-table .head-row td { vertical-align: middle; }
.form-table .head-row .org    { text-align: center; font-weight: bold; font-size: 13px; line-height: 1.4; }
.form-table .head-row .badge  { text-align: center; font-weight: bold; font-size: 14px; }
.form-table .head-row .title  { text-align: center; font-weight: bold; font-size: 18px; }
.notes { font-size: 11px; margin-top: 8px; line-height: 1.5; }
.notes div { padding: 1px 0; }
</style>';

        $body = '
<table class="form-table">

<tr class="head-row">
    <td class="badge" style="width:18%">MC &amp; MFCG</td>
    <td class="org" colspan="2" style="width:55%">
        Indira Gandhi Centre for Atomic Research<br/>
        Materials Chemistry &amp; Metal Fuel Cycle Group<br/>
        <span style="font-weight:normal;font-size:12px;">(GEM Direct Indent Form)</span>
    </td>
    <td class="title" style="width:27%">INDENT FORM</td>
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
    <td>'.$this->get('hos_org_desc', '-').'</td>
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
    <td colspan="2" class="sec" style="width:70%">Detailed Specifications</td>
    <td class="sec c" style="width:15%">Quantity</td>
    <td class="sec c" style="width:15%">Unit</td>
</tr>

<tr class="tall">
    <td colspan="2">'.$this->get('detailed_specification', '').'</td>
    <td class="c">'.$this->get('quantity', '-').'</td>
    <td class="c">'.$this->get('unit', '-').'</td>
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
    <td>'.($sigChairman ? $sigChairman . '<br/><span style="font-weight:normal;font-size:11px;">Approved Online</span>' : '').'</td>
    <td>Budget Coordinator</td>
    <td></td>
</tr>

<tr class="sig">
    <td colspan="2">Payment Authority Approval</td>
    <td colspan="2"></td>
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