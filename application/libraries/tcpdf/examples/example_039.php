<?php
//============================================================+
// File name   : example_039.php
// Begin       : 2008-10-16
// Last Update : 2014-01-13
//
// Description : Example 039 for TCPDF class
//               HTML justification
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
 * @abstract TCPDF - Example: HTML justification
 * @author Nicola Asuni
 * @since 2008-10-18
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MIS - ACI Limited');
$pdf->SetTitle('Yamaha Invoice');
$pdf->SetSubject('Yamaha Invoice');
$pdf->SetKeywords('Yamaha Invoice');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 039', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetLineStyle(array('cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 255)));

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage();
// set font
$pdf->SetFont('helvetica', 'B', 20);
//$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

// create some HTML content
$html = '
<table  cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td colspan="4" style="text-align: center;">
			<b>Money Receipt</b>
			<br />
			ACI Motor House
			<br />
			243, Tejgaon , Dhaka
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px; border-bottom: 1px solid #111;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="4" style="text-align: center; ">
			<hr />
		</td>
	</tr>
	
	<tr>
		<td style="font-weight:bold">Invoice No : 0001</td>
		<td>&nbsp;</td>
		<td style=""></td>
		<td style="text-align: right;font-weight:bold">Date : Jan 01 2015</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td colspan="1" style=" padding-top: 10px;">Received sum of BDT : </td>
		<td colspan="3" style="  border-bottom:1px solid #111; padding-top: 10px;">200000/-</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">In Words : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">Two Luck Tk Only </td> 
	</tr>
	
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr> 
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">From : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">Zillur Rahaman</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Address : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">245 Tajgou. ACI Center</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">As price against sale of : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">FZS FI V 2.0 Shark White 2GS400BWC1</td> 
	</tr>
	
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="2" style="font-weight:bold;">E/N :  G3C8E0354191</td>
		<td colspan="2" style="font-weight:bold;">C/N : ME1RG0728G0232250<br /><br /></td>
	</tr>
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="2" style="font-weight:bold;">Customer signature</td>
		<td colspan="2" style="font-weight:bold; text-align: right;">Authorised Signature<br /><br /></td>
	</tr>
</table>
';

// set core font
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();


/*secound page*/

// add a page
$pdf->AddPage();
// set font
$pdf->SetFont('helvetica', 'B', 20);
//$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

// create some HTML content
$html = '
<table  cellspacing="0" cellpadding="0" width="600px">
	<tr>
		<td colspan="4" style="text-align: center;">
			<b>Agreement Paper </b>
			<br />
			ACI Motor House
			<br />
			243, Tejgaon , Dhaka
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px; border-bottom: 1px solid #111;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="4" style="text-align: center; ">
			 &nbsp;
		</td>
	</tr>
	
	 
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	
	
	<tr>
		<td colspan="4"  style="padding-top: 10px; line-height: 23px;">
			We hereby are declearing that we have sold the following motor					
			cycle to <font style="text-decoration:underline"> Zillur Rahaman </font> Address 
			<font style="text-decoration:underline"> 245, Tejgaon Industrial Area Dhaka 1208, Bangladesh</font>					
			Fathers Name <font style="text-decoration:underline">Khurshed Alam</font> on <font style="text-decoration:underline"> Date Jan 01 2015</font>.					
			We have provided all the documents that required for Registration.					
			If there are any difficulties about the documents we provided in					
			future then we will bear the responsibilities. The infornation is					

		</td> 
	</tr>
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	  
	
	<tr>
		<td style="padding-bottom: 5px;">
			C/N	 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			ME1RG0728G0232250	 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			E/N 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			G3C8E0354191	 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Import from 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			India	 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Import Year
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			New Dilllhi India	 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Color
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			Red
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			CC
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			150 CC
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="2" style="font-weight:bold;"></td>
		<td colspan="2" style="font-weight:bold; text-align: right;">Authorised Seal & Signature
<br /><br /></td>
	</tr>
</table>
';
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

/*secound page*/




/*third page*/

// add a page
$pdf->AddPage();
// set font
$pdf->SetFont('helvetica', 'B', 20);
//$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

// create some HTML content
$html = '
<table  cellspacing="0" cellpadding="0" width="600px">
	<tr>
		<td colspan="4" style="text-align: center;">
			<b>Invoice	</b>
			<br />
			ACI Motor House
			<br />
			243, Tejgaon , Dhaka
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px; border-bottom: 1px solid #111;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="4" style="text-align: center; ">
			<hr />
		</td>
	</tr>
	
	<tr>
		<td style="font-weight:bold">Invoice No : 0001</td>
		<td>&nbsp;</td>
		<td style=""></td>
		<td style="text-align: right;font-weight:bold">Date : Jan 01 2015</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td colspan="1" style=" padding-top: 10px;">Mr./Mrs/M/s : </td>
		<td colspan="3" style="  border-bottom:1px solid #111; padding-top: 10px;">Zillur Rahaman</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Fathers Name : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">Khurshed Alam </td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Address : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">243, Tejgaon, Dhaka</td> 
	</tr>	
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Phone : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">01757615879</td> 
	</tr>
	
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr> 
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="4">
		  <table style="width: 800px;">
			<tr style="width: 100%;">
				<td style="border: 1px solid #111; padding: 5px;" height="21" width="64">&nbsp;SL NO</td>
				<td style="border: 1px solid #111; padding: 5px;" width="340">&nbsp;Particulars</td>
				<td style="border: 1px solid #111; padding: 5px;" width="64">&nbsp;BDT</td>
				<td style="border: 1px solid #111; padding: 5px;" width="64">&nbsp;PCS</td>
				<td style="border: 1px solid #111; padding: 5px;" width="64">&nbsp;Total</td>
			</tr>
			  
			<tr>
				<td style="border: 1px solid #111; padding: 5px;">&nbsp;1</td>
				<td style="border: 1px solid #111; padding: 5px;">&nbsp;FZS FI V 2.0 Shark White 2GS400BWC1</td>
				<td style="border: 1px solid #111; padding: 5px;" width="64">&nbsp;2250000</td>
				<td style="border: 1px solid #111; padding: 5px;" width="64">&nbsp;1</td>
				<td style="border: 1px solid #111; padding: 5px;" width="64">&nbsp;2250000</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border: 1px solid #111;padding: 5px;" width="64">&nbsp;Total</td>
				<td style="border: 1px solid #111;padding: 5px;" width="64">&nbsp;2250000/-</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border: 1px solid #111;padding: 5px;" width="64">&nbsp;Less ad</td>
				<td style="border: 1px solid #111;padding: 5px;" width="64">&nbsp;0/-</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="border: 1px solid #111;" width="64">&nbsp;Net</td>
				<td style="border: 1px solid #111;" width="64">&nbsp;2250000/-</td>
			</tr> 
			   
			</table>	
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	
	
	 
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="2" style="font-weight:bold;">Customer signature</td>
		<td colspan="2" style="font-weight:bold; text-align: right;">Authorised Seal & Sign	<br /><br /></td>
	</tr>
</table>
 
';
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

/*third page*/






/*Forth page*/

// add a page
$pdf->AddPage();
// set font
$pdf->SetFont('helvetica', 'B', 20);
//$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

// create some HTML content
$html = '
<table  cellspacing="0" cellpadding="0" width="600px">
	<tr>
		<td colspan="4" style="text-align: center;">
			<b>Sales Certificate</b>
			<br />
			ACI Motor House
			<br />
			243, Tejgaon , Dhaka
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px; border-bottom: 1px solid #111;">&nbsp;</td> </tr>
	
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	
	
	<tr>
		<td colspan="4"  style="padding-top: 10px; line-height: 23px;">
			To 	<br />				
			Assistant Director		<br />			
			BRTA					<br />
			Sub: Regarding new Motor cycle Registration					<br />
			Sir,					
			With due respect, it is to inform you that from our showroom under					
			noted motorcycle sold to ,Name : <font style="text-decoration:underline">Zillur Rahaman</font>
			Fathers Name: <font style="text-decoration:underline">Khurshed Alam</font>					
			Address: <font style="text-decoration:underline">245 Tejgaon Industrial Area, Bir Uttam Mir Shawkat Sarak</font>						

		</td> 
	</tr>
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	  
	
	<tr>
		<td style="padding-bottom: 5px;">
			Class of vehicle: 	 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			Motorcycle	 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Prev. Regn. No. (if Any): 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
				 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Maker Name: 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			India yamha Motors Pvt ltd	 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Makers Country: 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			India 
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Color
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			Red
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Year of Manufacture: 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			2016
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			Number of cylinders  one: 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			C/N
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			ME1RG0728G0232250
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			E/N
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			G3C8E0354191
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Fuel used 
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			Petrol
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr>
		<td style="padding-bottom: 5px;">
			Horse Power
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			RPM
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			Cubic capacilty
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			Seats including driver
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			Two
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			wheel Base:
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			weight:
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			Maximum laden weight:
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			No of tyre:
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			two	
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td style="padding-bottom: 5px;">
			No of Axel:
		</td>
		<td colspan="3" style="border-bottom: 1px solid #111; padding-bottom: 5px;">
			two	
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 3px;">&nbsp;</td> </tr>
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="2" style="font-weight:bold;"></td>
		<td colspan="2" style="font-weight:bold; text-align: right;">Signature
<br /><br /></td>
	</tr>
</table>
';
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

/*Forth page*/











/*Fifth page*/

// add a page
$pdf->AddPage();
// set font
$pdf->SetFont('helvetica', 'B', 20);
//$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

// create some HTML content
$html = '
<table  cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td colspan="4" style="text-align: center;">
			<b>Gate Pass	</b>
			<br />
			ACI Motor House
			<br />
			243, Tejgaon , Dhaka
		</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px; border-bottom: 1px solid #111;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="4" style="text-align: center; ">
			<hr />
		</td>
	</tr>
	
	<tr>
		<td style="font-weight:bold">Invoice No : 0001</td>
		<td>&nbsp;</td>
		<td style=""></td>
		<td style="text-align: right;font-weight:bold">Date : Jan 01 2015</td>
	</tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	
	<tr>
		<td colspan="1" style=" padding-top: 10px;">To: </td>
		<td colspan="3" style="padding-top: 10px;">&nbsp;</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Name: : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">Zillur Rahaman</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Address : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">245 Tajgou. ACI Center</td> 
	</tr>
	<tr><td colspan="4"  style="padding-top: 2px;">&nbsp;</td> </tr>
	<tr>
		<td colspan="1">Phone : </td>
		<td colspan="3" style="border-bottom:1px solid #111;">01757615879</td> 
	</tr>
 
	
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	
	
	<tr>
		<td colspan="4"  style="padding-top: 10px;">
			<table style="width: 100%;">
			  
			  <tr height="20">
				<td height="20" width="64" style="border: 1px solid #111;">&nbsp; SL No</td>
				<td colspan="3" width="418" style="border: 1px solid #111;">&nbsp; Description</td>
				<td width="64" style="border: 1px solid #111;">&nbsp; unit</td>
				<td width="64" style="border: 1px solid #111;">&nbsp; Qty</td>
				<td width="64" style="border: 1px solid #111;">&nbsp; Remarks</td>
			  </tr>
			  <tr height="100">
				<td height="147" style="border: 1px solid #111;">&nbsp; 1</td>
				<td height="147" width="418" style="border: 1px solid #111;">
					&nbsp; Name : FZS FI V 2.0 Shark White 2GS400BWC1<br>
				  	Model : FI V 2.0<br>
				  	E/N : G3C8E0354191 	<br>
				  	C/N : ME1RG0728G0232250</td>
				<td style="border: 1px solid #111;">&nbsp; 1</td>
				<td style="border: 1px solid #111;">&nbsp; 1</td>
				<td style="border: 1px solid #111;">&nbsp; Red</td>
			  </tr>
			  
			</table>
		</td> 
	</tr>
	
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="4" style="font-weight:bold;">N.B: This pass is valid for the day of issue only</td>
	</tr>
	
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	<tr><td colspan="4"  style="padding-top: 10px;">&nbsp;</td> </tr>
	
	<tr>
		<td colspan="2" style="font-weight:bold;">Received</td>
		<td colspan="2" style="font-weight:bold; text-align: right;">Authorised Signature<br /><br /></td>
	</tr>
	
</table>
';
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln();

/*Fifth page*/





// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('YamahaInvoice_InvoiceNo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
