<?php
include '../main/koneksi.php';

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
$queryfilter = ""; 

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


$query_role = "
SELECT  R.NAMA_RULE, U.EMP_ID, J.JOB_ID, A.APP_NAME, U.USERNAME AKUN, E.FULL_NAME VC_NAME, U.ID, E.EMAIL_ADDRESS VC_EMAIL, ID_USER_RULE, 
		UR.UPDATE_DATE, DECODE(UR.AKTIF, 'Y','Active','Inactive') ACTIVE_DESC, UR.ID_RULE, UR.AKTIF, J.NAME AS VC_DEPT_NAME
FROM    MJ.MJ_SYS_USER_RULE UR, MJ.MJ_SYS_RULE R,
        MJ.MJ_M_USER U, APPS.PER_PEOPLE_F E, MJ.MJ_SYS_APP A, APPS.PER_ALL_ASSIGNMENTS_F ASS, APPS.PER_JOBS J
WHERE   UR.ID_RULE = R.ID_RULE AND UR.ID_USER = U.ID AND U.EMP_ID = E.PERSON_ID AND R.APP_ID = A.APP_ID 
AND     E.PERSON_ID = ASS.PERSON_ID AND SYSDATE BETWEEN ASS.EFFECTIVE_START_DATE AND ASS.EFFECTIVE_END_DATE
AND     ASS.JOB_ID = J.JOB_ID(+) AND SYSDATE BETWEEN E.EFFECTIVE_START_DATE AND E.EFFECTIVE_END_DATE AND ASS.PRIMARY_FLAG = 'Y'
AND     A.APP_NAME = 'MJBHRD'
AND     U.EMP_ID = $emp_id
";

$result = oci_parse( $con, $query_role );
oci_execute( $result );
$row = oci_fetch_row( $result );

$vNamaRule = $row[0];
$vDeptId = $row[2];

if ( $vNamaRule == 'Administrator' || $vDeptId == 26066 ) {

	$queryfilter = "";

} else {
	
	$queryfilter = " WHERE ( KARYAWAN_ID = $emp_id OR CREATED_BY = $emp_id ) ";
	
}


$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN UPPER(REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2)) ELSE UPPER(REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3)) END LIKE '%$pjname%' ";
}

$query = "
SELECT DISTINCT CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END DATA_VALUE
, CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END DATA_NAME
FROM APPS.PER_JOBS PJ
WHERE UPPER(NAME) NOT LIKE '%OLD%' 
AND PJ.JOB_ID IN
(
	SELECT  DISTINCT DEPT_LAMA_ID
	FROM    MJ_T_MUTASI $queryfilter
)
$querywhere
ORDER BY DATA_VALUE
";

// echo $query; exit;

$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['DATA_VALUE']=$row[0];
	$record['DATA_NAME']=$row[1];
	$data[]=$record;
}

echo json_encode($data);
?>