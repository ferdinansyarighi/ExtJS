<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

$hdid = $_GET['hdid'];
//$hdid = 13;
$hari="";
$bulan="";
$tahun=""; 
$queryfilter=""; 
$mutasi = "    ";
$promosi = "    ";
$demosi = "    ";
$skTetap = "    ";
$skPercobaan = "    ";
$skPJS = "    ";
$skKontrak = "    ";
$skKhusus = "    ";
$spTetap = "    ";
$spSementara = "    ";
$lama_bulan = "...................";
if (isset($_GET['hdid']))
{	
	$hdid = $_GET['hdid'];
	
	$queryfilter=" AND ID in ($hdid) "; 
}
//echo $queryfilter;exit;
function penyebut($nilai) {
$nilai = abs($nilai);
$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
$temp = "";
if ($nilai < 12) {
	$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " Belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " Seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " Seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
	}    
	return $temp;
}
 
function terbilang($nilai) {
	if($nilai<0) {
		$hasil = "minus ". trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}    
	return $hasil." Rupiah";
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
		//$hdid = $_GET['hdid'];
		// $hdid = 13;
		// $query = "SELECT BS.NO_BS
		// FROM MJ.MJ_T_BS BS
		// WHERE 1=1 AND BS.ID in ($hdid)";
		// //echo $query;exit;
		// $result = oci_parse($con,$query);
		// oci_execute($result);
		// while($row = oci_fetch_row($result))
		// {
			// $xNoReq=$row[0];
		// }
		
		$this->SetFont('Arial', 'B', 15);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		
		if($this->PageNo() == 1)
		{
			//$this->Cell(30, 10.5, '', 1, 0, 'L', 0);	
			//$this->Cell(30, 10.5, '', 1, 0, 'L', 0);	
			
			// $this->Ln(6);
			// //$this->Image('logo_mjg.png',20,20,35);
			// $this->Cell(158, 10.5, '', 0, 0, 'L', 0);
			// $this->SetFont('Arial', 'B', 15);
			// $this->Cell(30, 6, 'F-02', 1, 0, 'C', 0);
			// $this->Ln(6);
			// $this->SetFont('Arial', 'BU', 8);
			// $this->Cell(200, 10, 'NIK :   '.'                    ', 0, 0, 'C', 90);	
			// $this->Ln(8);
			// $this->SetFont('Arial', 'B', 9);
			// $this->Cell(200, 6, 'FORMULIR USULAN PERUBAHAN STATUS KARYAWAN', 0, 0, 'C', 0);					
			// $this->Ln(6);
			// $this->SetFont('Arial', 'B', 12);
			// $this->Cell(200, 4, 'FPSK', 0, 0, 'C', 0);						
			// $this->Ln(5);
		}	

	}			

	function Footer()
	{
		$tglskr=date('d F Y'); 
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',8);
	    //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	    $this->Cell(0,10,'',0,0,'C');
	    $this->Cell(0,10,'Tanggal Cetak : '.$tglskr,0,0,'R');
	}
}		

// Out of the class
// Legal paper size: 355.6 mm x 215.9 mm
$pdf=new myPdf('L','mm','A5');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('L','A5','A5');				
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 8);
//EXIT;
$query = "SELECT NO_BS, TO_CHAR(CREATED_DATE, 'DD-MON-YYYY'), NOMINAL, KETERANGAN, ASSIGNMENT_ID, PENANGGUNG_JAWAB, TO_CHAR(CREATED_DATE, 'DD-MON-YYYY HH24:MI') FROM MJ.MJ_T_BS where 1=1 $queryfilter";
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$no_bs=$row[0];
	$tgl=$row[1];
	$nominal=$row[2];
	$keterangan=$row[3];
	$assign_id=$row[4];
	$pj=$row[5];
	$created_date=$row[6];


$queryPeminjam = "SELECT DISTINCT FULL_NAME FROM PER_ASSIGNMENTS_F PAF
    INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID
    AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
    AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
    WHERE PAF.ASSIGNMENT_ID = $assign_id";
//echo $queryPemohon;
$resultPeminjam = oci_parse($con,$queryPeminjam);
oci_execute($resultPeminjam);
$rowPeminjam = oci_fetch_row($resultPeminjam);
$peminjam = $rowPeminjam[0];

$queryPj = "SELECT DISTINCT FULL_NAME FROM PER_ASSIGNMENTS_F PAF
    INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID
    AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
    AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
    WHERE PAF.PERSON_ID = $pj";
//echo $queryPemohon;
$resultPj = oci_parse($con,$queryPj);
oci_execute($resultPj);
$rowPj = oci_fetch_row($resultPj);
$nama_pj = $rowPj[0];

$queryTgl = "SELECT TO_CHAR(SYSDATE, 'DD') || ' ' || 
TRIM(TO_CHAR(SYSDATE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || 
TO_CHAR(SYSDATE, 'YYYY') TGL 
FROM dual";
//echo $queryPemohon;
$resultTgl = oci_parse($con,$queryTgl);
oci_execute($resultTgl);
$rowTgl = oci_fetch_row($resultTgl);
$tglskr = $rowTgl[0];

$queryHRD = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%' AND (PP.NAME LIKE '%HRD.MGR.HRD%') 
AND PAF.JOB_ID = (SELECT JOB_ID FROM APPS.PER_JOBS WHERE NAME = 'HRD.Human Resource Department')";
//echo $queryPemohon;
$resultHRD = oci_parse($con,$queryHRD);
oci_execute($resultHRD);
$rowHRD = oci_fetch_row($resultHRD);
$managerHRD = $rowHRD[0];	

	 $pdf->Image('approve-min.png',28,103,25);
	// $pdf->Image('approve-min.png',50.5,220,25);
	// $pdf->Image('approve-min.png',81,220,25);
	// $pdf->Image('approve-min.png',111,220,25);
	//$pdf->Image('approve-min.png',171,220,25);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(120, 12, 'BON SEMENTARA', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(60, 6, 'No   : '.$no_bs, 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(120, 6, '', 0, 0, 'C', 0);
	$pdf->Cell(60, 6, 'Tgl   : '.$tgl, 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 9, '    Jumlah      :    Rp.  '.number_format($nominal, 2, ',', '.'), 'LRT', 0, 'L', 0);
	$pdf->Ln(9);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 9, '    Terbilang   :    '.terbilang($nominal), 'LRB', 0, 'L', 0);
	$pdf->Ln(9);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 9, '    Untuk keperluan   : '.$keterangan, 'LRT', 0, 'L', 0);
	$pdf->Ln(9);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 45, '', 'LRB', 0, 'L', 0);
	$pdf->Ln(45);
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(8, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(45, 5, 'Mengetahui :', 1, 0, 'C', 0);
	$pdf->Cell(45, 5, 'Menyetujui', 1, 0, 'C', 0);
	$pdf->Cell(45, 5, 'Kasir', 1, 0, 'C', 0);
	$pdf->Cell(45, 5, 'Peminjam', 1, 0, 'C', 0);
	$pdf->Ln(5);
	
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(8, 18, '', 0, 0, 'L', 0);	
	$pdf->Cell(45, 18, '', 'LRT', 0, 'C', 0);
	$pdf->Cell(45, 18, '', 'LRT', 0, 'C', 0);
	$pdf->Cell(45, 18, '', 'LRT', 0, 'C', 0);
	$pdf->Cell(45, 18, $created_date, 'LRT', 0, 'C', 0);
	$pdf->Ln(18);
	$pdf->Cell(8, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(45, 10, $nama_pj, 'LRB', 0, 'C', 0);
	$pdf->Cell(45, 10, '', 'LRB', 0, 'C', 0);
	$pdf->Cell(45, 10, '', 'LRB', 0, 'C', 0);
	$pdf->Cell(45, 10, $peminjam, 'LRB', 0, 'C', 0);
	$pdf->Ln(15);
 
	$pdf->SetFont('','B');
	$pdf->Ln(15);
}
$pdf->Output('CetakBS'. '.pdf', 'F');
$pdf->Output();
?>
