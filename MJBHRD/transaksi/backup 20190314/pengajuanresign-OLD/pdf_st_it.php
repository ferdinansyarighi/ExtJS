<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

$hdid = "";
$hari="";
$bulan="";
$tahun=""; 
$queryfilter=""; 
if (isset($_GET['hdid']))
{	
	$hdid = $_GET['hdid'];
} else {
	$hdid = "gagal";
}

class myPdf extends pdfmctable
{
	var $title;
	var $numtitle;
	
	function setTitle($title, $numtitle){
		$this->title	= $title;
		$this->numtitle	= $numtitle;
	}
		
	function Header()
	{
		include '../../main/koneksi3.php';
		
		$this->SetFont('Arial', '',20);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$hdid = $_GET['hdid'];
		
		$query = "SELECT NAMA_KARYAWAN, DEPARTMENT, POSITION, GRADE, LOCATION, TGL_MASUK, TGL_RESIGN FROM MJ.MJ_T_RESIGN WHERE NO_PENGAJUAN = '$hdid'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		$row = oci_fetch_row($result);

		$nama 		= $row[0];
		$department = $row[1];
		$position   = $row[2];
		$grade      = $row[3];
		$location   = $row[4];
		$tglmasuk   = $row[5];
		$tglresign  = $row[6];

		if($this->PageNo() == 1)
		{
			$this->Ln(4);
			$this->SetFont('Arial', 'B',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Image('logo_mjb.png',12,15,30);
			$this->Cell(70, 25, "" . '', 0, 0, 'L', 0);
			$this->Cell(90, 25, "SERAH TERIMA PERALATAN IT" . '', 0, 0, 'L', 0);
			$this->Ln(25);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,6,'NO','LRT',0,'C',1);
			$this->Cell(70,6,'NAMA PERALATAN IT','LRT',0,'C',1);
			$this->Cell(40,6,'LOKASI','LRT',0,'C',1);
			$this->Cell(35,6,'KONDISI','LRT',0,'C',1);
			$this->Cell(35,6,'KETERANGAN','LRT',0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,6,'','LRB',0,'C',1);
			$this->Cell(70,6,'','LRB',0,'C',1);
			$this->Cell(40,6,'','LRB',0,'C',1);
			$this->Cell(35,6,'','LRB',0,'C',1);
			$this->Cell(35,6,'','LRB',0,'C',1);		
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,10,'',1,0,'C',1);
			$this->Cell(70,10,'',1,0,'C',1);
			$this->Cell(40,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);
			$this->Cell(35,10,'',1,0,'C',1);		
			$this->Ln(10);
			$this->Ln(12);

		}	

	}			

	function Footer()
	{
		$tglskr=date('d F Y'); 
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',8);
	    $this->Cell(0,10,'Tanggal Cetak : '.$tglskr,0,0,'R');
	}
}		

// Out of the class
// Legal paper size: 355.6 mm x 215.9 mm
$pdf=new myPdf('P','mm','legal');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('P','A4','legal');				
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '',11);

	$query = "SELECT NAMA_KARYAWAN, MANAGER, HRD_MGR, ID FROM MJ.MJ_T_RESIGN WHERE NO_PENGAJUAN = '$hdid'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);

	$namab 		 = $row[0];
	$managerb    = $row[1];
	$managerhrb  = $row[2];
	$hdidlagi    = $row[3];

	$tglskr=date('d F Y');
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(180, 5, "Surabaya, $tglskr", 0, 0, 'L', 0);
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(50, 5, "Diserahkan Oleh,", 0, 0, 'C', 0);
	$pdf->Cell(50, 5, "Di Kroscek Oleh", 0, 0, 'C', 0);
	$pdf->Cell(50, 5, "Diterima Oleh", 0, 0, 'C', 0);
	$pdf->Cell(50, 5, "Mengetahui", 0, 0, 'C', 0);
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(50, 20, "", 0, 0, 'C', 0);
	$pdf->Cell(50, 20, "", 0, 0, 'C', 0);
	$pdf->Cell(50, 20, "", 0, 0, 'C', 0);
	$pdf->Cell(50, 20, "", 0, 0, 'C', 0);
	$pdf->Ln(10);	
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(50, 15, "$namab", 0, 0, 'C', 0);
	$pdf->Cell(50, 15, "( NAI )", 0, 0, 'C', 0);
	$pdf->Cell(50, 15, "(Logistik IT)", 0, 0, 'C', 0);
	$pdf->Cell(50, 15, "(Manager IT)", 0, 0, 'C', 0);
	$pdf->Ln(7);

 
$pdf->SetFont('','B');
$pdf->Ln(15);

$pdf->Output('ST_IT_'. "$hdidlagi" . '.pdf', 'F');
$pdf->Output();
?>
