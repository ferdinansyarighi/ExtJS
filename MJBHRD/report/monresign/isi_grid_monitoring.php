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
  
$tglfrom     = "";
$namapemohon = "";
$tglto       = "";
$nopengajuan = "";
$company     = "";
$departemen  = "";
$location    = "";
$manager     = "";

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$queryfilter = "";
	$tglskr      = date('Y-m-d');
	$tglfrom     = $_GET['tglfrom'];
	$tglto       = $_GET['tglto'];
	$namapemohon = $_GET['pemohon'];
	$nopengajuan = $_GET['nopengajuan'];
	$company	 = $_GET['company'];
	$dept   	 = $_GET['dept'];
	$location	 = $_GET['location'];
	$manager	 = $_GET['manager'];

	if ($namapemohon!=''){
		$queryfilter .=" AND NAMA_KARYAWAN = '$namapemohon' ";
	}
	if ($tglfrom!=''){
		$queryfilter .=" AND TGL_RESIGN >= TO_DATE('$tglfrom', 'DD/MM/YY') ";
	}
	if ($tglto!=''){
		$queryfilter .=" AND TGL_RESIGN <= TO_DATE('$tglto', 'DD/MM/YY') ";
	}
	if ($nopengajuan!=''){
		$queryfilter .=" AND NO_PENGAJUAN LIKE '%$nopengajuan%' ";
	}
	if ($company!=''){
		$queryfilter .=" AND COMPANY LIKE '%$company%' ";
	}
	if ($dept!=''){
		$queryfilter .=" AND DEPARTMENT LIKE '%$dept%' ";
	}
	if ($location!=''){
		$queryfilter .=" AND LOCATION LIKE '%$location%' ";
	}
	if ($manager!=''){
		$queryfilter .=" AND MANAGER LIKE '%$manager%' ";
	}
} 

$querycount = "SELECT COUNT(-1) FROM PER_ASSIGNMENTS_F PAF, PER_PEOPLE_F PPF WHERE PAF.JOB_ID = 26066 AND PAF.PERSON_ID = PPF.PERSON_ID AND PPF.PERSON_ID = $emp_id";
$resultcount = oci_parse($con,$querycount);
oci_execute($resultcount);
$rowcount = oci_fetch_row($resultcount);
$jumgen = $rowcount[0];

$querycountMan = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
WHERE PPF.FULL_NAME LIKE '%$emp_name%' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID AND PP.NAME LIKE '%MGR%' AND PAF.JOB_ID != 26066";
$resultcountman = oci_parse($con,$querycountMan);
oci_execute($resultcountman);
$rowcountman = oci_fetch_row($resultcountman);
$jumman = $rowcountman[0];

$queryPemohon = "SELECT PJ.JOB_ID 
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '%$emp_name%'";
$resultPemohon = oci_parse($con,$queryPemohon);
oci_execute($resultPemohon);
$rowPemohon = oci_fetch_row($resultPemohon);
$jobPemohon = $rowPemohon[0];

if($jumgen >= 1)
{
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY'),TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY'),TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TO_CHAR(TGL_APP_TERAKHIR, 'DD-MON-YYYY'), CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE,TO_CHAR(TGL_PENGAJUAN, 'DD-MON-YYYY')
	FROM MJ.MJ_T_RESIGN WHERE 1=1 $queryfilter";
}
else if($jumman >= 1){
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY'),TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY'),TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TO_CHAR(TGL_APP_TERAKHIR, 'DD-MON-YYYY'), CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE,TO_CHAR(TGL_PENGAJUAN, 'DD-MON-YYYY')
	FROM MJ.MJ_T_RESIGN WHERE NAMA_KARYAWAN LIKE '%$emp_name%' $queryfilter
	UNION
	SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY'),TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY'),TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TO_CHAR(TGL_APP_TERAKHIR, 'DD-MON-YYYY'), CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE,TO_CHAR(TGL_PENGAJUAN, 'DD-MON-YYYY')
	FROM MJ.MJ_T_RESIGN WHERE MANAGER LIKE '%$emp_name%' $queryfilter";
}
else {
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY'),TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY'),TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TO_CHAR(TGL_APP_TERAKHIR, 'DD-MON-YYYY'), CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE,TO_CHAR(TGL_PENGAJUAN, 'DD-MON-YYYY')
	FROM MJ.MJ_T_RESIGN WHERE NAMA_KARYAWAN LIKE '%$emp_name%' $queryfilter";
}

$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_PENGAJUAN']=$row[1];
	$record['DATA_NAMA_KARYAWAN']=$row[2];
	$record['DATA_COMPANY']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_POS']=$row[5];
	$record['DATA_GRADE']=$row[6];
	$record['DATA_LOCATION']=$row[7];
	$record['DATA_TGL_MASUK']=$row[8];
	$record['DATA_TGL_RESIGN']=$row[9];
	$record['DATA_TAHUN_LAMA_KERJA']=$row[10];
	$record['DATA_BULAN_LAMA_KERJA']=$row[11];
	$record['DATA_HARI_LAMA_KERJA']=$row[12];
	$record['DATA_MANAGER']=$row[13];
	$record['DATA_KETERANGAN']=$row[14];
	$record['DATA_STATUS']=$row[15];
	$record['DATA_APPROVAL_MANAGER']=$row[16];
	$record['DATA_APPROVAL_MANAGER_HRD']=$row[17];
	$record['DATA_TGL_APP_TERAKHIR']=$row[18];
	$record['DATA_CREATED_BY']=$row[19];
	$record['DATA_CREATED_DATE']=$row[20];
	$record['DATA_LAMA_KERJA']=$row[21];
	$record['DATA_KET_DISAPP']=$row[22];
	$record['DATA_TGL_PENGAJUAN']=$row[23];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>