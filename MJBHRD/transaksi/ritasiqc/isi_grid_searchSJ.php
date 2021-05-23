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
$data = "";
if (isset($_GET['tffilter_awal']) || isset($_GET['tfcari']) || isset($_GET['cb1']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$tffilter_awal = $_GET['tffilter_awal'];
	$tffilter_akhir = $_GET['tffilter_akhir'];
	$tfcari = str_replace("'", "''", $_GET['tfcari']);
	
	$queryfilter .=" AND TO_CHAR(TRQ.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tffilter_awal' AND TO_CHAR(TRQ.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tffilter_akhir' ";
	
	if($tfcari != ''){
		$queryfilter .=" AND MSB.SURAT_JALAN_NO LIKE '%$tfcari%' ";
	}
} 

$query = "SELECT MSB.SURAT_JALAN_NO
, PPF.FULL_NAME NAMA_QC
, MSB.NOTES
, TRQD.NOMOR_LHO
, TRQ.STATUS
FROM MJ.MJ_T_RITASI_QC TRQ
INNER JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQ.ID = TRQD.MJ_T_RITASI_QC_ID
INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRQD.SJ_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRQ.NAMA_QC AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
WHERE 1=1
$queryfilter
";
// echo $query;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['DATA_NOSJ']=$row[0];
	$record['DATA_NAMA']=$row[1];
	$record['DATA_NOTE']=$row[2];
	$record['DATA_LHO']=$row[3];
	$record['DATA_STATUS']=$row[4];
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