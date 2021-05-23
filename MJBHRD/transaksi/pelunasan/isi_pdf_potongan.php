<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

$hdid = $_GET['hdid'];
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
// if (isset($_GET['hdid']))
// {	
	// $hdid = $_GET['hdid'];
	
	// $queryfilter=" WHERE ID=$hdid "; 
// }

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
		$hdid = $_GET['hdid'];
		
		$query = "SELECT PPF.EMPLOYEE_NUMBER
		FROM MJ.MJ_T_PINJAMAN MTM
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		WHERE 1=1 AND MTM.ID = $hdid";
		$result = oci_parse($con,$query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$nik=$row[0];
		}
		$this->SetFont('Arial', 'B', 15);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		
		if($this->PageNo() == 1)
		{
			//$this->Cell(30, 10.5, '', 1, 0, 'L', 0);	
			//$this->Cell(30, 10.5, '', 1, 0, 'L', 0);	
			
			$this->Ln(6);
			$this->Image('logo_mjg.png',20,20,35);
			$this->Cell(158, 4, '', 0, 0, 'L', 0);
			$this->SetFont('Arial', 'B', 15);
			//$this->Cell(30, 6, 'F-12', 1, 0, 'C', 0);
			$this->Ln(4);
			$this->SetFont('Arial', 'B', 14);
			$this->Cell(8, 4, '', 0, 0, 'L', 0);	
			$this->Cell(38, 18, '', 1, 0, 'C', 90);	
			$this->Cell(142, 18, 'BUKTI PEMBAYARAN PINJAMAN KARYAWAN', 1, 0, 'C', 90);	
			$this->Ln(8);			
			$this->Ln(6);					
			$this->Ln(5);
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
$pdf=new myPdf('P','mm','A4');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('P','A4','A4');				
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 8);

$query = "SELECT MTP.ID, MTPP.NO_PELUNASAN, MTPP.TIPE_PELUNASAN, MTPP.TGL_PELUNASAN
, MTP.NOMOR_PINJAMAN, MTP.TIPE TIPE_PINJAMAN, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'DD-MON-YYYY') TANGGAL_PINJAMAN
, TO_CHAR(MTA.CREATED_DATE, 'DD-MON-YYYY') TGL_VALIDASI
, MTP.JUMLAH_PINJAMAN
, MTP.JUMLAH_CICILAN+(SELECT COUNT(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL WHERE MJ_T_PINJAMAN_ID=MTP.ID) JUMLAH_CICILAN
, MTP.NOMINAL NOMINAL_CICILAN
, MTP.JUMLAH_PINJAMAN-(SELECT SUM(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL
        WHERE MJ_T_PINJAMAN_ID=MTP.ID) OUTSTANDING
--, (SELECT COUNT(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL WHERE MJ_T_PINJAMAN_ID=MTP.ID) CICILAN_YG_SUDAH_DIBAYAR
--, (SELECT SUM(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL WHERE MJ_T_PINJAMAN_ID=MTP.ID) NOMINAL_YG_SUDAH_DIBAYAR
, NVL(MTPD.BULAN||' '||MTPD.TAHUN,'-') CICILAN_TERAKHIR
FROM MJ.MJ_T_PELUNASAN_PINJAMAN MTPP
INNER JOIN MJ.MJ_T_PELUNASAN_PINJAMAN_DT MTPPD ON MTPP.ID = MTPPD.HDID
INNER JOIN MJ.MJ_T_PINJAMAN MTP ON MTPPD.ID_PINJAMAN = MTP.ID
LEFT JOIN (
        SELECT MAX(ID) ID, TRANSAKSI_ID FROM
        MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN'
        GROUP BY TRANSAKSI_ID
    ) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'
LEFT JOIN (
        SELECT MAX(ID) ID, MJ_T_PINJAMAN_ID FROM
        MJ.MJ_T_PINJAMAN_DETAIL WHERE STATUS = 1
        GROUP BY MJ_T_PINJAMAN_ID 
    ) MTPD2 ON MTPD2.MJ_T_PINJAMAN_ID = MTP.ID  
LEFT JOIN MJ.MJ_T_PINJAMAN_DETAIL MTPD ON MTPD.ID = MTPD2.ID
INNER JOIN PER_ASSIGNMENTS_F PAF ON MTPP.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
WHERE 1=1 
AND MTP.ID = 55
AND ((MTP.TIPE = 'PINJAMAN PERSONAL' and MTP.STATUS_DOKUMEN = 'Validate') 
or (MTP.TIPE = 'PINJAMAN PENGGANTI INVENTARIS' and MTP.STATUS_DOKUMEN = 'Approved'))";
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$id=$row[0];
	$nomor_pelunasan=$row[1];
	$tipe_pelunasan=$row[2];
	$tgl_pelunasan=$row[3];
	$nomor_pinjaman=$row[4];
	$tipe_pinjaman=$row[5];
	$tgl_pinjaman=$row[6];
	$tgl_validasi=$row[7];
	$jumlah_pinjaman=$row[8];
	$jumlah_cicilan=$row[9];
	$nominal_cicilan=$row[10];
	$outstanding=$row[11];
	$cicilan_terakhir=$row[12];
}

$queryTgl = "SELECT TO_CHAR(SYSDATE, 'DD') || ' ' || 
TRIM(TO_CHAR(SYSDATE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || 
TO_CHAR(SYSDATE, 'YYYY') TGL 
FROM dual";
//echo $queryPemohon;
$resultTgl = oci_parse($con,$queryTgl);
oci_execute($resultTgl);
$rowTgl = oci_fetch_row($resultTgl);
$tglskr = $rowTgl[0];

// $queryPem = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
// FROM APPS.PER_PEOPLE_F PPF
// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
// INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
// AND (PP.NAME LIKE '%MGR%')
// AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
// AND PAF.JOB_ID='$xDeptLamaID'";
// //echo $queryPemohon;
// $resultPem = oci_parse($con,$queryPem);
// oci_execute($resultPem);
// $rowPem = oci_fetch_row($resultPem);
// $managerKaryawan = $rowPem[0];

// $queryDept = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
// FROM APPS.PER_PEOPLE_F PPF
// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
// INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
// AND (PP.NAME LIKE '%MGR%')
// AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
// AND PAF.JOB_ID='$xDeptBaruID'";
// //echo $queryPemohon;
// $resultDept = oci_parse($con,$queryDept);
// oci_execute($resultDept);
// $rowDept = oci_fetch_row($resultDept);
// $managerDept = $rowDept[0];

$queryHRD = "SELECT DECODE(MTP.BYHRD, 'Y', PPF.FULL_NAME, PPF2.FULL_NAME) HRD 
			, DECODE(MTP.BYHRD, 'Y', TO_CHAR(MTP.CREATED_DATE, 'DD Mon YYYY'), TO_CHAR(MTA.CREATED_DATE, 'DD Mon YYYY')) tgl
		FROM MJ.MJ_T_PINJAMAN MTP
        INNER JOIN PER_PEOPLE_F PPF ON MTP.CREATED_BY = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
        LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTP.ID = MTA.TRANSAKSI_ID AND TRANSAKSI_KODE = 'PINJAMAN' AND MTA.TINGKAT = 1 
        LEFT JOIN PER_PEOPLE_F PPF2 ON MTA.EMP_ID = PPF2.PERSON_ID AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
        WHERE MTP.ID = $hdid ";
//echo $queryPemohon;
$resultHRD = oci_parse($con,$queryHRD);
oci_execute($resultHRD);
$rowHRD = oci_fetch_row($resultHRD);
$managerHRD = $rowHRD[0];	
$tglAppHRD = $rowHRD[1];	

	//$pdf->Image('approve-min.png',20,182,25);
	//$pdf->Image('approve-min.png',50.5,182,25);
	//$pdf->Image('approve-min.png',81,182,25);
	//$pdf->Image('approve-min.png',111,182,25);
	//$pdf->Image('approve-min.png',171,182,25);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'LT', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Tanggal', 'T', 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 'T', 0, 1, 0);
	$pdf->Cell(80, 6, $tgl_pelunasan, 'T', 0, 'L', 0);
	$pdf->Cell(10, 6, 'NO.', 'T', 0, 'C', 0);
	$pdf->Cell(35, 6, $nomor_pelunasan, 'RT', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, '', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, '', 0, 0, 1, 0);
	$pdf->Cell(80, 6, '', 0, 0, 'L', 0);
	$pdf->Cell(10, 6, '', 0, 0, 'C', 0);
	$pdf->Cell(35, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(174, 6, 'Bersama dengan ini menerangkan sebagai berikut :', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Nama', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'NIK', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Bagian / Jabatan', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'No Pinjaman', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, $nomor_pinjaman, 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Total Pinjaman', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, 'Rp. '.$jumlah_pinjaman, 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Sisa Pinjaman', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, 'Rp. '.$outstanding, 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Nominal Pembayaran', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, ':', 0, 0, 1, 0);
	$pdf->Cell(125, 6, 'Rp. ', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, '', 0, 0, 'L', 0);
	$pdf->Cell(5, 6, '', 0, 0, 1, 0);
	$pdf->Cell(125, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, '', 0, 0, 'L', 0);
	$pdf->Cell(35, 6, '', 0, 0, 1, 0);
	$pdf->Cell(30, 6, 'Karyawan', 1, 0, 'C', 0);
	$pdf->Cell(30, 6, 'Manager HRD', 1, 0, 'C', 0);
	$pdf->Cell(30, 6, 'Manager Finance', 1, 0, 'C', 0);
	$pdf->Cell(5, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 18, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 18, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 18, '', 0, 0, 'L', 0);
	$pdf->Cell(35, 18, '', 0, 0, 1, 0);
	$pdf->Cell(30, 18, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 18, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 18, '', 1, 0, 'C', 0);
	$pdf->Cell(5, 18, '', 'R', 0, 'L', 0);
	$pdf->Ln(18);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'L', 0, 'L', 0);
	$pdf->Cell(44, 6, '', 0, 0, 'L', 0);
	$pdf->Cell(35, 6, '', 0, 0, 1, 0);
	$pdf->Cell(30, 6, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 6, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 6, '', 1, 0, 'C', 0);
	$pdf->Cell(5, 6, '', 'R', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->SetFont('Arial', 'I', 6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(6, 6, '', 'LB', 0, 'L', 0);
	$pdf->Cell(40, 6, 'Lembar 1 (Asli) : Karyawan', 'B', 0, 'L', 0);
	$pdf->Cell(44, 6, 'Lembar 2 (merah) : HRD Payroll', 'B', 0, 1, 0);
	$pdf->Cell(44, 6, 'Lembar 3 (kuning) : Finance', 'B', 0, 1, 0);
	$pdf->Cell(46, 6, 'FR-BPPK/MJG-SYS/VI/2018- Rev.00', 'RB', 0, 1, 0);
	$pdf->Ln(6);
	
	
 
$pdf->SetFont('','B');
$pdf->Ln(15);

$pdf->Output('Cetak_Pelunasan'. '.pdf', 'F');
$pdf->Output();
?>
