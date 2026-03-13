<?php

namespace Site\View;

class CommiteeLpcPdf
{
    private $data = [];
    private $items = []; // sub-items

    public function __construct($data, $items = [])
    {
        $this->data = is_object($data) ? (array)$data : $data;
        $this->items = $items; // array of sub-items
    }

    private function get($key)
    {
        return isset($this->data[$key]) && $this->data[$key] !== null
            ? htmlspecialchars($this->data[$key])
            : '';
    }
   private function getIndex($row, $key)
   {
    $row = is_object($row) ? (array)$row : $row;
    return isset($row[$key]) && $row[$key] !== null
        ? htmlspecialchars($row[$key])
        : '';
   }

    private function renderItems()
   {
    if (empty($this->items) || !is_array($this->items)) {
        return '<tr class="sign-box"><td colspan="6"></td></tr>';
    }

    $html = '';
    foreach ($this->items as $i => $item) {
        $html .= '<tr class="center">
            <td>'.($i + 1).'</td>
            <td>'.$this->getIndex($item, 'item_description').'</td>
            <td>'.$this->getIndex($item, 'item_quantity').'</td>
            <td>'.$this->getIndex($item, 'item_unit').'</td>
            <td>'.$this->getIndex($item, 'item_estimated_unit_cost').'</td>
            <td>'.$this->getIndex($item, 'item_total_estimated_cost').'</td>
        </tr>';
    }

    return $html;
   }


    public function get_html()
    {
        return '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Statement of Case / Indent Form</title>
<style>
    body{font-family: "Times New Roman", serif;background:#fff;}
    .container{width:900px;margin:auto;border:2px solid #000;}
    table{width:100%;border-collapse:collapse;}
    td,th{border:1px solid #000;padding:6px;font-size:14px;vertical-align:top;}
    .no-border td{border:none;}
    .center{text-align:center;}
    .right{text-align:right;}
    .bold{font-weight:bold;}
    .small{font-size:12px;}
    .sign-box{height:70px;}
    .mc-title{font-size:26px;font-weight:bold;text-align:center;}
</style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <table>
        <tr>
            <td style="width:25%"><div class="mc-title">MC&amp;MFCG</div></td>
            <td style="width:75%" class="center bold">
                Indira Gandhi Centre for Atomic Research<br>
                Material Chemistry &amp; Metal Fuel Cycle Group<br>
                Local Purchase Committee (LPC)
            </td>
        </tr>
        <tr>
            <td colspan="2" class="center bold">
                Statement of Case (SOC) / INDENT FORM
                <span style="float:right;">Annexure - I</span>
            </td>
        </tr>
    </table>

    <!-- INDENT INFO -->
    <table>
        <tr><td class="bold">Indent No:</td><td colspan="3">'.$this->get('indent_no').'</td></tr>
  <tr>
    <td class="bold">Date:</td>
    <td colspan="3">
        '.(!empty($this->get('created_time')) 
            ? date('d/m/Y', strtotime($this->data['created_time'])) 
            : '').'
    </td>
</tr>

    </table>

    <!-- OFFICER DETAILS -->
    <table>
        <tr><td colspan="4" class="center bold">Indenting Officer Details</td></tr>
        <tr>
            <td class="bold">Name &amp; IC No</td><td>'.$this->get('created_by').' / '.$this->get('created_by_intercome').'</td>
            <td class="bold">Designation</td><td>'.$this->get('created_by_designation').'</td>
        </tr>
      <td class="bold">Section</td>
<td>
'.(
    $this->get('hos_org_desc')
    ? $this->get('hos_name').' / '.$this->get('hos_org_desc')
    : $this->get('hos_name')
).'
</td>

<td class="bold">Division / Sub-Group</td>
<td>
'.(
    $this->get('hod_org_desc')
    ? $this->get('hod_name').' / '.$this->get('hod_org_desc')
    : $this->get('hod_name')
).'
</td>
        <tr>
            <td class="bold">Email</td><td>'.$this->get('created_by_email').'</td>
            <td class="bold">Phone No</td><td>'.$this->get('created_by_mobile_no').'</td>
        </tr>
    </table>

    <!-- ITEM DETAILS -->
    <table>
        <tr><td colspan="6" class="center bold">Item Details</td></tr>
        <tr class="center bold">
            <td>Sl.No</td>
            <td>Description of Item<br><span class="small">(Separate sheet to be attached for detailed specifications)</span></td>
            <td>Quantity</td><td>Unit</td><td>Estimated Unit Cost</td><td>Total Estimated Cost (Rs)</td>
        </tr>
        '.$this->renderItems().'
    </table>

    <!-- END USE & JUSTIFICATION -->
    <table>
        <tr><td class="bold">End Use of Item:</td><td>'.$this->get('end_use_item').'</td></tr>
        <tr>
            <td colspan="2" class="bold">
                Justification for the purchase:
                <span class="small">'.$this->get('justification_purchase').'</span>
            </td>
        </tr>
    </table>

    <!-- COST & CHECKLIST -->
    <table>
        <tr><td class="bold">Total Estimated Cost including taxes (Rs):</td><td>'.$this->get('total_estimated_cost').'</td></tr>
        <tr><td>Item category</td><td>'.$this->get('item_category').'</td></tr>
        <tr><td>Non-availability from Stores Unit attached : Yes / No</td><td>'.$this->get('stores_unit').'</td></tr>
        <tr><td>GeM non-availability attached : Yes / No</td><td>'.$this->get('gem_non_availablity').'</td></tr>
        <tr><td>Estimated Quantity and cost of proposal</td><td>'.$this->get('estimated_quantity_cost').'</td></tr>
        <tr><td>Whether similar items purchased in last six months</td><td>'.$this->get('items_purchased').'</td></tr>
        <tr><td>Availability of funds</td><td>'.$this->get('availability_funds').'</td></tr>
        <tr><td class="bold">Head of the Account:</td><td>'.$this->get('head_of_account').'</td></tr>
    </table>

    <!-- SIGNATURES -->
    <table>
        <tr><td colspan="5" class="center bold">Signatures</td></tr>

        <tr><td class="bold" colspan="5">Indenting Officer</td></tr>
        <tr>
            <td class="bold">Approving authority</td>
            <td class="bold center">Section Head</td>
             <td>'.(
    $this->get('hos_org_desc')
    ? $this->get('hos_name').' / '.$this->get('hos_org_desc')
    : $this->get('hos_name')
).'</td>

            <td class="bold center" style="border-left:2px solid #000;">Division Head</td>
             <td>'.(
    $this->get('hod_org_desc')
    ? $this->get('hod_name').' / '.$this->get('hod_org_desc')
    : $this->get('hod_name')
).'</td>
        </tr>
        <tr>
            <td class="bold">Financial Approval<br>(Budget coordinator)</td>
            <td>'.(
    $this->get('lpc_approver_org_desc')
    ? $this->get('lpc_approver_name').' / '.$this->get('lpc_approver_org_desc')
    : $this->get('lpc_approver_name')
).'</td>
            <td colspan="2" class="bold">Fund available Yes / No</td>
            <td>'.$this->get('fund_available').'</td >

        </tr>
        <tr>
            <td colspan="3" class="bold">Final Approving authority<br>(Director MC&amp;MFCG)</td>
            <td colspan="2">'.(
    $this->get('lpc_chairman_org_desc')
    ? $this->get('lpc_chairman_name').' / '.$this->get('lpc_chairman_org_desc')
    : $this->get('lpc_chairman_name')
).'</td>
        </tr>
    </table>

</div>

</body>
</html>
';
    }

    public static function getHtml($data, $items = [])
    {
        return (new self($data, $items))->get_html();
    }
}
