<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Site\view;
use Core\Helpers\SmartGeneral;



class MbookIssuePdf
{

  private $data = [];

  function __construct($data)
  {
    $this->data = is_object($data) ? (array) $data : $data;
    // var_dump($this->data);

  }
  //
  private function get($index)
  {
    return isset($this->data[$index]) ? str_replace("&", " ", $this->data[$index]) : "";
  }

  private function getIndex($dta, $index)
  {
    $dt = (array)$dta;
    return isset($dt[$index]) ? $dt[$index] : "";
  }

  private function pget($index)
  {
    $dummy = $this->get($index);
    echo $dummy;
  }

public function get_html()
{
    
      $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Measurement Book</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 40px;
            }
            .top-line {
                display: flex;
                justify-content: space-between;
                font-weight: bold;
            }
            .main-box {
                border: 2px dotted black;
                padding: 20px;
                margin-top: 10px;
            }
            .center-text {
                text-align: center;
                font-weight: bold;
            }
            .details {
                margin-top: 20px;
                font-size: 16px;
            }
            .details p {
                margin: 10px 0;
            }
            .signature-section {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
            }
        </style>
    </head>
    <body>

        <div class="top-line">
            <div>IGCAR/' . $this->get("mbook_number") . '</div>
            <div>CC No:' . $this->get("cc_number") . '</div>
        </div>

        <div class="main-box">
            <div class="center-text">
                Government of India<br>
                DEPARTMENT OF ATOMIC ENERGY<br>
                INDIRA GANDHI CENTRE FOR ATOMIC RESEARCH<br>
                MATERIAL CHEMISTRY & METAL FUEL CYCLE GROUP<br>
                Kalpakkam.
            </div>

            <div class="details">
                <p><strong>MEASUREMENT BOOK No.:</strong>' . $this->get("mbook_number") . '</p>
                <p><strong>Issued to (Name):</strong> ' . $this->get("title") . '</p>
                <p><strong>Designation:</strong> ' . $this->get("designation") . '</p>
                <p>Certified that this measurement book contains from Page no. <strong>01</strong> to <strong>100</strong> Pages serially numbered.</p>

                <div class="signature-section">
                    <div>Date: ' . $this->get("date_of_issue") . '</div>
                    <div>Signature of the Issuing Authority<br>Head, CFED, MC&MFCG.</div>
                </div>
            </div>
        </div>

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
