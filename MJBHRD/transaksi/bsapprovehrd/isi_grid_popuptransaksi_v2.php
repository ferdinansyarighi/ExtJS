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
$query = '';
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
		$queryfilter .=" AND UPPER(BS.NO_BS) LIKE '%$tffilter%' ";
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
		
		$queryfilter .=" AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	}
} 
// $query = "SELECT COUNT(-1) 
// FROM MJ.MJ_SYS_USER_RULE MSUR 
// INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MSUR.ID_RULE 
// WHERE MSUR.ID_USER=$user_id 
// AND MSUR.AKTIF='Y'
// AND MSR.APP_ID= " . APPCODE . " 
// AND MSR.AKTIF='Y' 
// AND NAMA_RULE='Administrator'";
// $result = oci_parse($con, $query);
// oci_execute($result);
// $row = oci_fetch_row($result);
// $countAdmin = $row[0];
// //echo $countAdmin;
// if ($countAdmin == 0){
	// $queryfilter .="   AND CREATED_BY='$emp_name' ";
// } 

$queryAssignLogin = "SELECT ASSIGNMENT_ID 
FROM APPS.PER_ASSIGNMENTS_F
WHERE PERSON_ID = $emp_id AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
$resultAssignLogin = oci_parse($con,$queryAssignLogin);
oci_execute($resultAssignLogin);
$rowAssignLogin = oci_fetch_row($resultAssignLogin);
$assignment_id_login = $rowAssignLogin[0]; 

$queryLogin = "SELECT COUNT(*) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND HRD_ID = $emp_id";
//echo $assignment_id_login;exit;
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$countHrd = $rowLogin[0]; 

$queryLogin = "SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND HRD_ID = $emp_id";
//echo $queryLogin;exit;
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$hrdOrgId = $rowLogin[0]; 

if($countHrd == 0){
	$queryLogin = "SELECT CASE WHEN PJ.name like '%HRD%' AND PG.name like '%Manager%' THEN '3'
					ELSE '0' END AS TINGKAT
				, PPF.FULL_NAME, PP.NAME
			FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
			PER_PEOPLE_F PPF, APPS.PER_GRADES PG
			WHERE PAF.JOB_ID=PJ.JOB_ID
				AND PAF.POSITION_ID=PP.POSITION_ID
				AND PAF.EFFECTIVE_END_DATE > SYSDATE
				AND PRIMARY_FLAG = 'Y'
				AND PAF.PERSON_ID = PPF.PERSON_ID 
				AND PAF.GRADE_ID = PG.GRADE_ID
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PAF.ASSIGNMENT_ID = $assignment_id_login";
	$resultLogin = oci_parse($con,$queryLogin);
	oci_execute($resultLogin);
	$rowLogin = oci_fetch_row($resultLogin);
	$tingkat = $rowLogin[0]; 
	//echo $tingkat;exit;
	if($tingkat == 3){
		$query = "
			SELECT BS.ID, BS.NO_BS, BS.ASSIGNMENT_ID, PPF.FULL_NAME PEMOHON, BS.PENANGGUNG_JAWAB, PPF2.FULL_NAME PJ, BS.NOMINAL
					, BS.KETERANGAN, BS.AKTIF, 0, PPF.PERSON_ID, BS.TIPE, HOU.NAME
					, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
					-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
			FROM MJ.MJ_T_BS BS
			INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			WHERE 1=1 AND BS.TINGKAT >= 3
			 and BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND HRD_ID is not null)
			 $queryfilter
			ORDER BY BS.ID
			";
	}
	
}else{
	$query = "
		SELECT BS.ID, BS.NO_BS, BS.ASSIGNMENT_ID, PPF.FULL_NAME PEMOHON, BS.PENANGGUNG_JAWAB, PPF2.FULL_NAME PJ, BS.NOMINAL
				, BS.KETERANGAN, BS.AKTIF, 0, PPF.PERSON_ID, BS.TIPE, HOU2.NAME
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
		FROM MJ.MJ_T_BS BS
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
		AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
		AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		WHERE 1=1 AND BS.TINGKAT >= 3
		 and (BS.PERUSAHAAN_BS = $hrdOrgId or
			BS.PERUSAHAAN_BS in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND HRD_ID is not null AND HRD_ID = $emp_id) or BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND HRD_ID is not null)) 
		 $queryfilter
		ORDER BY BS.ID
		";
}
/* 
$query = "SELECT BS.ID, BS.NO_BS, BS.ASSIGNMENT_ID, PPF.FULL_NAME PEMOHON, BS.PENANGGUNG_JAWAB, PPF2.FULL_NAME PJ, BS.NOMINAL
, BS.KETERANGAN, BS.AKTIF, 0, PPF.PERSON_ID, BS.TIPE
FROM MJ.MJ_T_BS BS
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
WHERE 1=1 AND (BS.TINGKAT = 3 or (BS.TINGKAT = 1 AND BS.STATUS = 'PROCESS')) 
 $queryfilter
ORDER BY BS.ID
"; */
//echo $query;exit;
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
	$record['DATA_NO_BS']=$row[1];
	$record['DATA_ASSIGNMENT_ID']=$row[2];
	$record['DATA_PEMOHON']=$row[3];
	$record['DATA_PERSON_ID_PJ']=$row[4];
	$record['DATA_PJ']=$row[5];
	$record['DATA_NOMINAL']=$row[6];
	$record['DATA_KET']=$row[7];
	$record['DATA_AKTIF']=$row[8];
	$record['DATA_ID_UPL']=$row[9];
	$record['DATA_PERSON_ID']=$row[10];
	$record['DATA_TIPE']=$row[11];
	$record['DATA_PERUSAHAAN_BS']=$row[12];
	$record['DATA_TGL_JT']=$row[13];
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