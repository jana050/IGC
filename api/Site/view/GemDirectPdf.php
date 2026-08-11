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
        if (!$sigVetter) { $sigVetter = $this->get('financial_approval_name'); }

        $css = '
<style>
@page { margin: 8mm 10mm 5mm 10mm; }
body { font-family: "Times New Roman", serif; font-size: 14px; color: #000; line-height: 1.3; margin: 0; padding: 0; }
.form-table { width: 100%; border-collapse: collapse; table-layout: fixed; padding:0; margin:0;}
.form-table td, .form-table th { border: 1px solid #000; padding: 5px 7px; vertical-align: top; font-size: 14px; word-wrap: break-word; overflow-wrap: break-word; }
.form-table .lbl { font-weight: bold;, font-size:14px }
.form-table  .sec { font-weight: bold; text-align: center; background: #e6e6e6; font-size: 14px; padding: 6px; }
.sing-table .sec { font-weight: bold; text-align: center; background: #e6e6e6; font-size: 14px; padding: 6px; }
.sing-table .c   { text-align: center; }
.form-table .tall td { height: 30mm; vertical-align: top; }
.sing-table .sig td { height: 20mm; text-align: center; vertical-align: middle; font-weight: bold; font-size: 13px; }
.sing-table .head-row td { vertical-align: middle; font-size 14px; }
.form-table .head-row .org    { text-align: center; font-weight: bold; font-size: 14px; line-height: 1.4; }
.form-table .head-row .badge  { text-align: center; font-weight: bold; font-size: 16x; }
.form-table .head-row .title  { text-align: center; font-weight: bold; font-size: 14px; }
.notes { font-size: 11px; margin-top: 4px; line-height: 1.5; }
.notes div { padding: 1px 0; }
 .sing-table { width: 100%; border-collapse: collapse; table-layout: fixed; padding-right:20px }
//.sing-table td, .form-table th { border: 1px solid #000; padding: 5px 7px; vertical-align: top; font-size: 14px; word-wrap: break-word; overflow-wrap: break-word; }
</style>';

        $body = '
<table   class="form-table" style="width:100%;border-collapse:collapse;" border="1">
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
        <span style="font-weight:normal;font-size:14px;">(GEM Direct Indent Form)</span>
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
    <td class="lbl">Intercom No</td>
    <td>'.$this->get('created_by_intercome', '-').'</td>
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

<tr><td colspan="4"  class="sec">Justification for the Purchase, Quantity and End Use</td></tr>

<tr class="tall">
    <td colspan="4" >'.$this->get('justification_purchase', '').'</td>
</tr>

<tr> 
    <td colspan="2"   class="sec">Detailed Specifications</td>
    <td style="font-size:14px"  class="sec c">Quantity</td>
    <td style="font-size:14px"  class="sec c">Unit</td>
</tr>

<tr style="font-size:14px"  class="tall">
    <td style="font-size:14px"  colspan="2">'.$this->get('detailed_specification', '').'</td>
    <td style="font-size:14px"  class="c">'.$this->get('quantity', '-').'</td>
    <td style="font-size:14px"  class="c">'.$this->get('unit', '-').'</td>
</tr>
</table>
     <div >

        <table class="sing-table" style="width:100%;border-collapse:collapse;" border="1" >

            <tr>
                 <td class="sec" style="font-size:14px"  width="20%"><b>Role</b></td>
            

                <td  class="sec" width="20%" style="font: size: 14px "><b>Name</b></td>
                
               <td class="sec"  width="15%"  style="font-size:14px"> <b>Signature </b></td>
                     <td  class="sec" style="font-size:14px"  width="20%"><b>Role</b></td>
            
 
                <td  class="sec"  width="20%" style="font: size: 14px "><b>Name</b></td>
                
                <td class="sec"  width="15%"  style="font-size:14px"> <b>Signature </b></td>
            </tr>

            <tr class="sig">
                <td style="font-size:14px"  width="20%"><b>Indentor</b></td>
                <td style="font-size:14px"  width="auto">'.$created_by.'</td>
                <td width="15%">'.($created_by ? '' : '').'</td>

                <td width="20%" style="font: size: 14px "><b>Approving </b><br/><b>Authority (HOS)</b></td>
                <td width="15%"  style="font-size:14px">'.$sigHos.'</td>
                <td width="15%"  style="font-size:23px"></td>
                
            </tr>

            <tr class="sig">
                <td style="font-size:14px"><b>IIBCC Chairman/Secretary</b></td>
                <td style="font-size:14px">'.$sigChairmanSecretary.'</td>
                <td style="font-size:14px">'.($sigChairmanSecretary ? 'Signed Online' : '').'</td>

                <td style="font-size:14px">
                    <b>Vetted By</b><br/>
                    </span>
                </td>
                <td style="font-size:14px">'.($sigVetter ?: '').'</td>
                <td style="font-size:14px">'.($sigVetter ? 'Signed Online' : 'N/A').'</td>
            </tr>

            <tr class="sig">
                <td style="font-size:14px"><b>Budget Coordinator</b></td>
                <td style="font-size:23px"></td>
                <td style="font-size:23px"></td>

                <td     style="font-size:14px"><b>Payment Authority  </b><br/>  Approval</b></td>
              <td style="font-size:23px"> </td>
                 <td style="font-size:23px"> </td>
             
            </tr>

            <tr class="sig">
                <td style="font-size:14px"><b>Stores Officer</b></td>
                <td style="font-size:23px"> </td>
                <td style="font-size:23px"> </td>
 
                    <td rowspan="2" colspan="3" ></td>
            </tr>

        </table>
        </div> 


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
