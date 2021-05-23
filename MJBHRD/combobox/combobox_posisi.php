<?php
include '../main/koneksi.php';

// deklarasi variable dan session
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
  
$name = "";
$pemohon = "";
$querywhere = "";
$data = "";

// $record = array();
// $record['DATA_VALUE']='0';
// $record['DATA_NAME']='- Pilih -';
// $data[]=$record;

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere .= " AND UPPER(PP.NAME) LIKE '%$name%' ";
}

if (isset($_GET['jobId']))
{	
	$jobId = $_GET['jobId'];
	$querywhere .= " AND PP.JOB_ID = '$jobId' ";
}

if (isset($_GET['grade']))
{	
	$grade = $_GET['grade'];
	$querywhere .= " AND PVG.GRADE_ID = '$grade' ";
}


$query = "
SELECT  PJ.NAME DEPT_NAME, PJ.JOB_ID,
        PVG.NAME GRADE_NAME, PVG.GRADE_ID, 
        PP.NAME POSITION_NAME, PP.POSITION_ID
FROM    APPS.PER_VALID_GRADES_V PVG, APPS.PER_POSITIONS PP, APPS.PER_JOBS PJ
WHERE   PVG.POSITION_ID = PP.POSITION_ID
AND     PP.JOB_ID = PJ.JOB_ID
$querywhere
ORDER   BY  PJ.NAME
";


/*
$query = "SELECT DISTINCT PP.POSITION_ID AS DATA_VALUE
, PP.NAME AS DATA_NAME
FROM APPS.PER_POSITIONS PP
WHERE 1=1
--ORGANIZATION_ID=$org_id 
$querywhere 
ORDER BY DATA_NAME";
*/

// echo $query;

$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[5];
		$record['DATA_NAME']=$row[4];
		$data[]=$record;
	}

	echo json_encode($data);
?>