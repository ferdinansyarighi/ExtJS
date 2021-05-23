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
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRQ.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRQ.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND TRL.PERSON_ID = '$nama'";
	}
	if($plant!=''){
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '$plant')";
	}
} 

$query = "SELECT PPF.PERSON_ID
, PPF.FULL_NAME
, SUM(SUM_VOLUM) VOLUM
, SUM(TRL.LEMBUR) LEMBUR
, NVL(N.NOMINAL, 0) NOMINAL
, NVL(K.KEKURANGAN, 0) KEKURANGAN
, (NVL(N.NOMINAL, 0) + SUM(TRL.LEMBUR) + NVL(K.KEKURANGAN, 0)) TOTAL 
FROM MJ.MJ_T_RITASI_LEMBUR TRL
INNER JOIN MJ.MJ_T_RITASI_QC TRQ ON TRQ.ID = TRL.TRANSAKSI_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRL.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN 
(
    SELECT TRLQ.TRANSAKSI_ID, SUM((TRLQ.SUM_VOLUM * TRLQ.NOMINAL)) as NOMINAL 
    FROM MJ.MJ_T_RITASI_LEMBUR TRLQ 
        INNER JOIN MJ.MJ_T_RITASI_QC TRQ1 ON TRLQ.TRANSAKSI_ID = TRQ1.ID
    WHERE TRLQ.STATUS='Y' AND TRLQ.TGL >= TRQ1.EFFECTIVE_START_DATE
    --AND TRLQ.TGL <= TRQ1.EFFECTIVE_END_DATE
    GROUP BY TRLQ.TRANSAKSI_ID
) N ON N.TRANSAKSI_ID=TRQ.ID
LEFT JOIN 
(
    SELECT TRLQ.TRANSAKSI_ID, SUM((TRLQ.SUM_VOLUM * TRLQ.NOMINAL) + TRLQ.LEMBUR) as KEKURANGAN 
    FROM MJ.MJ_T_RITASI_LEMBUR TRLQ 
        INNER JOIN MJ.MJ_T_RITASI_QC TRQ1 ON TRLQ.TRANSAKSI_ID = TRQ1.ID
    WHERE TRLQ.STATUS='Y' AND TRLQ.TGL < TRQ1.EFFECTIVE_START_DATE
    GROUP BY TRLQ.TRANSAKSI_ID
) K ON K.TRANSAKSI_ID=TRQ.ID
WHERE TRL.STATUS = 'Y'
$queryfilter
GROUP BY PPF.FULL_NAME, N.NOMINAL, PPF.PERSON_ID, K.KEKURANGAN
ORDER BY PPF.FULL_NAME
";
// echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['DATA_NAMA']=$row[1];
	$record['DATA_VOLUM']=$row[2];
	$record['DATA_LEMBUR']=$row[3];
	$record['DATA_NOMINAL']=$row[4];
	$record['DATA_KEKURANGAN']=$row[5];
	$record['DATA_TOTAL']=$row[6];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>