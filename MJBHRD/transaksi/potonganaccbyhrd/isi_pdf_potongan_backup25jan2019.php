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
			$this->Cell(158, 10.5, '', 0, 0, 'L', 0);
			$this->SetFont('Arial', 'B', 15);
			$this->Cell(30, 6, 'F-12', 1, 0, 'C', 0);
			$this->Ln(6);
			$this->SetFont('Arial', 'BU', 8);
			$this->Cell(200, 10, 'NIK :   '.$nik.'                    ', 0, 0, 'C', 90);	
			$this->Ln(8);
			$this->SetFont('Arial', 'B', 9);
			$this->Cell(200, 6, '', 0, 0, 'C', 0);					
			$this->Ln(6);
			$this->SetFont('Arial', 'B', 12);
			$this->Cell(10, 4, '', 0, 0, 'C', 0);						
			$this->Cell(180, 4, 'FORMULIR PERMOHONAN PINJAMAN', 0, 0, 'C', 0);						
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

$query = "
SELECT distinct PPF.MIDDLE_NAMES, PJ.NAME, PP.NAME, 
		TO_CHAR( PPF.EFFECTIVE_START_DATE, 'DD-MON-YYYY' ) TGL_MULAI_KERJA, HL.LOCATION_CODE
		, MTP.TUJUAN_PINJAMAN, MTP.NOMINAL * MTP.JUMLAH_CICILAN JUMLAH_PINJAMAN, MTP.NOMINAL, MTP.JUMLAH_CICILAN
		, PPF2.FULL_NAME MANAGER
		, PPF.FULL_NAME PEMINJAM
		, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'MONTH') bulan
		, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY') tahun
		, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'DD-MON-YYYY') tgl
		, PJ2.NAME, PP2.NAME
		, PJ2.NAME, PP2.NAME
		-- , TO_CHAR(MTP.CREATED_DATE, 'DD-MON-YYYY') tgl_pengajuan
		, NVL(to_char(MTA.CREATED_DATE, 'DD-MON-YYYY'), TO_CHAR(MTP.CREATED_DATE, 'DD-MON-YYYY')) tgl_app_mgr
		, DECODE( START_POTONGAN_BULAN, 1, ' Januari ', 2, ' Februari ', 3, ' Maret ', 4, ' April ', 5, ' Mei ', 6, ' Juni ',
					7, ' Juli ', 8, ' Agustus ', 9, ' September ', 10, ' Oktober ', 11, ' November ', 12, ' Desember ' ) bln_potongan
		, START_POTONGAN_TAHUN tahun_potongan
        , ( 
            SELECT LISTAGG( PHONE_NUMBER, ' / ' ) WITHIN GROUP ( ORDER BY PHONE_NUMBER )
            FROM APPS.PER_PHONES
            WHERE PARENT_ID = MTP.PERSON_ID
            AND SYSDATE BETWEEN DATE_FROM AND NVL( DATE_TO, SYSDATE )
          ) EMP_PHONE 
        , ( 
            SELECT LISTAGG( PHONE_NUMBER, ' / ' ) WITHIN GROUP ( ORDER BY PHONE_NUMBER )
            FROM APPS.PER_PHONES
            WHERE PARENT_ID = MTP.MANAGER
            AND SYSDATE BETWEEN DATE_FROM AND NVL( DATE_TO, SYSDATE )
            ) MGR_PHONE
FROM MJ.MJ_T_PINJAMAN MTP
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID =PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF2 ON PPF2.PERSON_ID =PAF2.PERSON_ID AND PAF2.EFFECTIVE_END_DATE > SYSDATE AND PAF2.PRIMARY_FLAG='Y'
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
LEFT JOIN APPS.PER_JOBS PJ2 ON PJ2.JOB_ID=PAF2.JOB_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
LEFT JOIN APPS.PER_POSITIONS PP2 ON PAF2.POSITION_ID=PP2.POSITION_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON  MTP.ID = MTA.TRANSAKSI_ID AND TRANSAKSI_KODE = 'PINJAMAN' AND MTA.TINGKAT = 0 
WHERE MTP.ID = $hdid
";

$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$initial=$row[0];
	$divisi=$row[1];
	$jabatan=$row[2];
	$mulai_kerja=$row[3];
	$lokasi=$row[4];
	$tujuan_pinjaman=$row[5];
	$jumlah_pinjaman=$row[6];
	$cicilan_perbulan=$row[7];
	$jumlah_cicilan=$row[8];
	$manager=$row[9];
	$peminjam=$row[10];
	$bulan=$row[11];
	$tahun=$row[12];
	$tgl_pengajuan=$row[13];
	$divisimgr=$row[14];
	$jabatanmgr=$row[15];
	// $tgl_pengajuan=$row[18];
	$tgl_app_mgr=$row[18];
	$bln_potongan=$row[19];
	$tahun_potongan=$row[20];
	
	$emp_phone=$row[21];
	$mgr_phone=$row[22];
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

$queryHRD = "
		SELECT 	DECODE(MTP.BYHRD, 'Y', PPF.FULL_NAME, PPF2.FULL_NAME) HRD 
				, DECODE(MTP.BYHRD, 'Y', TO_CHAR(MTP.CREATED_DATE, 'DD-MON-YYYY'), TO_CHAR(MTA.CREATED_DATE, 'DD-MON-YYYY')) tgl
				, '_' || REPLACE( MTP.NOMOR_PINJAMAN, '/', '_' ) NO_PINJAMAN_FILENAME
				, MTP.NOMOR_PINJAMAN NO_SURAT
		FROM MJ.MJ_T_PINJAMAN MTP
        INNER JOIN PER_PEOPLE_F PPF ON MTP.CREATED_BY = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
        LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTP.ID = MTA.TRANSAKSI_ID AND TRANSAKSI_KODE = 'PINJAMAN' AND MTA.TINGKAT = 2 AND MTA.STATUS = 'Approved'
        LEFT JOIN PER_PEOPLE_F PPF2 ON MTA.EMP_ID = PPF2.PERSON_ID AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
        WHERE MTP.ID = $hdid
		";

// LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTP.ID = MTA.TRANSAKSI_ID AND TRANSAKSI_KODE = 'PINJAMAN' AND MTA.TINGKAT = 1 

//echo $queryPemohon;

$resultHRD = oci_parse($con,$queryHRD);
oci_execute($resultHRD);
$rowHRD = oci_fetch_row($resultHRD);

$managerHRD = $rowHRD[0];	
$tglAppHRD = $rowHRD[1];		
$noPinjaman = $rowHRD[2];
$noSurat = $rowHRD[3];



	//$pdf->Image('approve-min.png',81,182,25);
	//$pdf->Image('approve-min.png',111,182,25);
	
		
	
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 6, 'Initial', 1, 0, 'L', 0);
	$pdf->Cell(57, 6, $initial, 1, 0, 'L', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(30, 6, 'No Surat', 1, 0, 'L', 0);
	
	$pdf->SetFont('Arial', 'B', 7.5);
	// $pdf->Cell(57, 6, $noSurat, 1, 0, 'L', 0);
	
	// $pdf->SetFont('Arial', '', 5);
	$pdf->Cell(60, 6, $noSurat, 1, 0, 'L', 0);
	
	
	$pdf->Ln(6);
	$pdf->SetFont('Arial', 'B', 7.5);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 6, 'Nama Karyawan', 'LTR', 0, 'C', 0);
	$pdf->Cell(57, 6, '', 'LR', 0, 'L', 0);
	$pdf->Cell(30, 9, 'Nama ATASAN', 'LTR', 0, 'L', 0);
	$pdf->Cell(60, 6, '', 'LTR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 0, 'yg mengajukan', 'LR', 0, 'C', 0);
	$pdf->Cell(57, 0, $peminjam, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 3, 'Pemohon', 'LR', 0, 'L', 0);
	
	// $pdf->Cell(60, 6, $manager, 'LR', 0, 'L', 0);
	
	$pdf->Cell(60, 0, $manager, 'LR', 0, 'L', 0);
	
	$pdf->Ln(0);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 7, 'Pinjaman', 'LR', 0, 'C', 0);
	$pdf->Cell(57, 6, '', 'LR', 0, 'L', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'L', 0);
	$pdf->Cell(60, 6, '', 'LR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7.5);
	$pdf->Cell(20, 6, 'Divisi / Jabatan', 'LTB', 0, 'L', 0);
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(13, 6, ' ( Pemohon )', 'RTB', 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 5);	
	$pdf->Cell(32, 6, $divisi, 1, 0, 'L', 0);
	$pdf->Cell(25, 6, $jabatan, 1, 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 7.5);
	$pdf->Cell(20, 12, 'Divisi / Jabatan', 'LTB', 0, 'L', 0);
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(10, 12, ' ( Atasan )', 'RTB', 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 5);
	$pdf->Cell(30, 12, $divisimgr, 'LTR', 0, 'L', 0);
	$pdf->Cell(30, 12, $jabatanmgr, 'LTR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7.5);	
	$pdf->Cell(33, 6, 'Tanggal Masuk MJG', 1, 0, 'L', 0);
	$pdf->Cell(57, 6, $mulai_kerja, 1, 0, 'L', 0);
	$pdf->Cell(30, 6, '', 0, 0, 'L', 0);
	$pdf->Cell(30, 6, '', 'LBR', 0, 'L', 0);
	$pdf->Cell(30, 6, '', 'LBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 6, 'Lokasi Kerja', 1, 0, 'L', 0);
	$pdf->SetFont('Arial', '', 6);	
	if($lokasi == 'Barata 46'){
		$pdf->SetFont('Arial', 'B', 7.5);
		$pdf->Cell(57, 6, 'Head Office '.$lokasi, 1, 0, 'L', 0);
	}else{
		$pdf->SetFont('Arial', 'B', 7.5);
		$pdf->Cell(57, 6, 'Plant Area '.$lokasi, 1, 0, 'L', 0);
	}
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(30, 6, 'No HP / WA Atasan', 1, 0, 'L', 0);
	
	// $pdf->Cell(30, 6, $mgr_phone, 'TBL', 1, 'L', 0);
	
	// $pdf->Cell(30, 6, $mgr_phone, 1, 0, 'L', 0);
	
	// $pdf->Cell(30, 6, $mgr_phone, 'TBL', 0, 'L', 0);
	
	$pdf->Cell(60, 6, $mgr_phone, 1, 0, 'L', 0);
	
	// $pdf->Cell(30, 6, '', 'TBL', 0, 'L', 0);
	// $pdf->Cell(30, 6, '', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 6, 'No HP / WA Pemohon', 1, 0, 'L', 0);
	
	// $pdf->SetFont('Arial', '', 5);
	
	// $pdf->Cell(57, 6, '', 1, 0, 'L', 0);
	
	$pdf->Cell(57, 6, $emp_phone, 1, 0, 'L', 0);
	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(30, 6, 'Tgl Pengajuan Pinjaman', 1, 0, 'L', 0);
	$pdf->Cell(30, 6, ''.$tgl_pengajuan, 'TBL', 0, 'L', 0);
	$pdf->Cell(30, 6, '', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(105, 6, 'ALASAN PERMOHONAN PINJAMAN', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(75, 6, '( di isi oleh Pemohon )', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	
	// $pdf->Cell(180, 18, $tujuan_pinjaman, 1, 0, 'C', 0);
	
	// $pdf->MultiCell(0, 4, $tujuan_pinjaman, 1, 0, 'C', 0);
	
	$pdf->MultiCell(180, 4, $tujuan_pinjaman, 1, 'L', 0);
	
	// $pdf->Ln(18);
	
	
	
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(90, 6, 'SISA PINJAMAN SEBELUMNYA', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(90, 6, '( di isi oleh Pemohon jika masih ada pinjaman )', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	
	
	$pdf->SetFont('Arial', '', 7);	
	
	// $pdf->Cell(90, 4, 'RP', 1, 0, 'C', 0);
	
	$pdf->Cell(30, 4, '', 'L', 0, 'C', 0);
	
	$pdf->Cell(60, 4, 'RP', 0, 0, 'R', 0);
	
	// $pdf->Cell(90, 4, 'RP', 1, 'C', 0);
	// $pdf->Cell(90, 4, 'Test', 1, 0, 'C', 0);
	//	$pdf->Cell(30, 6, 'Test', 'TBL', 'L', 0);
	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(90, 4, 'SISA ANGSURAN YANG MASIH BERJALAN', 1, 0, 'C', 0);
	$pdf->Ln(4);
	
	
	
	// $pdf->Cell(8, 8, 'Test', 0, 'L', 0);
	
	
	/*
	// Detail = 1
	
	$pdf->Image( 'approve-min.png', 20, 160 + 20 * $jumlahDetail, 25 );
	
	$pdf->Image( 'approve-min.png', 50.5, 160 + 20 * $jumlahDetail, 25 );
	
	$pdf->Image( 'approve-min.png', 171, 160 + 20 * $jumlahDetail, 25 );
	*/
	
	
	/*
	$pdf->Image('approve-min.png',20,182,25);
	
	$pdf->Image('approve-min.png',50.5,182,25);
	
	$pdf->Image('approve-min.png',171,182,25);
	*/

	

	$queryPinjaman = "
        (
			SELECT  MTP.TANGGAL_PINJAMAN, MTP.NOMOR_PINJAMAN,
					TRIM( TO_CHAR( 
						MTP.NOMINAL
						- ( 
							SELECT  NVL( SUM( NOMINAL ), 0 )
							FROM    MJ_T_PINJAMAN_DETAIL
							WHERE   MJ_T_PINJAMAN_ID = $hdid 
						  )
						, '999,999,999.99' ) ) NOMINAL,
					MTP.JUMLAH_CICILAN, 
                    TIPE
			FROM MJ.MJ_T_PINJAMAN MTP
			LEFT JOIN (
					SELECT MAX(ID) ID, TRANSAKSI_ID FROM
					MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN' AND STATUS = 'Approved' AND TINGKAT = 4
					GROUP BY TRANSAKSI_ID
				) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
			LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'    
			WHERE 1=1
			AND MTP.TIPE = 'PINJAMAN PERSONAL'
			AND MTP.STATUS_DOKUMEN = 'Validate'
			AND MTP.TINGKAT = 5
			AND MTP.JUMLAH_CICILAN > 0
			AND MTP.STATUS = 1
			AND MTP.ID <> $hdid  
			AND MTP.PERSON_ID = 
				(   SELECT  PERSON_ID
					FROM    MJ_T_PINJAMAN
					WHERE   ID =  $hdid 
				)
        )
        UNION
        (
			SELECT  MTP.TANGGAL_PINJAMAN, MTP.NOMOR_PINJAMAN,
					TRIM( TO_CHAR( 
						MTP.NOMINAL
						- ( 
							SELECT  NVL( SUM( NOMINAL ), 0 )
							FROM    MJ_T_PINJAMAN_DETAIL
							WHERE   MJ_T_PINJAMAN_ID = $hdid 
						  )
						, '999,999,999.99' ) ) NOMINAL,
					MTP.JUMLAH_CICILAN, 
                    TIPE
			FROM MJ.MJ_T_PINJAMAN MTP
			LEFT JOIN (
					SELECT MAX(ID) ID, TRANSAKSI_ID FROM
					MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN' AND STATUS = 'Approved' AND TINGKAT = 3
					GROUP BY TRANSAKSI_ID
				) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
			LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'    
			WHERE 1=1
			AND MTP.TIPE = 'PINJAMAN PENGGANTI INVENTARIS'
			AND MTP.STATUS_DOKUMEN = 'Approved'
			AND MTP.JUMLAH_CICILAN > 0
			AND MTP.TINGKAT = 4
			AND MTP.STATUS = 1
			AND MTP.ID <> $hdid 
			AND MTP.PERSON_ID = 
				(   SELECT  PERSON_ID
					FROM    MJ_T_PINJAMAN
					WHERE   ID =  $hdid 
				)
        )
		";
	
	/*
	$queryPinjaman = "
		SELECT  NOMOR_PINJAMAN,
				TRIM( TO_CHAR( NOMINAL * JUMLAH_CICILAN, '999,999,999.99' ) ) TOTAL_PINJAMAN,
				JUMLAH_CICILAN
		FROM    MJ_T_PINJAMAN
		WHERE   PERSON_ID = 
				(   SELECT  PERSON_ID
					FROM    MJ_T_PINJAMAN
					WHERE   ID =  $hdid )
		AND     TANGGAL_PINJAMAN < 
				(   SELECT  TANGGAL_PINJAMAN
					FROM    MJ_T_PINJAMAN
					WHERE   ID =  $hdid )
		";
	*/
	
	$resultPinjaman = oci_parse( $con, $queryPinjaman );
	oci_execute( $resultPinjaman );

	// $rowPinjaman = oci_fetch_row( $resultPinjaman );

	while ( $rowPinjaman = oci_fetch_row( $resultPinjaman ) )
	{
		
		$dtlTglPinjaman = $rowPinjaman[0];
		$dtlNoPinjaman = $rowPinjaman[1];
		$dtlNominal = $rowPinjaman[2];
		$dtlJmlCicilan = $rowPinjaman[3];
		$dtlTipePinjaman = $rowPinjaman[4];
		
		$pdf->Cell(8, 8, '', 0, 'L', 0);
		
		$pdf->SetFont('Arial', '', 7);
		
		// $pdf->Cell(90, 8, '', 1, 0, 'C', 0);
		// $pdf->Cell(90, 8, 'Test', 1, 0, 'C', 0);
		// $pdf->Cell(90, 4, $dtlTotalPinjaman, 1, 0, 'C', 0);
		
		
		// $pdf->Cell(45, 4, $dtlNoPinjaman, 1, 0, 'C', 0);
		
		$pdf->Cell(15, 4, $dtlTglPinjaman, 1, 0, 'C', 0);
		
		$pdf->Cell(45, 4, $dtlNoPinjaman, 1, 0, 'C', 0);
		
		$pdf->Cell(30, 4, $dtlNominal, 1, 0, 'R', 0);
		
		$pdf->Cell(50, 4, $dtlTipePinjaman, 1, 0, 'C', 0);
		
		$pdf->Cell(40, 4, $dtlJmlCicilan . '   Bulan', 1, 0, 'C', 0);
		
		// $pdf->Cell(90, 4, $dtlTotalPinjaman, 1, 'C', 0);
		
		
		// $pdf->SetFont('Arial', '', 7);
		
		// $pdf->Cell(90, 8, '________________ Bulan', 1, 0, 'C', 0);
		
		
		
		
		// $pdf->Cell(90, 4, $dtlJmlCicilan . '   Bulan', 1, 'C', 0);
		
		$pdf->Ln(4);
	}
	


	$queryCount = "
		SELECT  count( * )
		FROM    MJ_T_PINJAMAN
		WHERE   PERSON_ID = 
				(   SELECT  PERSON_ID
					FROM    MJ_T_PINJAMAN
					WHERE   ID =  $hdid )
		AND     TANGGAL_PINJAMAN < 
				(   SELECT  TANGGAL_PINJAMAN
					FROM    MJ_T_PINJAMAN
					WHERE   ID =  $hdid )	
	";
	
	$resultCount = oci_parse( $con, $queryCount );
	oci_execute( $resultCount );
	$rowCount = oci_fetch_row( $resultCount );

	$jumlahDetail = $rowCount[0];
	
	
	/*
	if ( $jumlahDetail == 0 ) {
		
		// Detail = 0
		
		$pdf->Image( 'approve-min.png', 20, 160, 25 );
		
		$pdf->Image( 'approve-min.png', 50.5, 160, 25 );
		
		$pdf->Image( 'approve-min.png', 171, 160, 25 );	
		
	} else {
		
		if ( $jumlahDetail == 1 ) {
			
			$pdf->Image( 'approve-min.png', 20, 166 - 3 * $jumlahDetail, 25 );
			
			$pdf->Image( 'approve-min.png', 50.5, 166 - 3 * $jumlahDetail, 25 );
			
			$pdf->Image( 'approve-min.png', 171, 166 - 3 * $jumlahDetail, 25 );
			
		} else {
			
			if ( $jumlahDetail == 2 ) {
				
				$pdf->Image( 'approve-min.png', 20, 182 - 3 * $jumlahDetail, 25 );
				
				$pdf->Image( 'approve-min.png', 50.5, 182 - 3 * $jumlahDetail, 25 );
				
				$pdf->Image( 'approve-min.png', 171, 182 - 3 * $jumlahDetail, 25 );
				
			} else {
				
				if ( $jumlahDetail == 3 ) {
					
					$pdf->Image( 'approve-min.png', 20, 180 - 3 * $jumlahDetail, 25 );
					
					$pdf->Image( 'approve-min.png', 50.5, 180 - 3 * $jumlahDetail, 25 );
					
					$pdf->Image( 'approve-min.png', 171, 180 - 3 * $jumlahDetail, 25 );
					
				}
				
			}
			
		}
		
	}
	*/
	
	
	// $pdf->Ln(8);
	
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'b', 7);
	$pdf->Cell(105, 6, 'KOLOM PENGAJUAN PINJAMAN BARU', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(75, 6, '( di isi oleh Pemohon )', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(60, 4, 'JUMLAH NOMINAL PINJAMAN YG DIAJUKAN', 'LTR', 0, 'C', 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(60, 4, 'JUMLAH ANGSURAN', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(60, 4, '( di isi oleh HR CnB Payroll )', 'TBR', 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 7);	
	$pdf->Cell(60, 4, '( di isi oleh Pemohon )', 'LBR', 0, 'C', 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(60, 4, 'DIANGSUR', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(60, 4, 'JUMLAH NOMINAL', 1, 0, 'C', 0);
	$pdf->Ln(4);
	$pdf->Cell(8, 10, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(60, 10, 'Rp. '.number_format($jumlah_pinjaman, 2, ',', '.'), 1, 0, 'L', 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(60, 10, '', 'LR', 0, 'C', 0);
	$pdf->Cell(60, 4, 'Setiap bulan akan dipotong dari gaji bulanan sebesar :', 'LR', 0, 'C', 0);
	$pdf->Cell(60, 6, '', 'LR', 0, 'C', 0);
	$pdf->Ln(10);
	$pdf->Cell(8, 10, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 7);	
	$pdf->Cell(60, 10, 'Terbilang : '.terbilang($jumlah_pinjaman), 1, 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(60, 0, $jumlah_cicilan.' X ( Kali )', 'LR', 0, 'C', 0);
	$pdf->Cell(1, 10, '', 'L', 0, 'C', 0);
	$pdf->Cell(59, -4, '      Rp. '.number_format($cicilan_perbulan, 2, ',', '.'), 'R', 0, 'L', 0);
	$pdf->Cell(0.00000001, 10, '', 'R', 0, 'L', 0);
	$pdf->Ln(10);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(180, 6, '', 'RTL', 0, 'R', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 7);
	
	// $pdf->Cell(180, 6, 'MULAI DIANGSUR BULAN                                '.'TAHUN    ', 'RBL', 0, 'C', 0);
	
	$pdf->Cell(180, 6, 'MULAI DIANGSUR BULAN  ' . $bln_potongan . ' TAHUN  ' . $tahun_potongan, 'RBL', 0, 'C', 0);
	
	$pdf->Ln(6);
	
	// $pdf->Cell(8, 1, '', 0, 0, 'L', 0);	
	// $pdf->Cell(30, 1, '', 'L', 0, 'L', 0);
	// $pdf->Cell(150, 1, '', 'R', 0, 'L', 0);
	// $pdf->Ln(1);
	
	
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(8, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 5, 'Diajukan Oleh :', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(30, 5, 'Menyetujui dan Mengetahui Oleh :', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(90, 5, 'Menyetujui', 1, 0, 'C', 0);
	$pdf->Cell(30, 5, 'Mengetahui', 1, 0, 'C', 0);
	$pdf->Ln(5);
	$pdf->Cell(8, 25, '', 0, 0, 'L', 0);
	
	/*
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);	
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	*/
	
	// $pdf->Cell(30, 25, 'Test', 1, 0, 'C', 0);
	
	// $pdf->Image( 'approve-min.png'
	
	/*
	$pdf->Cell(30, 25, 'Test 1', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 2', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 3', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 4', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 5', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 6', 1, 0, 'C', 0);
	*/

	// $pdf->Cell(30, 25, $pdf->Image( 'approve-min.png' ), 1, 0, 'C', 0);
	
	// $pdf->Image( 'approve-min.png', 20, 160, 25 );
	
	// $pdf->Image( 'approve-min.png', 20, $pdf->GetY(), 25 );
	
	// $pdf->Image($image1, 20, $pdf->GetY(), 25 );
	
	// $this->Cell( 40, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 33.78), 0, 0, 'L', false );
	
	
	$pdf->Cell( 30, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 2, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	$pdf->Cell( 30, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 2, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	
	$pdf->Cell( 30, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 2, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	
	/*
	$pdf->Cell(30, 25, 'Test 2', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 3', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 4', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 5', 1, 0, 'C', 0);
	$pdf->Cell(30, 25, 'Test 6', 1, 0, 'C', 0);
	*/
	
	$pdf->Ln(25);
	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 6, '', 'LR', 0, 'C', 0);
	$pdf->Ln(8);
	$pdf->Cell(8, -4, '', 0, 0, 'L', 0);	
	//$pdf->SetFont('Arial', 'I', 6);
	$pdf->Cell(30, -7, 'Yang Mengajukan', 0, 0, 'C', 0);
	$pdf->Cell(30, -7, 'Atasan yg Mengajukan', 0, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(30, -4, 'Admin Plant & atau HR Area', 0, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(30, -4, 'CEO/CFO/COO', 0, 0, 'C', 0);
	$pdf->Cell(30, -7, 'Finance', 0, 0, 'C', 0);
	$pdf->Cell(30, -4, 'HR Head Office', 0, 0, 'C', 0);
	$pdf->Ln(-2);
	$pdf->SetFont('Arial', 'I', 6);
	$pdf->Cell(8, 2, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 2, '(min. level Manager)', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 2, '(min. level Manager)', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 2, '', 'LR', 0, 'C', 0);
	$pdf->Ln(2);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 8, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_pengajuan, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    '.$tgl_app_mgr, 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    ', 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    ', 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    ', 'LR', 0, 'L', 0);
	$pdf->Cell(30, 8, 'Tgl :    '.$tglAppHRD, 'LR', 0, 'L', 0);
	$pdf->Ln(8);
	$pdf->Cell(8, 0, '', 0, 0, 'L', 0);	
	$pdf->Cell(30, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell(30, 0, '', 'LR', 0, 'C', 0);	
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(30, 4, ''.$managerHRD, 'LR', 0, 'C', 0);
	$pdf->Ln(0);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(30, 4, $peminjam, 'LRB', 0, 'C', 0);
	$pdf->Cell(30, 4, $manager, 'LRB', 0, 'C', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	$pdf->Cell(30, 4, '', 'LRB', 0, 'L', 0);
	
 
$pdf->SetFont('','B');
$pdf->Ln(15);


// $pdf->Output('Cetak_Potongan'. '.pdf', 'F');

// Nama file pdf diganti menjadi bentuk dinamis sesuai nomor dokumen. Perubahan oleh Yuke di 4 Oktober 2018.

$pdf->Output('Cetak_Potongan'. $noPinjaman . '.pdf', 'F');
$pdf->Output();

?>
