<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
$pdf->setPrintFooter(false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Furniture House Manila');
$pdf->SetTitle('FHM Quotation');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("penthouseheader.png", 150);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10,35,10);  
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-60, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}


// $style = array(
//     'position' => '',
//     'align' => 'C',
//     'stretch' => false,
//     'fitwidth' => true,
//     'cellfitalign' => '',
//     'border' => true,
//     'hpadding' => 'auto',
//     'vpadding' => 'auto',
//     'fgcolor' => array(0,0,0),
//     'bgcolor' => false, //array(255,255,255),
//     'text' => true,
//     'font' => 'helvetica',
//     'fontsize' => 8,
//     'stretchtext' => 4
// );




// ---------------------------------------------------------
// set font
$pdf->SetFont('dejavusans', '', 8);

// add a page
$pdf->AddPage();

date_default_timezone_set("Asia/Singapore");
$date =  date("F d, Y");

$html = '
    <style>
        .custom-table > table{
            border: 1px solid darkgray;
            border-collapse: collapse;
            padding: 6px;
        } 

        .custom-table > table > td{
            border: 1px solid darkgray;
            border-collapse: collapse;
            padding: 6px;
        }
        
        .custom-table > table > th{
            border: 1px solid darkgray;
            border-collapse: collapse;
            padding: 6px;
        }      
    </style>

    <h3 style="text-align: center;">Cash Voucher</h3>
    <br>
';


$html .= '
    <div class="custom-table">
        <table>
            <tbody>
                <tr>
                    <td style="width: 20%;">Payee </td>
                    <td style="width: 80%;">'.$expdetails['payee'].'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">Reference #</td>
                    <td style="width: 80%;">'.$expdetails['expense_id'].'</td>
                </tr>
                <tr>
                    <td style="width: 20%;">Date</td>
                    <td style="width: 80%;">'.$edate.'</td>
                </tr>
            </tbody>
        </table>
    </div>
';

$html .= '
    <table>
        <tr>
            <td style="width: 60%;">
                <div class="custom-table">
                    <table>
                        <thead>
                            <tr style="text-align: center;">
                                <th style="width: 80%;">Particular</th>
                                <th style="width: 20%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $row = 0;
                        $totalei = 0;
                        foreach($expitems as $ei){
                            $row++;
                            $totalei += $ei['amount'];
                            $html .= '
                            <tr>
                                <td style="width: 80%;">'.$ei['description'].'</td>
                                <td style="width: 20%; text-align:right;">₱ '.number_format($ei['amount'],2).'</td>
                            </tr>';
                        }
                        $html .='
                            <tr>
                                <td style="width: 80%;"></td>
                                <td style="width: 20%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 80%;"></td>
                                <td style="width: 20%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 80%; text-align:right;">Total</td>
                                <td style="width: 20%; text-align:right;">₱ '.number_format($totalei, 2).'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
            <td style="width: 40%;">
                <div class="custom-table">
                    <table >
                        <thead>
                            <tr style="text-align: center;">
                                <th>Date</th>
                                <th>Details</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $totalep = 0;
                            foreach($exppayment as $ep){
                                $row--;
                                $totalep += $ep['amount'];
                                $pd = date_format(date_create($ep['date']),"M d, Y");
                                $html .='
                                <tr>
                                    <td style="text-align:center;">'.$pd.'</td>
                                    <td style="text-align:center;">['.$ep['bankshortname'].']#'.$ep['check_number'].'</td>
                                    <td style="text-align:right;">₱ '.number_format($ep['amount'],2).'</td>
                                </tr>
                                ';
                            }
                            for ($i=0; $i < $row; $i++) { 
                                $html .= '
                                    <tr>
                                        <td style="text-align:center;"></td>
                                        <td style="text-align:center;"></td>
                                        <td style="text-align:right;"></td>
                                    </tr>';
                            }
                            $html .= '
                            <tr>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:right;"></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:right;"></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:right;">Total</td>
                                <td style="text-align:right;">₱ '.number_format($totalep,2).'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    
';


$html .= '
    <div class="custom-table">
        <table>
            <tr>
                <td>Prepared By:
                    <br>
                    <br>
                    <br>
                    '.$_SESSION['name'].'
                </td>
                <td>Earvin Bryan Co</td>
                <td>Jacqueline Phoebe Zhang</td>
                <td>Received By:</td>
            </tr>
        </table>
    </div>
';



//$pdf->write1DBarcode($rdata['reservation_id'], 'S25+', '', '', '', 18, 0.4, $style, 'N');

$pdf->writeHTML($html, true, false, false, false, '');
$pdf->Output('voucher-'.$date.'.pdf','I');
exit();

