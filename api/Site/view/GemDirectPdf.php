<?php

namespace Site\View;

class GemDirectPdf
{
    private $data = [];

    public function __construct($data = [])
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
<html>
<head>
<meta charset="UTF-8">
<title>GEM Direct Indent Form</title>

<style>
body {
    font-family: "Times New Roman", serif;
    font-size: 12px;
}

.container {
    width: 100%;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid #000;
}

td, th {
    padding: 6px;
    vertical-align: top;
}

.center { text-align: center; }
.bold { font-weight: bold; }
.section-title {
    font-weight: bold;
    text-align: center;
    background: #e6e6e6;
}
.signature-box {
    height: 60px;
}
</style>

</head>
<body>

<div class="container">

<table>

<tr>
    <td class="bold center" style="width:20%">MC & MFCG</td>
    <td colspan="3" class="center bold">
        Indira Gandhi Centre for Atomic Research<br>
        Materials Chemistry & Metal Fuel Cycle Group
    </td>
</tr>

<tr>
    <td colspan="4" class="center bold">INDENT FORM</td>
</tr>

<tr>
    <td class="bold">Indent No / GEM ID</td>
    <td colspan="3">'.$this->get('indent_no').'</td>
</tr>
<tr>
    <td colspan="4" class="section-title">Indenter Details</td>
</tr>

<tr>
    <td class="bold">Name</td>
    <td>
    '.$this->get('created_by').'
    '.(
        $this->get('created_by_intercome') 
        ? ' / '.$this->get('created_by_intercome')
        : ''
    ).'
</td>

    <td class="bold">Designation</td>
    <td>'.$this->get('created_by_designation').'</td>
</tr>

<tr>
    <td class="bold">Section</td>
    <td>
        '.(
            $this->get('hos_org_desc') 
            ? $this->get('hos_name').' / '.$this->get('hos_org_desc')
            : $this->get('hos_name')
        ).'
    </td>

    <td class="bold">Division</td>
    <td>
        '.(
            $this->get('hod_org_desc') 
            ? $this->get('hod_name').' / '.$this->get('hod_org_desc')
            : $this->get('hod_name')
        ).'
    </td>
</tr>

<tr>
    <td class="bold">E-mail</td>
    <td>'.$this->get('created_by_email').'</td>

    <td class="bold">Phone No</td>
    <td>'.$this->get('created_by_mobile_no').'</td>

</tr>
<tr>
    <td colspan="4" class="section-title">Item Details</td>
</tr>

<tr>
    <td class="bold">Item - Brief Description</td>
    <td colspan="3">'.$this->get('item_brief_description').'</td>
</tr>

<tr>
    <td class="bold">Head of the Account</td>
    <td colspan="3">'.$this->get('head_of_account').'</td>
</tr>

<tr>
    <td class="bold">Estimate Source</td>
    <td>'.$this->get('estimate_source').'</td>
    <td class="bold">Cost (INR)</td>
    <td>'.$this->get('cost').'</td>
</tr>

<tr>
    <td class="bold">GEM ID (in case item available in GEM)</td>
    <td colspan="3">'.$this->get('gem_id_item').'</td>

</tr>

<tr>
    <td colspan="4" class="section-title">
        Justification for the Purchase, Quantity and End Use
    </td>
</tr>

<tr>
    <td colspan="4">'.$this->get('justification_purchase').'</td>
</tr>

<tr>
    <td class="section-title">Detailed Specifications</td>
    <td class="section-title center">Quantity</td>
    <td colspan="2" class="section-title center">Unit</td>
</tr>

<tr>
    <td>'.$this->get('detailed_specification').'</td>
    <td class="center">'.$this->get('quantity').'</td>
    <td colspan="2" class="center">'.$this->get('unit').'</td>
</tr>

<tr>
    <td colspan="4" class="section-title">Signatures</td>
</tr>

<!-- First Row -->
<tr class="bold center">
    <td>Indentor</td>
    <td>'.$this->get('created_by').'</td>
    <td>Approving Authority (HOS)</td>
    <td>'.(
            $this->get('hos_org_desc') 
            ? $this->get('hos_name').' / '.$this->get('hos_org_desc')
            : $this->get('hos_name')
        ).'</td>
</tr>

<!-- Second Row -->
<tr class="bold center">
    <td colspan="2">Financial Approval (Budget Coordinator)</td>
    <td colspan="2">'.(
            $this->get('financial_approval_org_desc') 
            ? $this->get('financial_approval_name').' / '.$this->get('financial_approval_org_desc')
            : $this->get('financial_approval_name')
        ).'</td>
</tr>

</table>

</div>

</body>
</html>';
    }

    public static function getHtml($data = [])
    {
        return (new self($data))->get_html();
    }
}