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
  
$status = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$tingkat = 0;
$queryfilter = "";
$jamInSplit = "";
$jamOutSplit = "";
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND PPF.PERSON_ID = '$nama'";
	}
	if($plant!=''){
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '$plant')";
	}
} 

$query = "SELECT PPF.PERSON_ID
, PPF.FULL_NAME
, TRLD.TGL
, TRLD.RITASI_KE
, TRLD.NOMOR_LHO
, TRLD.VARIABEL
, CASE WHEN TRLD.TIPE = 'BP' THEN SUM(TRLD.VARIABEL * TRLD.NOMINAL)
ELSE SUM(TRLD.NOMINAL) END NOMINAL
, NVL(TRLD.JAM_IN, '-') JAM_IN
, NVL(TRLD.JAM_OUT, '-') JAM_OUT
, SUM(TRLD.LEMBUR) LEMBUR
, SUM(TRLD.UANG_MAKAN) UANG_MAKAN
, CASE WHEN TRLD.TIPE = 'BP' THEN SUM(TRLD.VARIABEL * TRLD.NOMINAL)
ELSE SUM(TRLD.NOMINAL) END + SUM(TRLD.LEMBUR) + SUM(TRLD.UANG_MAKAN) TOTAL
FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD ON TRD.ID = TRLD.TRANSAKSI_ID
LEFT JOIN APPS.HZ_PARTIES HP ON HP.PARTY_ID = TRLD.CUSTOMER_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRLD.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE  AND PAF.PRIMARY_FLAG = 'Y'
WHERE TRLD.STATUS = 'Y'
$queryfilter
GROUP BY PPF.FULL_NAME, TRLD.NOMINAL, PPF.PERSON_ID, TRLD.TGL, TRLD.RITASI_KE, TRLD.NOMOR_LHO, TRLD.VARIABEL
, TRLD.TIPE, TRLD.JAM_IN, TRLD.JAM_OUT
ORDER BY PPF.FULL_NAME, TRLD.TGL, TRLD.NOMOR_LHO
";
// echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	if($row[7] != '-'){
		$jamInSplit = explode(":",$row[7]);
		$jamOutSplit = explode(":",$row[8]);
		if($jamOutSplit[1] >= $jamInSplit[1]){
			$totalMenit = $jamOutSplit[1] - $jamInSplit[1];
			$totalJam = $jamOutSplit[0] - $jamInSplit[0];
		} else {
			$totalMenit = $jamOutSplit[1] - $jamInSplit[1] + 60;
			$totalJam = $jamOutSplit[0] - $jamInSplit[0] - 1;
		}
	} else {
		$totalJam = 0;
		$totalMenit = 0;
	}
	
	$jamTotal = $totalJam + ($totalMenit / 60);
	$jamTotal = round($jamTotal, 2);
	
	//echo "Seluruh IN : ". $row[6] .", Seluruh OUT : " . $row[7] . ", Jam Out : " . $jamOutSplit[0] . ", Jam In : " . $jamInSplit[0] . ", Menit Out : " . $jamOutSplit[1] . ", Menit In : " . $jamInSplit[1] . ", Total Jam : ". $totalJam .", Total Menit : " . $totalMenit;
	
	$record = array();
	$record['DATA_NAMA']=$row[1];
	$record['DATA_TGL']=$row[2];
	$record['DATA_RITASI_KE']=$row[3];
	$record['DATA_LHO']=$row[4];
	$record['DATA_VARIABEL']=$row[5];
	$record['DATA_NOMINAL']=$row[6];
	$record['DATA_JAM']=$jamTotal;
	$record['DATA_LEMBUR']=$row[9];
	$record['DATA_UM']=$row[10];
	$record['DATA_TOTAL']=$row[11];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>