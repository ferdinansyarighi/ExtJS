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
	$tffilter = strtoupper($_GET['tffilter']);
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$cb1 = $_GET['cb1'];
	$cb2 = $_GET['cb2'];
	if ($cb1=='true'){
		if($tffilter != ''){
			$queryfilter .=" AND upper(PPF.FULL_NAME) LIKE '%$tffilter%' ";
		}
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
		
		$queryfilter .=" AND TO_CHAR(TRD.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(TRD.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	}
} 

$query = "SELECT TRD.ID
, TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') EFFECTIVE_START_DATE
, TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') EFFECTIVE_END_DATE
, TRD.NAMA_DRIVER
, PPF.FULL_NAME 
FROM MJ.MJ_T_RITASI_DRIVER TRD
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRD.NAMA_DRIVER AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
WHERE TRD.STATUS = 'Save'
$queryfilter
";
 //echo $query;exit;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_PERIOD_AWAL']=$row[1];
	$record['DATA_PERIOD_AKHIR']=$row[2];
	$record['DATA_NAMA_QC_ID']=$row[3];
	$record['DATA_NAMA_QC']=$row[4];
	$data[]=$record;
	$count++;
}
if ($count==0)
{
	$data[]="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>