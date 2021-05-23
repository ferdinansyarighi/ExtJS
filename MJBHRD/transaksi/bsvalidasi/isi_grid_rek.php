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
 
/*
$tffilter = "";
$tglfrom = "";
$tglto = "";
$cb1 = "";
$cb2 = "";
if (isset($_GET['tffilter']) || isset($_GET['tglfrom']) || isset($_GET['cb1']))
{
	
} 
*/

/*
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
		, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL_TF
		, MTP.TIPE
		, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
								7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
					||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
FROM MJ_T_PINJAMAN MTP
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID
WHERE MTP.STATUS_DOKUMEN = 'Approved'
AND MTP.TINGKAT = 4
AND MTP.STATUS = 1
AND JENIS_PINJAMAN = 'PINJAMAN'
AND TIPE = 'PINJAMAN PERSONAL'
$queryfilter
ORDER BY TGL_PINJAMAN, NOMOR_PINJAMAN
";
*/

$tffilter = "";

if ( isset( $_GET['tffilter']) )
{
	$tffilter = $_GET['tffilter'];
	$queryfilter = " AND     CBA.BANK_ACCOUNT_NUM  LIKE '%$tffilter%' ";
}

/*
$query = "
SELECT  CBA.BANK_ACCOUNT_NAME,
        BB.BANK_NAME,
        CBA.BANK_ACCOUNT_NUM,
        CBA.BANK_ACCOUNT_ID, 
        BAU.ORG_ID
FROM    APPS.CE_BANK_ACCOUNTS CBA,
        APPS.CE_BANK_ACCT_USES_ALL BAU,
        APPS.CEFV_BANK_BRANCHES BB
WHERE   CBA.BANK_ACCOUNT_ID = BAU.BANK_ACCOUNT_ID
AND     CBA.BANK_BRANCH_ID = BB.BANK_BRANCH_ID
AND     ( CBA.END_DATE IS NULL OR CBA.END_DATE > TRUNC (SYSDATE) )
AND     CBA.BANK_ACCOUNT_NAME = 'PT. Merak Jaya Beton'
$queryfilter
ORDER   BY BB.BANK_NAME, CBA.BANK_ACCOUNT_NUM
";
*/

$query = "
SELECT  CBA.BANK_ACCOUNT_NAME,
        BB.BANK_NAME,
        CBA.BANK_ACCOUNT_NUM,
        CBA.BANK_ACCOUNT_ID, 
        BAU.ORG_ID
FROM    APPS.CE_BANK_ACCOUNTS CBA,
        APPS.CE_BANK_ACCT_USES_ALL BAU,
        APPS.CEFV_BANK_BRANCHES BB
WHERE   CBA.BANK_ACCOUNT_ID = BAU.BANK_ACCOUNT_ID(+)
AND     CBA.BANK_BRANCH_ID = BB.BANK_BRANCH_ID
AND     ( CBA.END_DATE IS NULL OR CBA.END_DATE > TRUNC (SYSDATE) )
AND     CBA.BANK_ACCOUNT_ID
IN (
53008,
72007,
57007,
56007,
58007,
98008,
53010,
73007,
25002,
57009,
42002,
12001,
45002,
54008,
31002,
98007,
57008,
12002
)
$queryfilter
ORDER   BY BB.BANK_NAME, CBA.BANK_ACCOUNT_NUM
";

//echo $query;

$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	
	$record = array();
	$record['BANK_ACCOUNT_NAME']=$row[0];
	$record['BANK_NAME']=$row[1];
	$record['BANK_ACCOUNT_NUM']=$row[2];
	$record['BANK_ACCOUNT_ID']=$row[3];
	
	$data[]=$record;
	$count++;
}
if ($count==0)
{
	$data="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>