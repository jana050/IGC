<?php

namespace Site\view;

class RfidCardPdf
{
    private $data = [];

    public function __construct($data)
    {
        $this->data = is_object($data) ? (array) $data : $data;
    }

    private function get($index)
    {
        return isset($this->data[$index]) ? htmlspecialchars($this->data[$index]) : "";
    }

    public function get_html()
    {
        $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Application for RFID Card</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                h2, h3 {
                    text-align: center;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                td, th {
                    border: 1px solid #000;
                    padding: 8px;
                    vertical-align: top;
                }
                .section-title {
                    background: #f0f0f0;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>

        <h2>Indira Gandhi Centre for Atomic Research</h2>
        <h3>MC&MCFG<br>Application for RFID Card</h3>

        <table>
            <tr class="section-title">
                <td colspan="4">Duration and Nature of Visitor</td>
            </tr>
            <tr>
                <td><strong>From</strong></td>
                <td>' . $this->get("from_date") . '</td>
                <td><strong>To</strong></td>
                <td>' . $this->get("to_date") . '</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Nature of Visitor:</strong> ' . $this->get("nature_of_visitor") . '</td>
            </tr>
            <tr>
                <td><strong>Date of Application</strong></td>
                <td colspan="3">' . $this->get("created_time") . '</td>
            </tr>
        </table>

        <table>
            <tr class="section-title">
                <td colspan="4">Details of Visitor</td>
            </tr>
            <tr>
                <td><strong>IGCAR Entry Permit No. & Date of Validity</strong></td>
                <td colspan="3">' . $this->get("igcar_entry_permit_no") . ' / ' . $this->get("igcar_entry_date_of_validity") . '</td>
            </tr>
            <tr>
                <td><strong>Name</strong></td>
                <td>' . $this->get("name") . '</td>
                <td><strong>Age</strong></td>
                <td>' . $this->get("age") . '</td>
            </tr>
            <tr>
                <td><strong>Gender (male/female)</strong></td>
                <td>' . $this->get("gender") . '</td>
                <td><strong>Institute Address</strong></td>
                <td>' . $this->get("institute_address") . '</td>
            </tr>
            <tr>
                <td><strong>Area of Visit / Work (LAB No.)</strong></td>
                <td>' . $this->get("area_of_visit") . '</td>
                <td><strong>Mobile No.</strong></td>
                <td>' . $this->get("mobile_no") . '</td>
            </tr>
            <tr>
                <td><strong>Contact Number (Intercom)</strong></td>
                <td>' . $this->get("intercom_no") . '</td>
                <td><strong>Signature of Visitor</strong></td>
                <td>' . $this->get("visitor_signature") . '</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Received RFID Card STA ........</strong></td>
                <td colspan="2"><strong>Signature:</strong></td>
            </tr>
        </table>

        <table>
            <tr class="section-title">
                <td colspan="4">Forwarded by</td>
            </tr>
            <tr>
                <td><strong>Name</strong></td>
                <td>' . $this->get("hos_name") . '</td>
                <td><strong>Signature</strong></td>
                <td>' . $this->get("forwarded_signature") . '</td>
            </tr>
            <tr>
                <td><strong>Designation</strong></td>
                <td>' . $this->get("forwarded_designation") . '</td>
                <td><strong>Section / Division</strong></td>
                <td>' . $this->get("forwarded_section") . '</td>
            </tr>
        </table>

        <table>
            <tr class="section-title">
                <td colspan="4">Authorised by</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Associate Director / Head of Division</strong></td>
                <td><strong>Name</strong></td>
                <td>' . $this->get("hod_name") . '</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><strong>Signature</strong></td>
                <td>' . $this->get("hod_signature") . '</td>
            </tr>
        </table>

        <table>
            <tr class="section-title">
                <td colspan="4">For Office Use</td>
            </tr>
            <tr>
                
                <td colspan="2">To:<strong>Head, ECIS/CFED</strong></td>
                <td colspan="2"><strong>Name</strong><br>Issued by : ' . $this->get("supervisor_name") . '<br>Card No : ' . $this->get("card_no") . '<br>Date : ' . $this->get("card_date") . '</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Deposited back by</strong><br>Name<br>Signature<br>Date</td>
                <td colspan="2"><strong>Received back by</strong><br>Name<br>Signature<br>Date</td>
            </tr>
        </table>

        </body>
        </html>';

        return $html;
    }

    public static function getHtml($data)
    {
        $obj = new self($data);
        return $obj->get_html();
    }
}
