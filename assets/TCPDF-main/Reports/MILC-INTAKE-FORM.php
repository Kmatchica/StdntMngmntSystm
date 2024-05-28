<?php
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
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
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 * @group header
 * @group footer
 * @group page
 * @group pdf
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
require_once('../../../includes/dbconnection.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	
	//Page header
	public function Header() {
		// Logo
		// $image_file = K_PATH_IMAGES.'logo_example.jpg';
		// $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		$this->setFont('helvetica', 'B', 13);
		$this->SetY(10); // Adjust this value to set the desired margin/padding
		$this->Cell(0, 20, 'Mother Ignacia National Social Apostolate Center, Inc.', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		
		$this->setFont('helvetica', '', 9);
		$this->setX((100 - $this->GetStringWidth('Roberto St., Group 13, Payatas B, Quezon City')) / 2);
		$this->Cell(0, 15, 'Roberto St., Group 13, Payatas B, Quezon City', 0, false, 'C', 0, '', 0, false, 'M', 'B');
	
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->setY(-15);
		// Set font
		$this->setFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Lolita Matchica');
$pdf->setTitle('MILC INTAKE FORM');
$pdf->setSubject('MILC');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->setFont('helvetica', '', 12);

// add a page
$pdf->AddPage();
$eid = isset($_GET['studId']) ? $_GET['studId'] : 0;
$sql="SELECT tblstudent.StuID,tblstudent.ID as sid,tblstudent.StudentName,tblstudent.StudentEmail,tblstudent.DateofAdmission,tblclass.ClassName,tblclass.Section from tblstudent join tblclass on tblclass.ID=tblstudent.StudentClass  where tblstudent.ID=:eid";
$query=$dbh->prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0){
	foreach($results as $row)
	{ 
		$studentId = $row->sid;
		$studentName = $row->StudentName;
	}
}

$html = '
<h2 style="text-align:center"><center>INTAKE FORM</center></h2>
<ol style="list-style-type: upper-roman;">
	<li>Personal Information(Family Head)
		<ul class="personalInfo" style="padding-left: 100em;">
			<li>Name: <b>'.$studentName.'</b></li>
			<li>Date of Birth:</li>
			<li>Place of Birth:</li>
			<li>Home Address:</li>
			<li>Provincial Address:</li>
		</ul>
	</li>
	
	<li></li>
	<li>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.<br />Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</li>
	<li>SUBLIST
		<ol>
			<li>row one
				<ul>
					<li>sublist</li>
				</ul>
			</li>
			<li>row two</li>
		</ol>
	</li>
	<li><b>T</b>E<i>S</i><u>T</u> <del>line through</del></li>
	<li><font size="+3">font + 3</font></li>
	<li><small>small text</small> normal <small>small text</small> normal <sub>subscript</sub> normal <sup>superscript</sup> normal</li>
</ol>
<dl>
	<dt>Coffee</dt>
	<dd>Black hot drink</dd>
	<dt>Milk</dt>
	<dd>White cold drink</dd>
</dl>
<div style="text-align:center">IMAGES<br />
<img src="images/logo_example.png" alt="test alt attribute" width="100" height="100" border="0" /><img src="images/tcpdf_box.svg" alt="test alt attribute" width="100" height="100" border="0" /><img src="images/logo_example.jpg" alt="test alt attribute" width="100" height="100" border="0" />
</div>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
