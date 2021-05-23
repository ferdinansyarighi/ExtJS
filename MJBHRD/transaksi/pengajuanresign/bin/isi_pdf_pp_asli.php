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
	
	$queryfilter=" WHERE MJ_T_PP_HEADER_ID=$hdid "; 
} else {
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
		include '../../main/koneksi2.php';
		
		$this->SetFont('Arial', '',20);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$hdid = $_GET['hdid'];
		
		$query = "SELECT PPH.PP_NO, HL.LOCATION_CODE, TO_CHAR(PPH.TANGGAL_PP, 'DD Month YYYY'), PPH.CREATED_BY FROM MJ.MJ_T_PP_HEADER PPH INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PPH.LOCATION_ID WHERE ID=$hdid";
		$result = oci_parse($con, $query);
		oci_execute($result);
		$row = oci_fetch_row($result);
		$pp_no = $row[0];
		$loc_name = $row[1];
		$tgl_pp = $row[2];
		$pembuat = $row[3];
		if($this->PageNo() == 1)
		{
			//$this->Cell(100, 8, http://192.168.0.40/MJBPP/images/header.jpg, 1, 0, 'C', 0);	
			$this->Cell(350, 8, 'Permintaan Pembelian', 0, 0, 'C', 0);					
			$this->Ln(8);
			$this->Cell(350, 8, $pp_no, 0, 0, 'C', 0);					
			$this->Ln(8);
			$this->SetFont('Arial', '',12);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(100, 8, "Pembuat : " . $pembuat, 0, 0, 'L', 0);
			$this->Cell(235, 8, $loc_name . ", " . $tgl_pp, 0, 0, 'R', 0);					
			$this->Ln(1);
			$this->SetFont('Arial', '',20);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);			
			$this->Cell(335, 8, "______________________________________________________________________________________", 0, 0, 'C', 0);			
			$this->Ln(12);
			
			$this->SetFont('Arial','',11);
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,12,'No.',1,0,'C',1);
			$this->Cell(45,12,'Kode Item',1,0,'C',1);
			$this->Cell(60,12,'Nama Barang',1,0,'C',1);
			$this->Cell(35,12,'Keterangan Detail',1,0,'C',1);
			$this->Cell(30,12,'Merk',1,0,'C',1);		 
			$this->Cell(30,12,'Kategori',1,0,'C',1);	
			$this->Cell(30,12,'Satuan',1,0,'C',1);	
			$this->Cell(15,12,'Qty',1,0,'C',1);
			$this->Cell(30,12,'Tgl Kebutuhan',1,0,'C',1);
			$this->Cell(50,12,'Keterangan',1,0,'C',1);				 
			$this->Ln(12);
		}	

	}			

	function Footer()
	{
		$tglskr=date('d F Y'); 
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',8);
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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

	$countibase = 0;
	$query = "SELECT INVENTORY_ITEM_ID, KODE_ITEM, NAMA_ITEM, KETERANGAN_DETAIL, MERK, KATEGORI, SATUAN, QUANTITY, TO_CHAR(TGL_KEBUTUHAN, 'DD-MM-YYYY'), KETERANGAN FROM MJ.MJ_T_PP_DETAIL $queryfilter ORDER BY ID";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$KodeItem = str_replace("*", "'", $row[1]);
		$NamaItem = str_replace("*", "'", $row[2]);
		$KetDet = str_replace("*", "'", $row[3]);
		$countibase++;
		$pdf->SetWidths(array(10, 45, 60, 35, 30, 30, 30, 15, 30, 50));
		$pdf->SetAligns(array('C','L', 'L', 'L', 'L', 'C', 'C', 'R', 'C', 'L'));
		$pdf->Row(array(
			$countibase, $KodeItem, $NamaItem, $KetDet, $row[4],
			$row[5], $row[6], $row[7], $row[8], $row[9]), 0
		);
	}
	
	/* for ($i=0; $countibase < 10; $countibase++){
		$pdf->SetWidths(array(10, 45, 60, 35, 30, 30, 30, 15, 30, 50));
		$pdf->SetAligns(array('C','L', 'L', 'L', 'L', 'C', 'C', 'R', 'C', 'L'));
		$pdf->Row(array(
			'', '', '','', '',
			'', '', '', '', ''), 0
		);
	} */
		
	$pdf->Ln(14);
	$pdf->SetFont('Arial','',11);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	$pdf->Cell(120, 20, ',', 0, 0, 'R', 0);	
	$pdf->Cell(70, 20, 'Diminta Oleh,', 1, 0, 'C', 0);	
	$pdf->Cell(70, 20, 'Menyetujui,', 1, 0, 'C', 0);			
	$pdf->Cell(70, 20, 'Mengetahui,', 1, 0, 'C', 0);					
	$pdf->Ln(5);
	$pdf->Cell(120, 20, '', 0, 0, 'R', 0);	
	$pdf->Cell(70, 20, '(Pembuat)', 0, 0, 'C', 0);	
	$pdf->Cell(70, 20, '(KA.Produksi/Manager Dept.)', 0, 0, 'C', 0);			
	$pdf->Cell(70, 20, '(PIC Penanggung Jawab)', 0, 0, 'C', 0);					
	$pdf->Ln(15);
	$pdf->Cell(120, 30, '', 0, 0, 'R', 0);	
	$pdf->Cell(70, 30, '(...................................)', 1, 0, 'C', 0);	
	$pdf->Cell(70, 30, '(...................................)', 1, 0, 'C', 0);		
	$pdf->Cell(70, 30, '(...................................)', 1, 0, 'C', 0);					
	$pdf->Ln(14);
 
$pdf->SetFont('','B');
$pdf->Ln(15);
/* 
$query = "SELECT PPH.PP_NO FROM MJ.MJ_T_PP_HEADER PPH INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PPH.LOCATION_ID WHERE ID=$hdid";
$result = oci_parse($con, $query);
oci_execute($result);
$row = oci_fetch_row($result);
$pp_no = $row[0]; */

$pdf->Output('CetakPP_'. $hdid . '.pdf', 'F');
$pdf->Output();
?>
