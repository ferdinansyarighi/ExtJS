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
$data="";
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
		$queryfilter .=" AND UPPER(MTM.NO_REQUEST) LIKE '%$tffilter%' ";
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
		
		$queryfilter .=" AND TO_CHAR(MTM.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTM.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	}
} 
$query = "SELECT COUNT(-1) 
FROM MJ.MJ_SYS_USER_RULE MSUR 
INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MSUR.ID_RULE 
WHERE MSUR.ID_USER=$user_id 
AND MSUR.AKTIF='Y'
AND MSR.APP_ID= " . APPCODE . " 
AND MSR.AKTIF='Y' 
AND NAMA_RULE='Administrator'";
$result = oci_parse($con, $query);
oci_execute($result);
$row = oci_fetch_row($result);
$countAdmin = $row[0];
//echo $countAdmin;
if ($countAdmin == 0){
	$queryfilter .="   AND MTM.CREATED_BY='$emp_id' ";
} 
$query = "SELECT MTM.ID
, MTM.NO_REQUEST
, MTM.TIPE
, MTM.KARYAWAN_ID
, PPF.FULL_NAME
, MTM.DEPT_LAMA_ID
, PJ.NAME DEPT_LAMA
, MTM.POSISI_LAMA_ID
, PP.NAME POSISI_LAMA
, MTM.LOKASI_LAMA_ID
, HL.LOCATION_CODE LOKASI_LAMA
, MTM.GAJI_LAMA
, MTM.DEPT_BARU_ID
, MTM.POSISI_BARU_ID
, MTM.LOKASI_BARU_ID
, MTM.GAJI_BARU
, MTM.KETERANGAN
, MTM.ALASAN
, MTM.STATUS
, TO_CHAR(MTM.TGL_EFFECTIVE, 'YYYY-MM-DD')
, PVG.GRADE_ID
, PVG.NAME
, MTM.GRADE_BARU_ID
, MTM.MGR_LAMA MGR_LAMA
, MTM.MGR_BARU MGR_BARU
, MTM.STATUS_KARYAWAN
, MTM.SIFAT_PERUBAHAN
, MTM.JUMLAH_BULAN
FROM MJ.MJ_T_MUTASI MTM
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.KARYAWAN_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF3 ON PPF3.PERSON_ID = MTM.MGR_LAMA AND PPF3.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF4 ON PPF4.PERSON_ID = MTM.MGR_BARU AND PPF4.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID 
LEFT JOIN APPS.PER_VALID_GRADES_V PVG ON PVG.POSITION_ID=PP.POSITION_ID AND MTM.GRADE_LAMA_ID = PVG.GRADE_ID
WHERE 1=1
AND MTM.STATUS_DOK <> 'Approved' 
$queryfilter";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NOREQ']=$row[1];
	$record['DATA_TIPE']=$row[2];
	$record['DATA_KARYAWAN_ID']=$row[3];
	$record['DATA_KARYAWAN']=$row[4];
	$record['DATA_DEPT_LAMA_ID']=$row[5];
	$record['DATA_DEPT_LAMA']=$row[6];
	$record['DATA_POSISI_LAMA_ID']=$row[7];
	$record['DATA_POSISI_LAMA']=$row[8];
	$record['DATA_LOKASI_LAMA_ID']=$row[9];
	$record['DATA_LOKASI_LAMA']=$row[10];
	$record['DATA_GAJI_LAMA']=$row[11];
	$record['DATA_DEPT']=$row[12];
	$record['DATA_POSISI']=$row[13];
	$record['DATA_LOKASI']=$row[14];
	$record['DATA_GAJI']=$row[15];
	$record['DATA_KETERANGAN']=$row[16];
	$record['DATA_ALASAN']=$row[17];
	$record['DATA_AKTIF']=$row[18];
	$record['DATA_TGL']=$row[19];
	$record['DATA_GRADE_LAMA_ID']=$row[20];
	$record['DATA_GRADE_LAMA']=$row[21];
	$record['DATA_GRADE']=$row[22];
	$record['DATA_MGR_LAMA']=$row[23];
	$record['DATA_MGR_BARU']=$row[24];
	$record['DATA_STATUS_KARYAWAN']=$row[25];
	$record['DATA_SIFAT_PERUBAHAN']=$row[26];
	$record['DATA_JUMLAH_BULAN']=$row[27];
	$data[]=$record;
	$count++;
}

 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>