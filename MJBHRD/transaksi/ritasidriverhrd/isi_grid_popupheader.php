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
$plant = "";
$tglfrom = "";
$tglto = "";
$periode_awal = "";
$periode_akhir = "";
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
	$plant = $_GET['plant'];
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$periode = $_GET['periode'];
	$cb1 = $_GET['cb1'];
	$cb2 = $_GET['cb2'];
	$cb3 = $_GET['cb3'];
	$cb4 = $_GET['cb4'];
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
	if($cb3=='true'){
		if($plant != ''){
			$queryfilter .=" AND paf.location_id = $plant ";
		}
	}
	if($cb4=='true'){
		$periode_awal=substr($periode, 0, 10);
		$periode_akhir=substr($periode, 13, 24);
		
		// $tahun=substr($periode_awal, 0, 4);
		// $bulan=substr($periode_awal, 5, 2);
		// $hari=substr($periode_awal, 8, 2);
		// //echo $bulan;exit;
		// //$periode_awal = $tahun . "-" . $bulan . "-" . $hari;
		// $hari=substr($periode_akhir, 0, 2);
		// $bulan=substr($periode_akhir, 3, 2);
		// $tahun=substr($periode_akhir, 6, 2);
		// if (strlen($tahun)==2)
		// {
			// $tahun = $tahunbaru . "" . $tahun;
		// }
		//$periode_akhir = $tahun . "-" . $bulan . "-" . $hari;
		
		$queryfilter .=" AND TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$periode_awal' AND TO_CHAR(TRD.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	}
} 

$query = "SELECT TRD.ID
, TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') EFFECTIVE_START_DATE
, TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') EFFECTIVE_END_DATE
, TRD.NAMA_DRIVER
, PPF.FULL_NAME 
FROM MJ.MJ_T_RITASI_DRIVER TRD
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRD.NAMA_DRIVER AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
inner join apps.per_assignments_f paf on ppf.person_id = paf.person_id 
WHERE SYSDATE between paf.EFFECTIVE_start_DATE  and paf.EFFECTIVE_END_DATE 
and paf.primary_flag = 'Y' 
and TRD.STATUS = 'Save' and TRD.EFFECTIVE_START_DATE IS NOT NULL
$queryfilter
";
// echo $query;
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