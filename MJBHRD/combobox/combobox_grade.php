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
	$querywhere .= " AND UPPER(NAME) LIKE '%$name%' ";
}


if (isset($_GET['jobId']))
{	
	$jobId = $_GET['jobId'];
	$querywhere .= " AND PP.JOB_ID = '$jobId' ";
}


if (isset($_GET['tipe']))
{
	$tipe = $_GET['tipe'];
	$gradeArr = explode(".", $_GET['grade']);
	$grade = $gradeArr[0];
	
	
	// Penambahan tipe "Mutasi dan Promosi" dan "Mutasi dan Demosi" oleh Yuke di 27 Sept 2018.
	
	
	if ($tipe == 'Mutasi') {
		$querywhere .= " AND REGEXP_SUBSTR(PVG.NAME, '[^.]+', 1, 1) = '$grade' ";
	} else if ($tipe == 'Promosi'){
		$querywhere .= " AND REGEXP_SUBSTR(PVG.NAME, '[^.]+', 1, 1) < '$grade' ";
	} else if ($tipe == 'Demosi'){
		$querywhere .= " AND REGEXP_SUBSTR(PVG.NAME, '[^.]+', 1, 1) > '$grade' ";	
	} else if ($tipe == 'Mutasi dan Promosi'){
		$querywhere .= " AND REGEXP_SUBSTR(PVG.NAME, '[^.]+', 1, 1) < '$grade' ";	
	} else if ($tipe == 'Mutasi dan Demosi'){
		$querywhere .= " AND REGEXP_SUBSTR(PVG.NAME, '[^.]+', 1, 1) > '$grade' ";	
	}
	
}

/*
$query = "SELECT DISTINCT GRADE_ID
, NAME 
FROM APPS.PER_VALID_GRADES_V PVG
WHERE 1=1 
$querywhere
ORDER BY NAME";
*/



$query = "
SELECT  DISTINCT PVG.NAME GRADE_NAME, PVG.GRADE_ID
FROM    APPS.PER_VALID_GRADES_V PVG, APPS.PER_POSITIONS PP, APPS.PER_JOBS PJ
WHERE   PVG.POSITION_ID = PP.POSITION_ID
AND     PP.JOB_ID = PJ.JOB_ID
$querywhere
ORDER   BY  PVG.NAME
";


// echo $query; exit;

$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		
		// $record['DATA_VALUE']=$row[0];
		// $record['DATA_NAME']=$row[1];
		
		$record['DATA_VALUE']=$row[1];
		$record['DATA_NAME']=$row[0];
		
		$data[]=$record;
	}

	echo json_encode($data);
?>