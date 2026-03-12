<?php

namespace Site\view;

use Core\Helpers\SmartGeneral;

class RadiologicalPdf
{
    private $data = [];

    public function __construct($data)
    {
        $this->data = is_object($data) ? (array) $data : $data;
    }

    private function get($index)
    {
        return isset($this->data[$index]) ? str_replace("&", " ", $this->data[$index]) : "";
    }

    public function get_html()
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Radiological Work Permit</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    margin: 30px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                    margin-bottom: 20px;
                }
                td, th {
                    border: 1px solid #000;
                    padding: 6px;
                    text-align: center;
                }
                .no-border {
                    border: none;
                }
                .section-title {
                    font-weight: bold;
                    margin-top: 20px;
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>

            <h3 style="text-align:center;">
                INDIRA GANDHI CENTRE FOR ATOMIC RESEARCH<br>
                MATERIALS CHEMISTRY and METAL FUEL CYCLE GROUP<br>
                RADIOLOGICAL WORK PERMIT<br>
                <small>(to be submitted in duplicate)</small>
            </h3>

            <p>Permit issued to: ' . $this->get("permit_no") . ' <span style="float:right;">RWP No. ' . $this->get("rwp_no") . '</span></p>
            <p><strong>Job location & Description:</strong> ' . $this->get("job_description") . '</p>

            <p class="section-title">Details of Persons covered under this RWP:</p>
            <table>
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name of personnel</th>
                        <th>TLD No</th>
                        <th>DRD No</th>
                        <th>Planned Exposure (mSv)</th>
                        <th>Dose as per DRD</th>
                    </tr>
                </thead>
                <tbody>';

       $personnel = isset($this->data['personnel']) && is_array($this->data['personnel']) ? $this->data['personnel'] : [];

foreach ($personnel as $i => $p) {
    $html .= '<tr>
        <td>' . ($i + 1) . '</td>
        <td>' . $this->getIndex($p, "name_of_personnel") . '</td>
        <td>' . $this->getIndex($p, "tld_no") . '</td>
        <td>' . $this->getIndex($p, "drd_no") . '</td>
        <td>' . $this->getIndex($p, "planned_exposure") . '</td>
        <td>' . $this->getIndex($p, "dose_as_per") . '</td>
    </tr>';
}


        $html .= '
                </tbody>
            </table>

            <p>(Additional Names on reverse side)</p>
            <p>Signature of permit holder: ' . $this->get("signature") . '</p>
            <p>Laboratory/area in charge: ' . $this->get("lab_incharge_id") . '</p>
            <p>Date & Time: ' . $this->get("created_time") . '</p>


            <p class="section-title">REQUIRED PROTECTION</p>
            <table>
                <tr>
                    <td>Cover all: ' . $this->get("cover_all") . '</td>
                    <td>Rubber glove: ' . $this->get("rubber_glove") . '</td>
                    <td>Dosimeter: ' . $this->get("dosimeter") . '</td>
                </tr>
                <tr>
                    <td>Complete dress: ' . $this->get("complete_dress") . '</td>
                    <td>Surgical gloves: ' . $this->get("surgical_gloves") . '</td>
                    <td>Respirator: ' . $this->get("respirator") . '</td>
                </tr>
                <tr>
                    <td>Change: ' . $this->get("change_of_clothes") . '</td>
                    <td>Over shoes: ' . $this->get("over_shoes") . '</td>
                    <td>Dust/Gas Mask: ' . $this->get("dust_gas_mask") . '</td>
                </tr>
                <tr>
                    <td>Plastic suit: ' . $this->get("plastic_suit") . '</td>
                    <td colspan="2">Airline/Breathing Apparatus</td>
                </tr>
            </table>

            <p>
                Presence of health Physicist: ' . $this->get("health_physicist_present") . '<br>
                (If yes, work should start only after HP arrival, HP to clear permit on spot)
            </p>

            <p>Signature, <strong>HP / RSO, MC&MFCG</strong></p>

        </body>
        </html>';

        return $html;
    }

    private function getIndex($dta, $index)
    {
        $dt = (array) $dta;
        return isset($dt[$index]) ? $dt[$index] : '';
    }

    public static function getHtml($data)
    {
        $obj = new self($data);
        return $obj->get_html();
    }
}
