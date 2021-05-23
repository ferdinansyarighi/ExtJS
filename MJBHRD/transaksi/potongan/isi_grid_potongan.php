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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$kategori = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$Dept = "";
$tingkat = 0;
$count = 0;
$countSpv = 0;
$countMan = 0;
$queryfilter=""; 
if (isset($_GET['nama_pem']))
{	
	$nama_pem=str_replace("'", "''", $_GET['nama_pem']);
	$resultCount = oci_parse($con, "SELECT COUNT(-1) 
	FROM MJ.MJ_T_POTONGAN 
	WHERE APP_ID=" . APPCODE . " AND PERSON_ID=(SELECT PERSON_ID FROM APPS.PER_PEOPLE_F WHERE FULL_NAME='$nama_pem' AND CURRENT_EMPLOYEE_FLAG='Y' AND EFFECTIVE_END_DATE > SYSDATE) AND STATUS = 'A'");
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$userCount = $rowCount[0];
	//echo $userCount;
	if ($userCount==0){
		$query = "SELECT HD_ID
		, NAMA_PINJAMAN
		, JUM_PINJAMAN
		, JUM_CICILAN
		, HARGA_CICILAN
		, OUTSTANDING
		, OUTSTANDING_ASLI
		FROM
		(SELECT 0 AS HD_ID
		, 'Pinjaman Personal' AS NAMA_PINJAMAN
		, 0 AS JUM_PINJAMAN
		, 0 AS JUM_CICILAN
		, 0 AS HARGA_CICILAN
		, 0 AS OUTSTANDING 
		, 0 AS OUTSTANDING_ASLI
		FROM DUAL
		UNION
		SELECT 0 AS HD_ID
		, 'BS' AS NAMA_PINJAMAN
		, 0 AS JUM_PINJAMAN
		, 0 AS JUM_CICILAN
		, 0 AS HARGA_CICILAN
		, 0 AS OUTSTANDING 
		, 0 AS OUTSTANDING_ASLI
		FROM DUAL)
		ORDER BY NAMA_PINJAMAN DESC
		";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$record = array();
			$record['HD_ID']=$row[0];
			$record['DATA_JENIS']=$row[1];
			$record['DATA_PINJAMAN']=$row[2];
			$record['DATA_CICILAN']=$row[3];
			$record['DATA_JUMLAH_CICILAN']=$row[4];
			$record['DATA_OUTSTANDING']=$row[5];
			$record['OUTSTANDING_ASLI']=$row[6];
			$data[]=$record;
			$countSpv++;
		}
	} else {
		$query = "SELECT ID AS HD_ID
		, 'Pinjaman Personal' AS NAMA_PINJAMAN
		, PINJAMAN_P AS JUM_PINJAMAN
		, CICILAN_P AS JUM_CICILAN
		, HARGA_CICILAN_P AS HARGA_CICILAN
		, OUTSTANDING_P AS OUTSTANDING 
		, OUTSTANDING_P AS OUTSTANDING_ASLI
		FROM MJ.MJ_T_POTONGAN 
		WHERE APP_ID=" . APPCODE . " AND PERSON_ID=(SELECT PERSON_ID FROM APPS.PER_PEOPLE_F WHERE FULL_NAME='$nama_pem' AND CURRENT_EMPLOYEE_FLAG='Y' AND EFFECTIVE_END_DATE > SYSDATE)
		AND STATUS='A'
		UNION
		SELECT ID AS HD_ID
		, 'BS' AS NAMA_PINJAMAN
		, PINJAMAN_B AS JUM_PINJAMAN
		, CICILAN_B AS JUM_CICILAN
		, HARGA_CICILAN_B AS HARGA_CICILAN
		, OUTSTANDING_B AS OUTSTANDING 
		, OUTSTANDING_B AS OUTSTANDING_ASLI
		FROM MJ.MJ_T_POTONGAN 
		WHERE APP_ID=" . APPCODE . " AND PERSON_ID=(SELECT PERSON_ID FROM APPS.PER_PEOPLE_F WHERE FULL_NAME='$nama_pem' AND CURRENT_EMPLOYEE_FLAG='Y' AND EFFECTIVE_END_DATE > SYSDATE)
		AND STATUS='A'
		ORDER BY NAMA_PINJAMAN DESC";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$record = array();
			$record['HD_ID']=$row[0];
			$record['DATA_JENIS']=$row[1];
			$record['DATA_PINJAMAN']=$row[2];
			$record['DATA_CICILAN']=$row[3];
			$record['DATA_JUMLAH_CICILAN']=$row[4];
			$record['DATA_OUTSTANDING']=$row[5];
			$record['OUTSTANDING_ASLI']=$row[6];
			$data[]=$record;
			$countSpv++;
		}
	}

	if ($count==0 && $countSpv==0 && $countMan==0)
	{
		$data="";
	}

	echo json_encode($data); 
} 


?>