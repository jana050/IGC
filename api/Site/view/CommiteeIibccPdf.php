<?php

namespace Site\View;

class CommiteeIibccPdf
{
    private $data = [];

    public function __construct($data)
    {
        $this->data = is_object($data) ? (array)$data : $data;
    }

    private function get($key)
    {
        return isset($this->data[$key]) && $this->data[$key] !== null
            ? htmlspecialchars($this->data[$key])
            : '';
    }

    public function get_html()
    {
        return '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IIBCC Indent Form</title>


<style> 
    body { font-family: "Times New Roman", serif; margin: 20px; font-size: 13px; } 
    .container { width: 100%; border: 1px solid #000; } 
    table { width: 100%; border-collapse: collapse; font-size: 13px; } 
    td, th { border: 1px solid #000; padding: 4px; vertical-align: top; } 
    .signature-box { height: 40px; } 
    .center { text-align: center; } 
    .bold { font-weight: bold; } 
</style>

</head>

<body>
<div class="container">

<table>

<tr>
    <td class="bold center" style="width:15%">IIBCC</td>
    <td colspan="3" class="center bold">
        Indira Gandhi Centre for Atomic Research<br>
        Material Chemistry & Metal Fuel cycle Group
    </td>
</tr>

<tr>
    <td class="bold">Division</td>
    <td class="bold center">CFED</td>
    <td colspan="2" class="bold center">INDENT FORM</td>
</tr>

<tr>
    <td class="bold">Indent No.</td>
    <td colspan="3">'.$this->get('indent_no').'</td>
</tr>

<tr>
    <td class="bold">IIBCC No.</td>
    <td>'.$this->get('iibcc_no').'</td>
    <td class="bold">Date</td>
  <td>
        '.(!empty($this->get('created_time')) 
            ? date('d/m/Y', strtotime($this->data['created_time'])) 
            : '').'
    </td>
</tr>

<tr>
    <td colspan="4" class="center bold">Indenting Officer Details</td>
</tr>

<tr>
    <td class="bold">Name</td>
    <td>'.$this->get('created_by').' / '.$this->get('created_by_intercome').'</td>
    <td class="bold">Designation</td>
    <td>'.$this->get('created_by_designation').'</td>
</tr>

<tr>
    <td class="bold">Section</td>
    <td>
        '.(
            $this->get('hos_org_desc')
            ? $this->get('hos_org_desc')
            : ''
        ).'
    </td>

    <td class="bold">Division</td>
    <td>
         '.(
            $this->get('hod_org_desc')
            ? $this->get('hod_org_desc')
            : ''
        ).'
    </td>
</tr>

<tr>
    <td class="bold">Email</td>
    <td>'.$this->get('created_by_email').'</td>
    <td class="bold">Lab Phone No.</td>
    <td>'.$this->get('created_by_mobile_no').'</td>
</tr>

<tr>
    <td colspan="4" class="center bold">Item Details</td>
</tr>

<tr>
    <td class="bold">Item Brief Description</td>
    <td colspan="3">'.$this->get('name_of_item').'</td>
</tr>

<tr>
    <td class="bold">Estimate Source</td>
    <td class="center">'.$this->get('estimate_source').'</td>
    <td class="bold">Cost (INR)</td>
    <td class="center">'.$this->get('amount').'</td>
</tr>

<tr>
    <td class="bold">Expenditure Head of Account</td>
    <td class="center">'.$this->get('head_of_account').'</td>
    <td class="bold">Nature of Item</td>
    <td class="center">'.$this->get('nature_of_item').'</td>
</tr>

<tr>
    <td class="bold">Whether the Item Belongs to</td>
    <td colspan="3" class="center">'.$this->get('item_belongs_to').'</td>
</tr>

<tr>
    <td class="bold">Item Source</td>
    <td class="center">'.$this->get('item_source').'</td>
    <td class="bold">PDI Required</td>
    <td class="center">'.$this->get('pdi_required').'</td>
</tr>

<tr>
    <td class="bold">Item to be purchased through</td>
    <td class="center">'.$this->get('item_to_purchased').'</td>
    <td class="bold">Delivery Date</td>
    <td class="center">'.(!empty($this->get('delivery_date')) ? date('d/m/Y', strtotime($this->get('delivery_date'))) : '').'</td>
</tr>

<tr>
    <td colspan="4" class="center bold">
        Justification of the purchase, QUANTITY and END USE
    </td>
</tr>

<tr>
    <td colspan="3" class="bold">Detailed Specifications</td>
    <td class="bold center">Quantity</td>
</tr>

<tr>
    <td colspan="3">'.$this->get('description').'</td>
    <td class="center">'.$this->get('item_quantity').'</td>
</tr>

<tr>
    <td colspan="4" class="bold">Note:</td>
</tr>

<tr>
    <td colspan="3">
        Detailed Specifications Indent (Part-I & Part-II),
        Budgetary Quote, Authorization certificate (Proprietary Items)
        should be enclosed in the file (ANNEXURE)
    </td>
    <td class="center bold">YES</td>
</tr>

<tr>
    <td colspan="4" class="center bold">Probable Supplier</td>
</tr>

<tr class="center bold">
    <td style="width:25%">M/s, Supplier 1 Address</td>
    <td style="width:25%">M/s, Supplier 2 Address</td>
    <td style="width:25%">M/s, Supplier 3 Address</td>
    <td style="width:25%"></td>
</tr>

<tr class="center">
    <td>'.$this->get('supplier_1').'</td>
    <td>'.$this->get('supplier_2').'</td>
    <td>'.$this->get('supplier_3').'</td>
    <td></td>
</tr>

<tr>
    <td class="bold">Technical Sanction Amount</td>
    <td colspan="3" class="center">'.$this->get('technical_sanction_amount').'</td>
</tr>

<tr class="center bold">
    <td>GeM ID Available</td>
    <td>GeM Number</td>
    <td>GeM Approvals</td>
    <td>Store Certificate</td>
</tr>

<tr class="center">
    <td>'.$this->get('gem_id_flag').'</td>
    <td>'.$this->get('gem_number').'</td>
    <td>'.$this->get('gem_approvals').'</td>
    <td>'.$this->get('store_certificate').'</td>
</tr>

<tr>
    <td colspan="4" class="center bold">Signatures</td>
</tr>

<tr class="center bold">
    <td>Indentor</td>
    <td>Forwarded</td>
    <td colspan="2">Authorized</td>
</tr>
<tr>
    <td class="signature-box"></td>
    <td class="signature-box"></td>
    <td colspan="2" class="signature-box"></td>
</tr>
<tr>
    <td class="center">'.$this->get('created_by').'</td>
    <td class="center">
      '.(
            $this->get('hos_org_desc') 
            ? $this->get('hos_org_desc').' / Signed'
            : 'Signed'
        ).'
    </td>
    <td colspan="2" class="center">
         '.(
            $this->get('hod_org_desc') 
            ? $this->get('hod_org_desc').' / Signed'
            : 'Signed'
        ).'
    </td>
</tr>
<tr>
    <td colspan="4" class="center bold">Approval</td>
</tr>

<tr class="center bold">
    <td colspan="2">Vetted by (Secretary, IIBCC)</td>
    <td colspan="2">Approved by (Chairman, IIBCC)</td>
</tr>

<tr>
    <td colspan="2" class="center">
        '.(
            $this->get('iibcc_approver_org_desc') 
            ? $this->get('iibcc_approver_org_desc').' / Signed'
            : 'Signed'
        ).'
    </td>
    <td colspan="2" class="center">
         '.(
            $this->get('iibcc_chairman_org_desc') 
            ? $this->get('iibcc_chairman_org_desc').' / Signed'
            : 'Signed'
        ).'
    </td>
</tr>
</table>

</div>
</body>
</html>';
    }

    public static function getHtml($data)
    {
        return (new self($data))->get_html();
    }
}
