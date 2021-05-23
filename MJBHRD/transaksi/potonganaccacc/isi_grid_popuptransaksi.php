<?php
include '../../main/koneksi.php';
session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$tffilter = "";
$tglfrom = "";
$tglto = "";
$cb1 = "";
$cb2 = "";
if (isset($_GET['tffilter']) || isset($_GET['tglfrom']) || isset($_GET['cb1']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$tffilter = $_GET['tffilter'];
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$cb1 = $_GET['cb1'];
	$cb2 = $_GET['cb2'];
	if ($cb1=='true'){
		$queryfilter .=" AND UPPER(NOMOR_PINJAMAN) LIKE '%$tffilter%' ";
	}
	if ($cb2=='true'){
		$hari=substr($tglfrom, 0, 2);
		$bulan=substr($tglfrom, 3, 2);
		$tahun=substr($tglfrom, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
		$hari=substr($tglto, 0, 2);
		$bulan=substr($tglto, 3, 2);
		$tahun=substr($tglto, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tglto = $tahun . "-" . $bulan . "-" . $hari;
		
		$queryfilter .=" AND TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	}
} 


$query = "
(
SELECT DISTINCT MTP.ID
        , MTP.NOMOR_PINJAMAN
        , PPF.FULL_NAME
        , TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
        , MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
        , MTP.JUMLAH_CICILAN
        , MTP.TUJUAN_PINJAMAN
        , TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
        , MTP.STATUS
        , MTP.PERSON_ID
        , MTP.NOMINAL NC
        , MTP.KETERANGAN_MGR
        , MTP.KETERANGAN
        , MTP.KETERANGAN_DIR
        , MTP.TIPE
        , PPF2.FULL_NAME
        , DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
                                7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
                    ||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
        , D.ORGANIZATION_ID, MTP.TIPE_PENCAIRAN, MTP.NOMOR_REKENING
FROM MJ.MJ_T_PINJAMAN MTP
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
INNER JOIN MJ.MJ_M_APPROVAL_PINJAMAN MMP ON MMP.PERUSAHAAN_ID = D.ORGANIZATION_ID
WHERE MTP.STATUS_DOKUMEN <> 'Approved'
AND D.PRIMARY_FLAG = 'Y'
AND D.EFFECTIVE_END_DATE > SYSDATE
-- AND D.ORGANIZATION_ID = $org_id    -- :ORG_ID
-- AND MMP.PERUSAHAAN_ID = $org_id    -- :ORG_ID
AND NVL( MMP.ACCOUNTING_ID, $emp_id ) = $emp_id    -- :ORG_ID
AND MTP.TINGKAT = 3
AND MTP.STATUS = 1
AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
AND MMP.TIPE = 'Pinjaman'
AND MMP.STATUS = 'A'
$queryfilter
)
UNION
(
SELECT DISTINCT MTP.ID
        , MTP.NOMOR_PINJAMAN
        , PPF.FULL_NAME
        , TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
        , MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
        , MTP.JUMLAH_CICILAN
        , MTP.TUJUAN_PINJAMAN
        , TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
        , MTP.STATUS
        , MTP.PERSON_ID
        , MTP.NOMINAL NC
        , MTP.KETERANGAN_MGR
        , MTP.KETERANGAN
        , MTP.KETERANGAN_DIR
        , MTP.TIPE
        , PPF2.FULL_NAME
        , DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
                                7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
                    ||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
        , D.ORGANIZATION_ID, MTP.TIPE_PENCAIRAN, MTP.NOMOR_REKENING
FROM MJ.MJ_T_PINJAMAN MTP
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
WHERE MTP.STATUS_DOKUMEN <> 'Approved'
AND D.PRIMARY_FLAG = 'Y'
AND D.EFFECTIVE_END_DATE > SYSDATE
AND MTP.TINGKAT = 3
AND MTP.STATUS = 1
AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
AND NOT EXISTS (
    SELECT  1
    FROM    MJ_M_APPROVAL_PINJAMAN
    WHERE   PERUSAHAAN_ID = D.ORGANIZATION_ID
    AND     TIPE = 'Pinjaman'
    AND     STATUS = 'A'
    )
$queryfilter
)
ORDER BY NOMOR_PINJAMAN
";


// echo $query; exit;

$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$statusRecord = $row[8];
	if($statusRecord == 1){
		$statusRecord = "AKTIF";
	} else {
		$statusRecord = "NON AKTIF";
	}
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_PINJAMAN']=$row[1];
	$record['DATA_PERSON']=$row[2];
	$record['DATA_TGL']=$row[3];
	$record['DATA_NOMINAL']=number_format($row[4], 2, ',', '.');
	$record['DATA_NOMINAL_HIDE']=$row[4];
	$record['DATA_JML_C']=$row[5];
	$record['DATA_TUJUAN']=$row[6];
	$record['DATA_TGL_BUAT']=$row[7];
	$record['DATA_STATUS']=$statusRecord;
	$record['DATA_PERSON_ID']=$row[9];
	$record['DATA_NC']=number_format($row[10], 2, ',', '.');
	$record['DATA_KET_MGR']=$row[11];
	$record['DATA_KET_HRD']=$row[12];
	$record['DATA_KET_DIR']=$row[13];
	$record['DATA_TIPE']=$row[14];
	$record['DATA_MGR']=$row[15];
	$record['DATA_START_POTONGAN']=$row[16];

	$record['DATA_TIPE_PENCAIRAN']=$row[18];
	$record['DATA_NOMOR_REKENING']=$row[19];
	
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}

$totalrow = 0;


/*
// Jika tidak dilakukan setting pada ORG_ID tersebut di master approval pinjaman dan BS

$checkExist = oci_parse($con, "
				SELECT  COUNT( * )
				FROM    MJ_M_APPROVAL_PINJAMAN
				WHERE   TIPE = 'Pinjaman'
				AND     STATUS = 'A'
				AND     PERUSAHAAN_ID = $org_id
			");
oci_execute( $checkExist );
$rowCheckExist = oci_fetch_row( $checkExist );
$jumlahExist = $rowCheckExist[0];



// Jika dilakukan setting pada ORG_ID tersebut di master approval pinjaman dan BS, namun HRD_ID kosong

$checkExistPerson = oci_parse($con, "
				SELECT  ACCOUNTING_ID
				FROM    MJ_M_APPROVAL_PINJAMAN
				WHERE   TIPE = 'Pinjaman'
				AND     STATUS = 'A'
				AND     PERUSAHAAN_ID = $org_id
			");
oci_execute( $checkExistPerson );
$rowCheckExistPerson = oci_fetch_row( $checkExistPerson );
$jumlahExistPerson = $rowCheckExistPerson[0];


// Jika tidak dilakukan setting PIC HRD approver pada ORG_ID seusai login, maka tampilkan semua transaksi yang dapat di-approve

if ( $jumlahExist == 0 || $jumlahExistPerson == '' ) {

	$query = "
	SELECT DISTINCT MTP.ID
			, MTP.NOMOR_PINJAMAN
			, PPF.FULL_NAME
			, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
			, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
			, MTP.JUMLAH_CICILAN
			, MTP.TUJUAN_PINJAMAN
			, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
			, MTP.STATUS
			, MTP.PERSON_ID
			, MTP.NOMINAL NC
			, MTP.KETERANGAN_MGR
			, MTP.KETERANGAN
			, MTP.KETERANGAN_DIR
			, MTP.TIPE
			, PPF2.FULL_NAME
			, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
									7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
						||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
	FROM MJ_T_PINJAMAN MTP
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	WHERE MTP.STATUS_DOKUMEN <> 'Approved'
	AND MTP.TINGKAT = 3
	AND MTP.STATUS = 1
	AND JENIS_PINJAMAN = 'PINJAMAN'
	$queryfilter
	ORDER BY NOMOR_PINJAMAN
	";
	
	// echo $query; exit;
	
	$result = oci_parse($con,$query);
	oci_execute($result);

	$count = 0;
	while($row = oci_fetch_row($result))
	{
		$statusRecord = $row[8];
		if($statusRecord == 1){
			$statusRecord = "AKTIF";
		} else {
			$statusRecord = "NON AKTIF";
		}
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_PINJAMAN']=$row[1];
		$record['DATA_PERSON']=$row[2];
		$record['DATA_TGL']=$row[3];
		$record['DATA_NOMINAL']=number_format($row[4], 2, ',', '.');
		$record['DATA_NOMINAL_HIDE']=$row[4];
		$record['DATA_JML_C']=$row[5];
		$record['DATA_TUJUAN']=$row[6];
		$record['DATA_TGL_BUAT']=$row[7];
		$record['DATA_STATUS']=$statusRecord;
		$record['DATA_PERSON_ID']=$row[9];
		$record['DATA_NC']=number_format($row[10], 2, ',', '.');
		$record['DATA_KET_MGR']=$row[11];
		$record['DATA_KET_HRD']=$row[12];
		$record['DATA_KET_DIR']=$row[13];
		$record['DATA_TIPE']=$row[14];
		$record['DATA_MGR']=$row[15];
		$record['DATA_START_POTONGAN']=$row[16];
		
		$data[]=$record;
		$count++;
	}
	
	if ($count==0)
	{
		$data="";
	}
	
	$totalrow = 0;
	 
} else {
	
	$query = "
	SELECT DISTINCT MTP.ID
			, MTP.NOMOR_PINJAMAN
			, PPF.FULL_NAME
			, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
			, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
			, MTP.JUMLAH_CICILAN
			, MTP.TUJUAN_PINJAMAN
			, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
			, MTP.STATUS
			, MTP.PERSON_ID
			, MTP.NOMINAL NC
			, MTP.KETERANGAN_MGR
			, MTP.KETERANGAN
			, MTP.KETERANGAN_DIR
			, MTP.TIPE
			, PPF2.FULL_NAME
			, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
									7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
						||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
	FROM MJ_T_PINJAMAN MTP
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	WHERE MTP.STATUS_DOKUMEN <> 'Approved'
	AND MTP.TINGKAT = 3
	AND MTP.STATUS = 1
	AND JENIS_PINJAMAN = 'PINJAMAN'
	AND EXISTS
		(
			SELECT  1
			FROM    MJ_M_APPROVAL_PINJAMAN
			WHERE   TIPE = 'Pinjaman'
			AND     STATUS = 'A'
			AND     PERUSAHAAN_ID = $org_id    --  0  81  201
			AND     ACCOUNTING_ID = $emp_id    -- 3309
		)
	$queryfilter
	ORDER BY NOMOR_PINJAMAN
	";
	
	// echo $query; exit;
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	
	$count = 0;
	while($row = oci_fetch_row($result))
	{
		$statusRecord = $row[8];
		if($statusRecord == 1){
			$statusRecord = "AKTIF";
		} else {
			$statusRecord = "NON AKTIF";
		}
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_PINJAMAN']=$row[1];
		$record['DATA_PERSON']=$row[2];
		$record['DATA_TGL']=$row[3];
		$record['DATA_NOMINAL']=number_format($row[4], 2, ',', '.');
		$record['DATA_NOMINAL_HIDE']=$row[4];
		$record['DATA_JML_C']=$row[5];
		$record['DATA_TUJUAN']=$row[6];
		$record['DATA_TGL_BUAT']=$row[7];
		$record['DATA_STATUS']=$statusRecord;
		$record['DATA_PERSON_ID']=$row[9];
		$record['DATA_NC']=number_format($row[10], 2, ',', '.');
		$record['DATA_KET_MGR']=$row[11];
		$record['DATA_KET_HRD']=$row[12];
		$record['DATA_KET_DIR']=$row[13];
		$record['DATA_TIPE']=$row[14];
		$record['DATA_MGR']=$row[15];
		$record['DATA_START_POTONGAN']=$row[16];
		
		$data[]=$record;
		$count++;
	}
	
	if ($count==0)
	{
		$data="";
	}
	
	$totalrow = 0;
	
}
*/


$result = array('success' => true,
				'results' => $totalrow,
				'rows' => $data
			);
echo json_encode($result);

?>