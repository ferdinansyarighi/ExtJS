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
	$cbplant = str_replace("'", "''", $_GET['cbplant']);
	
	if($tffilter_awal!=''){
		$queryfilter .=" AND TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tffilter_awal' AND TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tffilter_akhir' ";
	}
	if($tfcari != ''){
		$queryfilter .=" AND MSB.SURAT_JALAN_NO||MSB.NOTES LIKE '%$tfcari%' ";
	}
	if($cbplant != ''){
		$queryfilter .=" And paf.location_id = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = $cbplant )";
	}
} 

$query = "SELECT MSB.SURAT_JALAN_NO
, PPF.FULL_NAME NAMA_QC
, MSB.NOTES
, TRDD.NOMOR_LHO
, TRD.STATUS
, TRD.EFFECTIVE_START_DATE || ' - ' || TRD.EFFECTIVE_END_DATE PERIODE 
FROM mj.mj_t_ritasi_driver TRD
INNER JOIN mj.mj_t_ritasi_driver_detail TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRD.NAMA_DRIVER 
inner join apps.per_assignments_f paf on ppf.person_id = paf.person_id 
where PPF.EFFECTIVE_END_DATE > SYSDATE and SYSDATE between paf.EFFECTIVE_start_DATE  and paf.EFFECTIVE_END_DATE 
and paf.primary_flag = 'Y' 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
$queryfilter
order by MSB.NOTES
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
	$record['DATA_PERIODE']=$row[5];
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