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
		$query = "SELECT MTM.NO_REQUEST
		FROM MJ.MJ_T_MUTASI MTM
		WHERE 1=1 AND MTM.ID = $hdid";
		$result = oci_parse($con,$query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$xNoReq=$row[0];
		}
		
		$query = "SELECT PPF.EMPLOYEE_NUMBER
		FROM MJ.MJ_T_MUTASI MTM
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.KARYAWAN_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
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
		
		/*
		if($this->PageNo() == 1)
		{
			//$this->Cell(30, 10.5, '', 1, 0, 'L', 0);	
			//$this->Cell(30, 10.5, '', 1, 0, 'L', 0);	
			
			$this->Ln(6);
			$this->Image('logo_mjg.png',20,20,35);
			$this->Cell(158, 10.5, '', 0, 0, 'L', 0);
			$this->SetFont('Arial', 'B', 15);
			$this->Cell(30, 6, 'F-02', 1, 0, 'C', 0);
			$this->Ln(6);
			$this->SetFont('Arial', 'BU', 8);
			$this->Cell(200, 10, 'NIK :   '.$nik.'                    ', 0, 0, 'C', 90);	
			$this->Ln(8);
			$this->SetFont('Arial', 'B', 9);
			$this->Cell(200, 6, 'FORMULIR USULAN PERUBAHAN STATUS KARYAWAN', 0, 0, 'C', 0);					
			$this->Ln(6);
			$this->SetFont('Arial', 'B', 12);
			$this->Cell(200, 4, 'FPSK', 0, 0, 'C', 0);						
			$this->Ln(5);
		}	
		*/
		
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

$query = "SELECT MTM.ID
, MTM.NO_REQUEST
, MTM.TIPE
, PPF1.FULL_NAME PEMBUAT
, PPF.FULL_NAME KARYAWAN
, PJ.NAME DEPT_LAMA
, PP.NAME POSISI_LAMA
, HL.LOCATION_CODE LOKASI_LAMA
, MTM.GAJI_LAMA
, PJ1.NAME DEPT_BARU
, PP1.NAME POSISI_BARU
, HL1.LOCATION_CODE LOKASI_BARU
, MTM.GAJI_BARU
, MTM.KETERANGAN
, MTM.ALASAN
, MTM.DEPT_LAMA_ID
, MTM.DEPT_BARU_ID
, PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME DIREKSI
, TO_CHAR(MTM.TGL_EFFECTIVE, 'DD') || ' ' || TRIM(TO_CHAR(MTM.TGL_EFFECTIVE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || TO_CHAR(MTM.TGL_EFFECTIVE, 'YYYY') TGL
, PPF.PERSON_ID
, PPF.MIDDLE_NAMES
, PP.POSITION_ID
, PPF.EMPLOYEE_NUMBER
, TO_CHAR(PPF.EFFECTIVE_START_DATE, 'DD') || ' ' || TRIM(TO_CHAR(PPF.EFFECTIVE_START_DATE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || TO_CHAR(PPF.EFFECTIVE_START_DATE, 'YYYY') TGL_MASUK
, MTM.ALASAN
, MTM.TIPE
, HOU.NAME PERUSAHAAN_LAMA
, HOU1.NAME PERUSAHAAN_BARU
, REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) DIVISI_LAMA
, REGEXP_SUBSTR(PP1.NAME, '[^.]+', 1, 3) DIVISI_BARU
, PG.NAME GRADE_LAMA
, PG1.NAME GRADE_BARU
, TO_CHAR(MTM.TGL_EFFECTIVE, 'DD') || ' ' || TRIM(TO_CHAR(MTM.TGL_EFFECTIVE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || TO_CHAR(MTM.TGL_EFFECTIVE, 'YYYY') TGL_EFEKTIF
, MTM.GAJI_LAMA
, MTM.GAJI_BARU
, MTM.NO_REQUEST
, MTM.KETERANGAN
, PPF3.FIRST_NAME || ' ' || PPF3.LAST_NAME MGR_LAMA
, PPF4.FIRST_NAME || ' ' || PPF4.LAST_NAME MGR_BARU
, PPF5.FIRST_NAME || ' ' || PPF5.LAST_NAME DIREKSI
, to_char(MTA.CREATED_DATE, 'DD-MM-YYYY') TGL_APPR_MGR_LAMA
, to_char(MTA2.CREATED_DATE, 'DD-MM-YYYY') TGL_APPR_MGR_BARU
, to_char(MTA3.CREATED_DATE, 'DD-MM-YYYY') TGL_APPR_HRD
, MTM.STATUS_KARYAWAN
, MTM.SIFAT_PERUBAHAN
, MTM.JUMLAH_BULAN
, PAF.PAYROLL_ID
FROM MJ.MJ_T_MUTASI MTM
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.KARYAWAN_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.CREATED_BY AND PPF1.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.DIREKSI AND PPF2.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF3 ON PPF3.PERSON_ID = MTM.MGR_LAMA AND PPF3.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF4 ON PPF4.PERSON_ID = MTM.MGR_BARU AND PPF4.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_PEOPLE_F PPF5 ON PPF5.PERSON_ID = MTM.DIREKSI AND PPF5.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_GRADES PG ON MTM.GRADE_LAMA_ID = PG.GRADE_ID 
INNER JOIN APPS.PER_GRADES PG1 ON MTM.GRADE_BARU_ID = PG1.GRADE_ID 
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID 
LEFT JOIN APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
LEFT JOIN APPS.PER_POSITIONS PP1 ON PP1.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN APPS.HR_LOCATIONS HL1 ON HL1.LOCATION_ID = MTM.LOKASI_BARU_ID 
LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PP.ORGANIZATION_ID = HOU.ORGANIZATION_ID
LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU1 ON PP1.ORGANIZATION_ID = HOU1.ORGANIZATION_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTM.ID = MTA.TRANSAKSI_ID AND MTA.TRANSAKSI_KODE = 'MPD' 
    AND MTA.STATUS = 'Approved' AND 1 = MTA.TINGKAT AND MTM.MGR_LAMA = MTA.EMP_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA2 ON MTM.ID = MTA2.TRANSAKSI_ID AND MTA2.TRANSAKSI_KODE = 'MPD' 
    AND MTA2.STATUS = 'Approved' AND 2 = MTA2.TINGKAT AND MTM.MGR_BARU = MTA2.EMP_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA3 ON MTM.ID = MTA3.TRANSAKSI_ID AND MTA3.TRANSAKSI_KODE = 'MPD' 
    AND MTA3.STATUS = 'Approved' AND 3 = MTA3.TINGKAT AND MTA3.TINGKAT = 3  
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
WHERE 1=1 AND MTM.ID = $hdid
AND PAF.PAYROLL_ID IS NULL";

$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$hd_id=$row[0];
	$xNoReq=$row[1];
	$xTipe=$row[2];
	$xPembuat=$row[3];
	$xKaryawan=$row[4];
	$xDeptLama=$row[5];
	$xPosisiLama=$row[6];
	$xLokasiLama=$row[7];
	$xGajiLama=$row[8];
	$xDept=$row[9];
	$xPosisi=$row[10];
	$xLokasi=$row[11];
	$xGaji=$row[12];
	$xKeterangan=$row[13];
	$xAlasan=$row[14];
	$xDeptLamaID=$row[15];
	$xDeptBaruID=$row[16];
	$xDireksi=$row[17];
	$xTglEfektif=$row[18];
	$person_id=$row[19];
	$initial=$row[20];
	$position_id=$row[21];
	$nik=$row[22];
	$tgl_masuk=$row[23];
	$alasan=$row[24];
	$tipe=$row[25];
	if($tipe == 'Mutasi'){
		$mutasi = ' v ';
	}else if($tipe == 'Promosi'){
		$promosi = ' v ';
	}else if($tipe == 'Demosi'){
		$demosi = ' v ';
	}
	$perusahaan_lama=$row[26];
	$perusahaan_baru=$row[27];
	$divisi_lama=$row[28];
	$divisi_baru=$row[29];
	$pangkat_lama=$row[30];
	$pangkat_baru=$row[31];
	$tgl_efektif=$row[32];
	$gaji_lama=$row[33];
	$gaji_baru=$row[34];
	$no_fpsk=$row[35];
	$keterangan=$row[36];
	$mgr_lama=$row[37];
	$mgr_baru=$row[38];
	$direksi=$row[39];
	$tgl_appr_mgr_lama=$row[40];
	$tgl_appr_mgr_baru=$row[41];
	$tgl_appr_hrd=$row[42];
	$status_karyawan=$row[43];
	if($status_karyawan == 'TETAP'){
		$skTetap = ' v ';
	}else if($status_karyawan == 'PERCOBAAN'){
		$skPercobaan = ' v ';
	}else if($status_karyawan == 'PJS'){
		$skPJS = ' v ';
	}else if($status_karyawan == 'KONTRAK'){
		$skKontrak = ' v ';
	}else if($status_karyawan == 'KONTRAK KHUSUS'){
		$skKhusus = ' v ';
	}
	$sifat_perubahan=$row[44];
	if($sifat_perubahan == 'TETAP'){
		$spTetap = ' v ';
	}else if($sifat_perubahan == 'SEMENTARA'){
		$spSementara = ' v ';
		$lama_bulan='      '.$row[45].'    ';
	}
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

	
	$pdf->Cell(30, 10, '', 0, 0, 'C', 0);
	$pdf->Ln(6);
	$pdf->Cell(158, 10.5, '', 0, 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 15);
	$pdf->Cell(30, 6, 'F-03', 1, 0, 'C', 0);
	$pdf->Ln(6);
	$pdf->Image('logo_mjg.png',20,20,35);
	
	$pdf->Image('approve-min.png',20,187,25);
	$pdf->Image('approve-min.png',50.5,187,25);
	$pdf->Image('approve-min.png',81,187,25);
	$pdf->Image('approve-min.png',111,187,25);
	$pdf->Image('approve-min.png',171,187,25);
	
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(200, 10, 'NIK :   '.$nik.'                    ', 0, 0, 'C', 90);	
	$pdf->Ln(8);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(200, 6, 'FORMULIR USULAN PERUBAHAN STATUS KARYAWAN', 0, 0, 'C', 0);					
	$pdf->Ln(6);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(200, 4, 'FPSK', 0, 0, 'C', 0);						
	$pdf->Ln(5);	
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(120, 6, '', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Lampiran Pendapatan', 1, 0, 'C', 0);
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, 'INITIAL DAN ID', 1, 0, 'L', 0);
	$pdf->Cell(30, 6, $initial, 1, 0, 'L', 0);
	$pdf->Cell(30, 6, $person_id, 1, 0, 'L', 0);
	$pdf->Cell(30, 6, 'No. FPSK', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $no_fpsk, 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, 'Nama Karyawan', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $xKaryawan, 1, 0, 'L', 0);
	$pdf->Cell(30, 6, 'Tanggal Efektif', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $tgl_efektif, 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, 'Lokasi Asal Rekrut', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $xLokasiLama, 1, 0, 'L', 0);
	$pdf->Cell(30, 6, 'Tanggal Masuk', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $tgl_masuk, 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, 'Home Base', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, '', 1, 0, 'L', 0);
	$pdf->Cell(30, 6, 'Tanggal Aktual', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $tglskr, 1, 0, 'L', 0);
	$pdf->Ln(6);
	
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(8, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 5, 'Keterangan Perubahan :  '.$keterangan, 1, 0, 'L', 0);
	$pdf->Ln(5);
	
	$pdf->Cell(8, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 5, '', 1, 0, 'L', 0);
	$pdf->Cell(60, 5, 'LAMA', 1, 0, 'C', 0);
	$pdf->Cell(60, 5, 'BARU', 1, 0, 'C', 0);
	$pdf->Ln(5);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Golongan', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, '', 1, 0, 'C', 0);
	$pdf->Cell(60, 6, '', 1, 0, 'C', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Gaji Gross', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp. '.number_format($gaji_lama, 2, ',', '.'), 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp. '.number_format($gaji_baru, 2, ',', '.'), 1, 0, 'L', 0);

	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Gaji Pokok', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Tunj. Jabatan', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Tunj. Transport', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Tunj. Makan', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Tunj. Penempatan Sementara', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 6, 'Tunj. Mutasi', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Rp.', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(27, 6, 'System Penggajian Ikut ', 'L', 0, 'L', 0);
	$pdf->Cell(33, 6, '( Coret yg tidak perlu )', 'R', 0, 'L', 0);
	$pdf->Cell(60, 6, 'Head Office / Plant_________________________', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, 'Head Office / Plant_________________________', 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 12, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 12, 'Lain-lain', 1, 0, 'L', 0);
	$pdf->Cell(60, 12, '', 1, 0, 'C', 0);
	$pdf->Cell(60, 12, '', 1, 0, 'C', 0);
	$pdf->Ln(14);
	
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 6, 'Alasan Perusahaan :  '.$alasan, 'RTL', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 19, '', 0, 0, 'L', 0);	
	$pdf->Cell(180, 19, '', 'RLB', 0, 'L', 0);
	$pdf->Ln(21);
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(8, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 5, 'Diusulkan Oleh :', 1, 0, 'C', 0);
	$pdf->Cell(90, 5, 'Menyetujui', 1, 0, 'C', 0);
	$pdf->Cell(30, 5, 'Mengetahui', 1, 0, 'C', 0);
	$pdf->Ln(5);
	$pdf->Cell(8, 25, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Ln(25);
	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, 'Atasan dari', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, 'Atasan dari', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Ln(8);
	$pdf->Cell(8, -4, '', 0, 0, 'L', 0);	
	//$pdf->SetFont('Arial', 'I', 6);
	$pdf->Cell(30, -7, 'Atasan Lama', 0, 0, 'C', 0);
	$pdf->Cell(30, -7, 'Atasan Baru', 0, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(30, -4, 'Atasan Langsung', 0, 0, 'C', 0);
	$pdf->Cell(30, -4, 'Atasan Langsung', 0, 0, 'C', 0);
	$pdf->Cell(30, -4, 'CEO/CFO/COO', 0, 0, 'C', 0);
	$pdf->Cell(30, -4, 'HR Head Office', 0, 0, 'C', 0);
	$pdf->Ln(-2);
	$pdf->SetFont('Arial', 'I', 6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, '(min. level Manager)', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '(min. level Manager)', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '(PT. Lama)', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '(PT. Tujuan)', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 8, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_appr_mgr_lama, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_appr_mgr_baru, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_appr_mgr_lama, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_appr_mgr_baru, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    ', 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_appr_hrd, 'LR', 0, 'L', 0);
	$pdf->Ln(8);
	$pdf->Cell(8, 0, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 0, ''.$mgr_lama, 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, ''.$mgr_baru, 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, ''.$mgr_lama, 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, ''.$mgr_baru, 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, ''.$direksi, 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, ''.$managerHRD, 'LR', 0, 'C', 0);
	$pdf->Ln(0);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Ln(6);
 
$pdf->SetFont('','B');
$pdf->Ln(15);


// Nama file pdf diganti menjadi bentuk dinamis sesuai nomor dokumen. Perubahan oleh Yuke di 4 Oktober 2018.

// $pdf->Output('Mutasi_F-03_Non_Payroll'. '.pdf', 'F');


$query_noreq = "
	SELECT  '_' || REPLACE( NO_REQUEST, '/', '_' ) NO_REQUEST_FILENAME
	FROM    MJ_T_MUTASI
	WHERE   ID = $hdid
";

$result_noreq = oci_parse( $con, $query_noreq );
oci_execute( $result_noreq );
$row_noreq = oci_fetch_row( $result_noreq );

$vNoReq = $row_noreq[0];

$pdf->Output( 'Mutasi_F-03_Non_Payroll' . $vNoReq . '.pdf', 'F' );


$pdf->Output();
?>
