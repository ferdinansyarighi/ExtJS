<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

$hdid = $_GET['hdid'];
$hrd_id = $_GET['hrd_id'];
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
		FROM MJ.MJ_T_BS BS
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		WHERE 1=1 AND BS.ID = $hdid";
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
			$this->Cell(180, 4, 'FORMULIR PERMOHONAN BON SEMENTARA', 0, 0, 'C', 0);						
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
SELECT DISTINCT PPF.MIDDLE_NAMES, BS.NO_BS, PPF.FULL_NAME PEM, PPF2.FULL_NAME PJ, PJ.NAME DEPT_PEM, PP.NAME POS_PEM, PJ2.NAME DEPT_PJ, PP2.NAME POS_PJ
    , TO_CHAR( PPF.ORIGINAL_DATE_OF_HIRE, 'DD-MON-YYYY' ) TGL_MULAI_KERJA,  HL.LOCATION_CODE
     , ( 
            SELECT LISTAGG( PHONE_NUMBER, ' / ' ) WITHIN GROUP ( ORDER BY PHONE_NUMBER )
            FROM APPS.PER_PHONES
            WHERE PARENT_ID = PPF.PERSON_ID
            AND SYSDATE BETWEEN DATE_FROM AND NVL( DATE_TO, SYSDATE )
          ) PHONE_PEM 
	, ( 
		SELECT LISTAGG( PHONE_NUMBER, ' / ' ) WITHIN GROUP ( ORDER BY PHONE_NUMBER )
		FROM APPS.PER_PHONES
		WHERE PARENT_ID = BS.PENANGGUNG_JAWAB
		AND SYSDATE BETWEEN DATE_FROM AND NVL( DATE_TO, SYSDATE )
		) PHONE_PJ
    , BS.KETERANGAN, BS.NOMINAL
    , TO_CHAR(BS.CREATED_DATE, 'DD-MON-YYYY') TGL_PENGAJUAN
    , NVL(to_char(MTA.CREATED_DATE, 'DD-MON-YYYY'), TO_CHAR(BS.CREATED_DATE, 'DD-MON-YYYY')) TGL_APP_MGR
    , MTA2.CREATED_BY APP_CKR
    , NVL(to_char(MTA2.CREATED_DATE, 'DD-MON-YYYY'), TO_CHAR(BS.CREATED_DATE, 'DD-MON-YYYY')) TGL_APP_CKR
	, HOU2.NAME, TO_CHAR(BS.TGL_JT, 'DD - Mon - YYYY')
	, BS.SYARAT1, BS.SYARAT2, BS.SYARAT3, BS.SYARAT4, BS.SYARAT5
	, MTA3.CREATED_BY APP_CKR
    , NVL(to_char(MTA3.CREATED_DATE, 'DD-MON-YYYY'), TO_CHAR(BS.CREATED_DATE, 'DD-MON-YYYY')) TGL_APP_CKR
	, MTA4.CREATED_BY APP_CKR
    , NVL(to_char(MTA4.CREATED_DATE, 'DD-MON-YYYY'), TO_CHAR(BS.CREATED_DATE, 'DD-MON-YYYY')) TGL_APP_CKR
	, BS.TIPE_PENCAIRAN || '   ' || BS.NO_REK TIPE_PENCAIRAN
    , BS.TIPE, BS.TINGKAT
            FROM MJ.MJ_T_BS BS
            INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
            INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
            INNER JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
            INNER JOIN APPS.PER_ASSIGNMENTS_F PAF2 ON PPF2.PERSON_ID =PAF2.PERSON_ID AND PAF2.EFFECTIVE_END_DATE > SYSDATE AND PAF2.PRIMARY_FLAG='Y'
            INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
            INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
            INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
            INNER JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
            INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
            INNER JOIN APPS.PER_JOBS PJ2 ON PJ2.JOB_ID=PAF2.JOB_ID
            INNER JOIN APPS.PER_POSITIONS PP2 ON PAF2.POSITION_ID=PP2.POSITION_ID
            LEFT JOIN MJ.MJ_T_APPROVAL MTA ON  BS.ID = MTA.TRANSAKSI_ID AND MTA.TRANSAKSI_KODE = 'BON' AND MTA.TINGKAT = 2 AND MTA.STATUS = 'Approved'
            LEFT JOIN MJ.MJ_T_APPROVAL MTA2 ON  BS.ID = MTA2.TRANSAKSI_ID AND MTA2.TRANSAKSI_KODE = 'BON' AND MTA2.TINGKAT = 3 AND MTA2.STATUS = 'Approved'
            LEFT JOIN MJ.MJ_T_APPROVAL MTA3 ON  BS.ID = MTA3.TRANSAKSI_ID AND MTA3.TRANSAKSI_KODE = 'BON' AND MTA3.TINGKAT = 4 AND MTA3.STATUS = 'Approved'
            LEFT JOIN MJ.MJ_T_APPROVAL MTA4 ON  BS.ID = MTA4.TRANSAKSI_ID AND MTA4.TRANSAKSI_KODE = 'BON' AND MTA4.TINGKAT = 5 AND MTA4.STATUS = 'Approved'
				WHERE BS.ID =  $hdid
";

$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$initial=$row[0];
	$no_bs=$row[1];
	$pem=$row[2];
	$pj=$row[3];
	$dept_pem=$row[4];
	$pos_pem=$row[5];
	$dept_pj=$row[6];
	$pos_pj=$row[7];
	$tgl_mulai_kerja=$row[8];
	$lokasi=$row[9];
	$phone_pem=$row[10];
	$phone_pj=$row[11];
	$keterangan=$row[12];
	$nominal=$row[13];
	$tgl_pengajuan=$row[14];
	$tgl_app_mgr=$row[15];
	$nama_ckr=$row[16];
	$tgl_app_ckr=$row[17];
	$perusahaan_bs=$row[18];
	$tglJt=$row[19];
	$syarat1=$row[20];
	$syarat2=$row[21];
	$syarat3=$row[22];
	$syarat4=$row[23];
	$syarat5=$row[24];
	$nama_hrd=$row[25];
	$tgl_app_hrd=$row[26];
	$nama_fin=$row[27];
	$tgl_app_fin=$row[28];
	
	$tipe_pencairan=$row[29];
	$tipe_bs=$row[30];
	
	$tingkat=$row[31];
	
}

/* $queryTgl = "SELECT TO_CHAR(SYSDATE, 'DD') || ' ' || 
TRIM(TO_CHAR(SYSDATE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || 
TO_CHAR(SYSDATE, 'YYYY') TGL 
FROM dual"; */
$queryTgl = "SELECT TO_CHAR(SYSDATE, 'DD-MON-YYYY') TGL FROM dual";
//echo $queryPemohon;
$resultTgl = oci_parse($con,$queryTgl);
oci_execute($resultTgl);
$rowTgl = oci_fetch_row($resultTgl);
$tglskr = $rowTgl[0];

$queryHrd = "SELECT FULL_NAME FROM PER_PEOPLE_F WHERE CURRENT_EMPLOYEE_FLAG = 'Y' AND EFFECTIVE_END_DATE > SYSDATE AND PERSON_ID = $hrd_id";
//echo $queryPemohon;
$resultHrd = oci_parse($con,$queryHrd);
oci_execute($resultHrd);
$rowHrd = oci_fetch_row($resultHrd);
$hrd = $rowHrd[0];

//$hrd_id
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
	$pdf->Cell(60, 6, $no_bs, 1, 0, 'L', 0);
	
	
	$pdf->Ln(6);
	$pdf->SetFont('Arial', 'B', 7.5);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 6, 'Nama Karyawan', 'LTR', 0, 'C', 0);
	$pdf->Cell(57, 6, '', 'LR', 0, 'L', 0);
	//$pdf->Cell(30, 9, 'Nama ATASAN', 'LTR', 0, 'L', 0);
	$pdf->Cell(30, 6, 'Perusahaan BS', 1, 0, 'L', 0);
	$pdf->Cell(60, 6, $perusahaan_bs, 1, 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 0, 'yg mengajukan', 'LR', 0, 'C', 0);
	$pdf->Cell(57, 0, $pem, 'LR', 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(30, 6, 'Nama Atasan Pemohon', 'LR', 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 7.5);
	$pdf->Cell(60, 6, $pj, 'LR', 0, 'L', 0);
	
	$pdf->Ln(0);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 7, 'Bon Sementara', 'LR', 0, 'C', 0);
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
	$pdf->Cell(32, 6, $dept_pem, 1, 0, 'L', 0);
	$pdf->Cell(25, 6, $pos_pem, 1, 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 7.5);
	$pdf->Cell(20, 12, 'Divisi / Jabatan', 'LTB', 0, 'L', 0);
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(10, 12, ' ( Atasan )', 'RTB', 0, 'L', 0);
	$pdf->SetFont('Arial', 'B', 5);
	$pdf->Cell(30, 12, $dept_pj, 'LTR', 0, 'L', 0);
	$pdf->Cell(30, 12, $pos_pj, 'LTR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7.5);	
	$pdf->Cell(33, 6, 'Tanggal Masuk MJG', 1, 0, 'L', 0);
	$pdf->Cell(57, 6, $tgl_mulai_kerja, 1, 0, 'L', 0);
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
	
	$pdf->Cell(60, 6, $phone_pj, 1, 0, 'L', 0);
	
	// $pdf->Cell(30, 6, '', 'TBL', 0, 'L', 0);
	// $pdf->Cell(30, 6, '', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(33, 6, 'No HP / WA Pemohon', 1, 0, 'L', 0);
	
	// $pdf->SetFont('Arial', '', 5);
	
	// $pdf->Cell(57, 6, '', 1, 0, 'L', 0);
	
	$pdf->Cell(57, 6, $phone_pem, 1, 0, 'L', 0);
	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(30, 6, 'Tgl Pengajuan BS', 1, 0, 'L', 0);
	$pdf->Cell(30, 6, ''.$tgl_pengajuan, 'TBL', 0, 'L', 0);
	$pdf->Cell(30, 6, '', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell(105, 6, 'ALASAN PERMOHONAN BON SEMENTARA', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(75, 6, '( di isi oleh Pemohon )', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	
	// $pdf->Cell(180, 18, $tujuan_pinjaman, 1, 0, 'C', 0);
	
	// $pdf->MultiCell(0, 4, $tujuan_pinjaman, 1, 0, 'C', 0);
	
	$pdf->MultiCell(180, 4, $keterangan, 1, 'L', 0);
	
	// $pdf->Ln(18);
	
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->MultiCell(180, 4, 'Tipe Pencairan: ' . $tipe_pencairan, 1, 'L', 0);	
	
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(90, 6, 'SISA BON SEMENTARA SEBELUMNYA', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(90, 6, '( di isi oleh Pemohon jika masih ada pinjaman )', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	
	
	$pdf->SetFont('Arial', '', 7);	
	
	// $pdf->Cell(90, 4, 'RP', 1, 0, 'C', 0);
	
	//$pdf->Cell(45, 4, '', 'L', 0, 'C', 0);
	
	$pdf->Cell(90, 4, 'RP', 1, 0, 'C', 0);
	
	// $pdf->Cell(90, 4, 'RP', 1, 'C', 0);
	// $pdf->Cell(90, 4, 'Test', 1, 0, 'C', 0);
	//	$pdf->Cell(30, 6, 'Test', 'TBL', 'L', 0);
	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(90, 4, 'NOMOR BS', 1, 0, 'C', 0);
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
		SELECT  NO_BS,
				TRIM( TO_CHAR( NOMINAL, '999,999,999.99' ) ) TOTAL_PINJAMAN
		FROM    MJ_T_BS
		WHERE   ASSIGNMENT_ID = 
				(   SELECT  ASSIGNMENT_ID
					FROM    MJ_T_BS
					WHERE   ID =  $hdid )
		AND     CREATED_DATE < 
				(   SELECT  CREATED_DATE
					FROM    MJ_T_BS
					WHERE   ID =  $hdid )	
		AND STATUS != 'CLOSE'
		AND TGL_PENCAIRAN IS NOT NULL
		";

	$resultPinjaman = oci_parse( $con, $queryPinjaman );
	oci_execute( $resultPinjaman );

	// $rowPinjaman = oci_fetch_row( $resultPinjaman );
	$x =0;
	while ( $rowPinjaman = oci_fetch_row( $resultPinjaman ) )
	{
		
		$dtlNoPinjaman = $rowPinjaman[0];
		$dtlTotalPinjaman = $rowPinjaman[1];
		//$dtlJmlCicilan = $rowPinjaman[2];
		
		$pdf->Cell(8, 8, '', 0, 'L', 0);
		
		$pdf->SetFont('Arial', '', 7);
		
		$pdf->Cell(90, 4, $dtlTotalPinjaman, 1, 0, 'C', 0);
		
		$pdf->SetFont('Arial', '', 7);
		
		$pdf->Cell(90, 4, $dtlNoPinjaman, 1, 0, 'C', 0);
		
		$pdf->Ln(4);
		
		$x++;
	}
	if($x == 0){
		$pdf->Cell(8, 8, '', 0, 'L', 0);
		
		$pdf->SetFont('Arial', '', 7);
		
		$pdf->Cell(90, 4, '-', 1, 0, 'C', 0);
		
		$pdf->SetFont('Arial', '', 7);
		
		$pdf->Cell(90, 4, '-', 1, 0, 'C', 0);
		
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
	
	
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'b', 7);
	$pdf->Cell(105, 6, 'KOLOM PENGAJUAN BON SEMENTARA BARU', 'TBL', 0, 'R', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(75, 6, '( di isi oleh Pemohon )', 'TBR', 0, 'L', 0);
	$pdf->Ln(6);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 6);	
	$pdf->Cell(60, 4, 'JUMLAH NOMINAL BON SEMENTARA YG DIAJUKAN', 'LTR', 0, 'C', 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(120, 4, 'KETERANGAN', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 7);
	//$pdf->Cell(60, 4, '( di isi oleh HR CnB Payroll )', 'TBR', 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 7);	
	$pdf->Cell(60, 4, '( di isi oleh Pemohon )', 'LBR', 0, 'C', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(120, 5, '1. Bon Sementara ini wajib diselesaikan tanggal :  '. $tglJt, 'LTR', 0, 'L', 0);
	//$pdf->SetFont('Arial', 'B', 7);
	//$pdf->Cell(60, 4, 'JUMLAH NOMINAL', 1, 0, 'C', 0);
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 4, '', 'LTR', 0, 'L', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(120, 4, '2. Penyelesaian Bon Semnetara ini wajib disertai : ', 'LR', 0, 'L', 0);
	
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', 'B', 7);	
	$pdf->Cell(60, 4, 'Rp. '.number_format($nominal, 2, ',', '.'), 'LR', 0, 'L', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(120, 4, '    a. '.$syarat1, 'LR', 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 4, '', 'LR', 0, 'L', 0);
	$pdf->Cell(120, 4, '    b. '.$syarat2, 'LR', 0, 'L', 0);
	
	$terbilang = terbilang($nominal);
	if(strlen($terbilang) > 38){
		$terbilang1 = substr($terbilang,0,38).'-';
		$terbilang2 = substr($terbilang,38,49).'-';
		$terbilang3 = substr($terbilang,87);
	}else{
		$terbilang1 = $terbilang;
		$terbilang2 = '';
		$terbilang3 = '';
	} 
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 4, 'Terbilang : '.$terbilang1, 'LTR', 0, 'L', 0);
	$pdf->Cell(120, 4, '    c. '.$syarat3, 'LR', 0, 'L', 0);
	
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 4, $terbilang2, 'LR', 0, 'L', 0);
	$pdf->Cell(120, 4, '    d. '.$syarat4, 'LR', 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->Cell(60, 4, $terbilang3, 'LRB', 0, 'L', 0);
	$pdf->Cell(120, 4, '    e. '.$syarat5, 'LRB', 0, 'L', 0);
	
	
	$pdf->Ln(3);
	$pdf->Cell(8, 10, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 7);
	
	// $pdf->Cell(180, 10, 'BS PELUNASAN BULAN  ' . '_________________' . ' TAHUN  ' . '_________________', 'RBL', 0, 'C', 0);

	$pdf->Cell(60, 10, 'Tipe BS: ' . $tipe_bs, 'LB', 0, 'L', 0);
	
	$pdf->Cell(120, 10, 'BS PELUNASAN BULAN  ' . '_________________' . ' TAHUN  ' . '_________________', 'RB', 0, 'L', 0);
		
	$pdf->Ln(10);
	
	// $pdf->Cell(8, 1, '', 0, 0, 'L', 0);	
	// $pdf->Cell(30, 1, '', 'L', 0, 'L', 0);
	// $pdf->Cell(150, 1, '', 'R', 0, 'L', 0);
	// $pdf->Ln(1);
	
	
	/*
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
	*/

	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(8, 5, '', 0, 0, 'L', 0);	
	
	$pdf->Cell( 25.7, 5, 'Diajukan Oleh :', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell( 25.7, 5, 'Menyetujui Oleh :', 1, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell( 77.1, 5, 'Menyetujui', 1, 0, 'C', 0);
	$pdf->Cell( 25.7, 5, 'Mengetahui', 1, 0, 'C', 0);
	$pdf->Cell( 25.8, 5, 'Penerima BS', 1, 0, 'C', 0);
	
	$pdf->Ln(5);
	
	$pdf->Cell(8, 25, '', 0, 0, 'L', 0);

	
	$pdf->Cell( 25.7, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 1, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	$pdf->Cell( 25.7, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 1, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	$pdf->Cell( 25.7, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 1, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	//$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	//$pdf->Cell(30, 25, '', 1, 0, 'C', 0);
	
	
	// $pdf->Cell( 25.7, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 1, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	// $pdf->Cell( 25.7, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 1, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	$pdf->Cell( 25.7, 25, '', 1, 0, 'L', false );
	$pdf->Cell( 25.7, 25, '', 1, 0, 'L', false );
	
	$pdf->Cell( 25.7, 25, $pdf->Image( 'approve-min.png', $pdf->GetX() + 1, $pdf->GetY() + 5, 25 ), 1, 0, 'L', false );
	
	$pdf->Cell( 25.8, 25, '', 1, 0, 'C', 0);
	
	$pdf->Ln(25);
	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 6, '', 0, 0, 'L', 0);	
	$pdf->Cell( 25.7, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 6, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.8, 6, '', 'LR', 0, 'C', 0);
	
	$pdf->Ln(8);
	
	$pdf->Cell(8, -4, '', 0, 0, 'L', 0);
	
	
	$pdf->Cell( 25.7, -7, 'Yang Mengajukan', 0, 0, 'C', 0);
	$pdf->Cell( 25.7, -7, 'Atasan yg Mengajukan', 0, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell( 25.7, -4, 'Checker', 0, 0, 'C', 0);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell( 25.7, -4, 'CEO/CFO/COO', 0, 0, 'C', 0);
	$pdf->Cell( 25.7, -7, 'Finance', 0, 0, 'C', 0);
	$pdf->Cell( 25.7, -4, 'HR Head Office', 0, 0, 'C', 0);
	$pdf->Cell( 25.8, -4, 'Penerima BS', 0, 0, 'C', 0);
	
	$pdf->Ln(-2);
	
	$pdf->SetFont('Arial', 'I', 6);
	$pdf->Cell(8, 2, '', 0, 0, 'L', 0);	
	$pdf->Cell( 25.7, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 2, '(min. level Manager)', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 2, '(min. level Manager)', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 2, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.8, 2, '', 'LR', 0, 'C', 0);
	$pdf->Ln(2);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(8, 8, '', 0, 0, 'L', 0);	
	$pdf->Cell( 25.7, 8, 'Tgl :    '.$tgl_pengajuan, 'LR', 0, 'L', 0);
	$pdf->Cell( 25.7, 8, 'Tgl :    '.$tgl_app_mgr, 'LR', 0, 'L', 0);
	$pdf->Cell( 25.7, 8, 'Tgl :    '.$tgl_app_ckr, 'LR', 0, 'L', 0);

	$pdf->Cell( 25.7, 8, 'Tgl :    ', 'LR', 0, 'L', 0);
	$pdf->Cell( 25.7, 8, 'Tgl :    ', 'LR', 0, 'L', 0);
	
	// $pdf->Cell( 25.7, 8, 'Tgl :    '.$tgl_app_hrd, 'LR', 0, 'L', 0);
	// $pdf->Cell( 25.7, 8, 'Tgl :    '.$tgl_app_fin, 'LR', 0, 'L', 0);
	
	$pdf->Cell( 25.7, 8, 'Tgl :    '.$tgl_app_hrd, 'LR', 0, 'L', 0);
	$pdf->Cell( 25.8, 8, '', 'LR', 0, 'L', 0);
	$pdf->Ln(8);
	$pdf->Cell(8, 0, '', 0, 0, 'L', 0);	
	$pdf->Cell( 25.7, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 0, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.7, 0, '', 'LR', 0, 'C', 0);	
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell( 25.7, 4, '', 'LR', 0, 'C', 0);
	$pdf->Cell( 25.8, 4, '', 'LR', 0, 'C', 0);
	
	$pdf->Ln(0);
	$pdf->Cell(8, 4, '', 0, 0, 'L', 0);	
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell( 25.7, 4, $pem, 'LRB', 0, 'C', 0);
	$pdf->Cell( 25.7, 4, $pj, 'LRB', 0, 'C', 0);
	$pdf->Cell( 25.7, 4, $nama_ckr, 'LRB', 0, 'C', 0);
	
	$pdf->Cell( 25.7, 4, '', 'LRB', 0, 'C', 0);
	$pdf->Cell( 25.7, 4, '', 'LRB', 0, 'C', 0);
	
	// $pdf->Cell( 25.7, 4, $nama_hrd, 'LRB', 0, 'C', 0);
	// $pdf->Cell( 25.7, 4, $nama_fin, 'LRB', 0, 'C', 0);
	
	$pdf->Cell( 25.7, 4, $nama_hrd, 'LRB', 0, 'C', 0);
	$pdf->Cell( 25.8, 4, '', 'LRB', 0, 'C', 0);
 
$pdf->SetFont('','B');
$pdf->Ln(15);

$pdf->Output('Cetak_BS.pdf', 'F');
$pdf->Output();

?>
