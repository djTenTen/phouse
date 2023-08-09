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

    <h3 style="text-align: center;">Fund Transfer</h3>
    <br>
';

$html .= '
    <div class="custom-table">
        <table>
            <tbody>
                <tr>
                    <td style="width: 20%;">Reference ID</td>
                    <td style="width: 80%;">'.$ftdetails['fund_transfer_id'].'</td>
                </tr>

                <tr>
                    <td style="width: 20%;">Transfer From</td>
                    <td style="width: 80%;">['.$from['bank'].']'.$from['name'].'</td>
                </tr>

                <tr>
                    <td style="width: 20%;">Transfer To</td>
                    <td style="width: 80%;">['.$to['bank'].']'.$to['name'].'</td>
                </tr>

                <tr>
                    <td style="width: 20%;">Purpose</td>
                    <td style="width: 80%;">'.$ftdetails['purpose'].'</td>
                </tr>

                <tr>
                    <td style="width: 20%;">Check Number</td>
                    <td style="width: 80%;">'.$ftdetails['check_number'].'</td>
                </tr>

                <tr>
                    <td style="width: 20%;">Date</td>
                    <td style="width: 80%;">'.date_format(date_create($ftdetails['date']),"F d, Y").'</td>
                </tr>

                <tr>
                    <td style="width: 20%;">Amount</td>
                    <td style="width: 80%;">₱ '.number_format($ftdetails['amount'],2).'</td>
                </tr>
            </tbody>
        </table>
    </div>

';


$html .= '
    <div class="custom-table">
        <table>
            <tr>
                <td>Prepared By:
                    <br>
                    <br>
                    <br>
                    '.$ftdetails['added_by'].'
                </td>
                <td>Earvin Bryan Co</td>
                <td>Jacqueline Phoebe Zhang</td>
            </tr>
        </table>
    </div>
';








//$pdf->write1DBarcode($rdata['reservation_id'], 'S25+', '', '', '', 18, 0.4, $style, 'N');

$pdf->writeHTML($html, true, false, false, false, '');
$pdf->Output('FundTransfer-'.$date.'.pdf','I');
exit();

