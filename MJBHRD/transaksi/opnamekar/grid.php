<?PHP
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
session_start();
$queryFilter = '';
$data = '';
$result= '';
$periode='';
$periode1='';
$periode2='';
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
	// $pos_name = $_SESSION[APP]['pos_name'];
	// $pos_id = $_SESSION[APP]['pos_id'];
}



if(isset($_GET['plant'])){	
	
	$plant = $_GET['plant'];
	$queryFilter .= "AND HL.LOCATION_ID = '$plant'";		

	$department = $_GET['department'];
	if ($department != '' && $department != 'null') {
		$queryFilter .= "AND PJ.NAME LIKE '%$department%'";
	}	

	$atasan_tdk_lsg = $_GET['atasan_tdk_lsg'];
	if ($atasan_tdk_lsg != '' && $atasan_tdk_lsg != 'null') {
		$queryFilter .= "AND PAF.ASS_ATTRIBUTE2 = '$atasan_tdk_lsg'";
	}

	$atasan_lsg = $_GET['atasan_lsg'];
	if ($atasan_lsg != '' && $atasan_lsg != 'null') {
		$queryFilter .= "AND PAF.ASS_ATTRIBUTE1 = '$atasan_lsg'";
	}

	$periode = $_GET['periode'];
	$periode1 = substr($periode, 0, 10);
	$periode2 = substr($periode, 15, 10);

	$queryCek = "SELECT COUNT(-1) FROM MJ.MJ_MASTER_OPNAME WHERE NAMA = '$emp_name' AND STATUS = 'A' AND PLANT = '$plant'";
	$resultCek = oci_parse($con,$queryCek);
	oci_execute($resultCek);
	$rowCek = oci_fetch_row($resultCek);
	$counter = $rowCek[0];	

	// echo $counter;exit;

	if ($counter != 0) {	

		// echo "";exit;
		$result = oci_parse($con, "SELECT DISTINCT PPF.FULL_NAME NAMA, MOK.ID,PPF.PERSON_ID, HOU.NAME OU, HOU.ORGANIZATION_ID ID_OU,
		CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE
		 REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END Depart,PJ.JOB_ID DEPART_ID,
		PP.POSITION_ID,PP.NAME JABATAN, HL.LOCATION_ID PLANT_ID,HL.LOCATION_CODE PLANT, PG.NAME GRADE, PG.GRADE_ID
		 GRADE_ID, PPF2.FULL_NAME LSG, PPF3.FULL_NAME TDK_LSG, MOK.KETERANGAN,MOK.STATUS,MOK.TINGKAT,
		MOK.OU OU_PENGGANTI,
		MOK.DEPART DEPT_PENGGATIN,
		MOK.PLANT PLANT_PENGGANTI,
		MOK.JABATAN JABATAN_PENGGANTI,
		MOK.GRADE GRADE_PENGGANTI,
		MOK.ATASAN_LSG ATASAN_LSG_PENGGANTI,
		MOK.ATASAN_TDK_LSG ATASAN_TDK_LSG_PENGGANTI
		from per_people_f ppf
		INNER JOIN PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
		INNER JOIN PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		INNER JOIN PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
		INNER JOIN PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
		INNER JOIN HR_ORGANIZATION_UNITS HOU ON PP.ORGANIZATION_ID = HOU.ORGANIZATION_ID 
		INNER JOIN HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN MJ_OPNAME_KAR MOK ON PPF.PERSON_ID = MOK.PERSON_ID 
		LEFT JOIN PER_PEOPLE_F PPF2 ON PAF.ASS_ATTRIBUTE1 = PPF2.PERSON_ID AND PPF2.EFFECTIVE_END_DATE > SYSDATE 
        LEFT JOIN PER_PEOPLE_F PPF3 ON PAF.ASS_ATTRIBUTE2 = PPF3.PERSON_ID AND PPF3.EFFECTIVE_END_DATE > SYSDATE 
		WHERE PAF.PRIMARY_FLAG = 'Y'
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND PPF.EFFECTIVE_END_DATE > SYSDATE 
		AND PPF.DATE_EMPLOYEE_DATA_VERIFIED is null
		AND DECODE(MOK.CREATED_DATE, NULL,'$periode2') between '$periode1' and '$periode2'
		$queryFilter
		UNION
		SELECT DISTINCT PPF.FULL_NAME NAMA, 0 ID,PPF.PERSON_ID, HOU.NAME OU, HOU.ORGANIZATION_ID ID_OU,
		CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE
		 REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END Depart,PJ.JOB_ID DEPART_ID,
		PP.POSITION_ID,PP.NAME JABATAN, HL.LOCATION_ID PLANT_ID,HL.LOCATION_CODE PLANT, PG.NAME GRADE, PG.GRADE_ID
		 GRADE_ID, PPF2.FULL_NAME LSG, PPF3.FULL_NAME TDK_LSG, '' KETERANGAN, '' STATUS,0 TINGKAT,
		'' OU_PENGGANTI,
		'' DEPT_PENGGATIN,
		'' PLANT_PENGGANTI,
		'' JABATAN_PENGGANTI,
		'' GRADE_PENGGANTI,
		'' ATASAN_LSG_PENGGANTI,
		'' ATASAN_TDK_LSG_PENGGANTI
		from per_people_f ppf
		INNER JOIN PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
		INNER JOIN PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		INNER JOIN PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
		INNER JOIN PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
		INNER JOIN HR_ORGANIZATION_UNITS HOU ON PP.ORGANIZATION_ID = HOU.ORGANIZATION_ID 
		INNER JOIN HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN MJ_OPNAME_KAR MOK ON PPF.PERSON_ID = MOK.PERSON_ID --
		LEFT JOIN PER_PEOPLE_F PPF2 ON PAF.ASS_ATTRIBUTE1 = PPF2.PERSON_ID AND PPF2.EFFECTIVE_END_DATE > SYSDATE 
        LEFT JOIN PER_PEOPLE_F PPF3 ON PAF.ASS_ATTRIBUTE2 = PPF3.PERSON_ID AND PPF3.EFFECTIVE_END_DATE > SYSDATE 
		WHERE PAF.PRIMARY_FLAG = 'Y'
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND PPF.EFFECTIVE_END_DATE > SYSDATE 
		AND PPF.DATE_EMPLOYEE_DATA_VERIFIED is null
		AND TO_CHAR(MOK.CREATED_DATE,'YYYY-MM-DD') NOT between '$periode1' and '$periode2'
		$queryFilter");
				// oci_execute($result);
		oci_execute($result);		
		while($row = oci_fetch_row($result))
		{
			$record = array();
			$record['DATA_KARYAWAN']=$row[0];
			$record['HD_ID']=$row[1];
			$record['DATA_KARYAWAN_ID']=$row[2];
			$record['DATA_OU']=$row[3];
			$record['DATA_OU_ID']=$row[4];
			$record['DATA_DEPT']=$row[5];
			$record['DATA_DEPT_ID']=$row[6];
			$record['DATA_JABATAN_ID']=$row[7];
			$record['DATA_JABATAN']=$row[8];
			$record['DATA_PLANT_ID']=$row[9];
			$record['DATA_PLANT']=$row[10];
			$record['DATA_GRADE']=$row[11];		
			$record['DATA_GRADE_ID']=$row[12];		
			$record['DATA_ATASAN_LSG']=$row[13];
			$record['DATA_ATASAN_TDK_LSG']=$row[14];		
			$record['DATA_KETERANGAN']=$row[15];
			$record['DATA_STATUS']=$row[16];
			$record['DATA_TINGKAT']=$row[17];
			$record['DATA_OU_PENGGANTI']=$row[18];		
			$record['DATA_DEPT_PENGGANTI']=$row[19];		
			$record['DATA_PLANT_PENGGANTI']=$row[20];		
			$record['DATA_JABATAN_PENGGANTI']=$row[21];		
			$record['DATA_GRADE_PENGGANTI']=$row[22];		
			$record['DATA_ATASAN_LSG_PENGGANTI']=$row[23];		
			$record['DATA_ATASAN_TDK_LSG_PENGGANTI']=$row[24];				
			$data[]=$record;
		}	
	}
}
	
echo json_encode($data); 
?>