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
//echo $query;
if ($countAdmin == 0){
	//if{
		$queryfilter .=" AND ((MTM.MGR_LAMA = '$emp_id' and MTM.TINGKAT = 0) OR (MTM.MGR_BARU = '$emp_id' and MTM.TINGKAT = 1))";
	//}
	//$queryfilter .="   AND MTM.CREATED_BY='$emp_id' ";
} 
$query = "SELECT MTM.ID
, MTM.NO_REQUEST
, MTM.TIPE
, PPF1.FULL_NAME PEMBUAT
, PPF.FULL_NAME KARYAWAN
, PJ.NAME DEPT_LAMA
, PP.NAME POSISI_LAMA
, HL.LOCATION_CODE LOKASI_LAMA
, MTM.GAJI_LAMA
, PJ1.NAME DEPT_BARU
, PP1.NAME POSISI_BARU
, HL1.LOCATION_CODE LOKASI_BARU
, MTM.GAJI_BARU
, MTM.KETERANGAN
, MTM.ALASAN
, TO_CHAR(MTM.TGL_EFFECTIVE, 'YYYY-MM-DD')
, PVG.NAME GRADE_LAMA
, PVG1.NAME GRADE
, PPF3.FIRST_NAME || ' ' || PPF3.LAST_NAME MGR_LAMA
, PPF4.FIRST_NAME || ' ' || PPF4.LAST_NAME MGR_BARU
, MTM.STATUS_KARYAWAN
, MTM.SIFAT_PERUBAHAN
, MTM.JUMLAH_BULAN
FROM MJ.MJ_T_MUTASI MTM
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.KARYAWAN_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.CREATED_BY AND PPF1.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_PEOPLE_F PPF3 ON PPF3.PERSON_ID = MTM.MGR_LAMA AND PPF3.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_PEOPLE_F PPF4 ON PPF4.PERSON_ID = MTM.MGR_BARU AND PPF4.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN APPS.PER_VALID_GRADES_V PVG ON PVG.POSITION_ID=PP.POSITION_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID 
LEFT JOIN APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
LEFT JOIN APPS.PER_POSITIONS PP1 ON PP1.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN APPS.PER_VALID_GRADES_V PVG1 ON PVG1.POSITION_ID=PP1.POSITION_ID
LEFT JOIN APPS.HR_LOCATIONS HL1 ON HL1.LOCATION_ID = MTM.LOKASI_BARU_ID 
WHERE 1=1
AND MTM.STATUS_DOK NOT IN ('Approved','Disapproved') AND MTM.TINGKAT IN (0, 1)
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
	$record['DATA_PEMBUAT']=$row[3];
	$record['DATA_KARYAWAN']=$row[4];
	$record['DATA_DEPT_LAMA']=$row[5];
	$record['DATA_POSISI_LAMA']=$row[6];
	$record['DATA_LOKASI_LAMA']=$row[7];
	$record['DATA_GAJI_LAMA']=number_format($row[8], 2, ',', '.');
	$record['DATA_DEPT']=$row[9];
	$record['DATA_POSISI']=$row[10];
	$record['DATA_LOKASI']=$row[11];
	$record['DATA_GAJI']=number_format($row[12], 2, ',', '.');
	$record['DATA_KETERANGAN']=$row[13];
	$record['DATA_ALASAN']=$row[14];
	$record['DATA_TGL']=$row[15];
	$record['DATA_GRADE_LAMA']=$row[16];
	$record['DATA_GRADE']=$row[17];
	$record['DATA_MGR_LAMA']=$row[18];
	$record['DATA_MGR_BARU']=$row[19];
	$record['DATA_STATUS_KARYAWAN']=$row[20];
	$record['DATA_SIFAT_PERUBAHAN']=$row[21];
	$record['DATA_JUMLAH_BULAN']=$row[22];
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