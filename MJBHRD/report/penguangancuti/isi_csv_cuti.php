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
$tglskr=date('Y-m-d'); 
if (isset($_GET['perusahaan']) || isset($_GET['nama']) || isset($_GET['dept']))
{	
	$perusahaan = $_GET['perusahaan'];
	$nama = $_GET['nama'];
	$dept = $_GET['dept'];
	if($perusahaan!=''){
		$queryfilter .= " AND TL.DESCRIPTION='$perusahaan'";
	}
	if($nama!=''){
		$queryfilter .= " AND PPF.FULL_NAME='$nama'";
	}
	if($dept!=''){
		$queryfilter .= " AND REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3)='$dept'";
	}
}

$namaFile ="Report_Penguangan_Cuti.csv";

$dataArray = array();

$countData = 0;
$sumGaji = 0;
$isiKaryawan = ""; 

	$queryGaji = "SELECT MMUC.PERSON_ID
	,MMUC.UANG_CUTI
	,TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) AS NO_REKENING
	,TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AS ATAS_NAMA
	,REPLACE(TO_CHAR(SYSDATE, 'YYYY-MM-DD'), '-', '')
	FROM MJHR.MJ_M_UANG_CUTI MMUC
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMUC.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE";
	//echo $queryGaji;
	$resultGaji = oci_parse($conHR,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vPersonId = $row[0];
		$vUangCuti = $row[1];
		$vNoRek = $row[2];
		$vAtasNama = $row[3];
		$tglskr = $row[4];
		
		if($vUangCuti > 0){
			$sumGaji = $sumGaji + $vUangCuti;
			$recordDetail = array($vNoRek, $vAtasNama, '1420000080605', '', '', 'IDR', $vUangCuti, '', '', 'IBU', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'extended payment detail1');
			$dataArray[$countData] = $recordDetail;
			$countData++;
		}
	}
	
$fp = fopen($namaFile, "w");

$recordHeader = array('P', $tglskr, '1420000080605', $countData, $sumGaji);
fputcsv($fp, $recordHeader);

for($x = 0; $x < $countData; $x++){
	fputcsv($fp, $dataArray[$x]);
}

fclose($fp);

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $namaFile .'');
readfile($namaFile);

exit();

?>