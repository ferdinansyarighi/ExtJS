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
			$this->Cell(55, 25, "" . '', 0, 0, 'L', 0);
			$this->Cell(80, 25, "BERITA ACARA SERAH TERIMA PEKERJAAN" . '', 0, 0, 'L', 0);
			$this->Ln(25);
			$this->SetFont('Arial', '',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(50, 8, "Nama" . '', 0, 0, 'L', 0);
			$this->Cell(10, 8, ":" . '', 0, 0, 'L', 0);
			$this->Cell(55, 8, $nama . '', 0, 0, 'L', 0);			
			$this->Ln(5);
			$this->SetFont('Arial', '',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(50, 8, "Departemen" . '', 0, 0, 'L', 0);
			$this->Cell(10, 8, ":" . '',0, 0, 'L', 0);
			$this->Cell(55, 8, $department . '', 0, 0, 'L', 0);
			$this->Ln(5);
			$this->SetFont('Arial', '',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(50, 8, "Jabatan" . '', 0, 0, 'L', 0);
			$this->Cell(10, 8, ":" . '',0, 0, 'L', 0);
			$this->Cell(55, 8, $position . '', 0, 0, 'L', 0);
			$this->Ln(5);
			$this->SetFont('Arial', '',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(50, 8, "Tanggal Masuk" . '', 0, 0, 'L', 0);
			$this->Cell(10, 8, ":" . '',0, 0, 'L', 0);
			$this->Cell(55, 8, $tglmasuk . '', 0, 0, 'L', 0);
			$this->Ln(5);
			$this->SetFont('Arial', '',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(50, 8, "Tanggal Terakhir Aktif" . '', 0, 0, 'L', 0);
			$this->Cell(10, 8, ":" . '',0, 0, 'L', 0);
			$this->Cell(55, 8, $tglresign . '', 0, 0, 'L', 0);
			$this->Ln(10);
			$this->SetFont('Arial', '',10);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->Cell(190, 8, "Dengan ini menyatakan telah menyelesaikan serah terima :" . '', 0, 0, 'L', 0);
			$this->Ln(10);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,6,'','LRT',0,'C',1);
			$this->Cell(80,6,'','LRT',0,'C',1);
			$this->Cell(35,6,'','LRT',0,'C',1);
			$this->Cell(35,6,'TANDA','LRT',0,'C',1);
			$this->Cell(35,6,'','LRT',0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(10,6,'NO','LRB',0,'C',1);
			$this->Cell(80,6,'JENIS','LRB',0,'C',1);
			$this->Cell(35,6,'KETERANGAN','LRB',0,'C',1);
			$this->Cell(35,6,'TANGAN','LRB',0,'C',1);
			$this->Cell(35,6,'TANGGAL','LRB',0,'C',1);		
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'1',1,0,'C',1);
			$this->Cell(80,6,'   Inventaris',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'2',1,0,'C',1);
			$this->Cell(80,6,'   Daily Job (Rincian Pekerjaan dan Tanggung Jawab)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'3',1,0,'C',1);
			$this->Cell(80,6,'   Monthly Job (Pekerjaan Bulanan)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'4',1,0,'C',1);
			$this->Cell(80,6,'   Softcopy (Data yang disimpan di data D)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'5',1,0,'C',1);
			$this->Cell(80,6,'   Hardcopy (Berkas Fisik)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'6',1,0,'C',1);
			$this->Cell(80,6,'   Last Case (pekerjaan yang belum terselesaikan)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'7',1,0,'C',1);
			$this->Cell(80,6,'   Material yang dipinjam (Alat Kerja)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'8',1,0,'C',1);
			$this->Cell(80,6,'   Bon Sementara',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'9',1,0,'C',1);
			$this->Cell(80,6,'   Pinjaman',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);	
			$this->Ln(6);
			$this->SetFont('Arial','',9);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','');
			$this->Cell(10,6,'10',1,0,'C',1);
			$this->Cell(80,6,'   Furniture (Meja/Kursi)',1,0,'L',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);
			$this->Cell(35,6,'',1,0,'C',1);				 
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
	$pdf->Ln(1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(180, 5, 'Demikian berita acara serah terima ini diselesaikan.', 0, 0, 'L', 0);
	$pdf->Ln(7);
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
	$pdf->Cell(40, 5, "Pemohon", 0, 0, 'C', 0);
	$pdf->Cell(140, 5, "Mengetahui,", 0, 0, 'C', 0);
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(40, 20, "", 0, 0, 'C', 0);
	$pdf->Cell(140, 20, "", 0, 0, 'C', 0);
	$pdf->Ln(20);	
	$pdf->SetFont('Arial','',7);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(40, 5, "$namab", 0, 0, 'C', 0);
	$pdf->Cell(40, 5, "$managerb", 0, 0, 'C', 0);
	$pdf->Cell(25, 5, "( Mgr IT )", 0, 0, 'C', 0);
	$pdf->Cell(25, 5, "( Mgr FA )", 0, 0, 'C', 0);
	$pdf->Cell(40, 5, "$managerhrb", 0, 0, 'C', 0);
	$pdf->Cell(25, 5, "( Direksi )", 0, 0, 'C', 0);
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	$pdf->Cell(40, 5, "NB:", 0, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	$pdf->Cell(180, 5, "Untuk semua soft file pekerjaan yang diserah terimakan harap dicopy di folder permanen - pribadi", 0, 0, 'L', 0);
	$pdf->Ln(15);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(170, 5, "Mengetahui", 0, 0, 'R', 0);
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','');
	$pdf->Cell(169, 40, "(HR Area)", 0, 0, 'R', 0);

 
$pdf->SetFont('','B');
$pdf->Ln(15);

$pdf->Output('BAST_Pekerjaan_'. "$hdidlagi" . '.pdf', 'F');
$pdf->Output();
?>
