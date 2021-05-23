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
  
$tglskr=date('Y-m-d'); 
$tglfrom = "";
$tglto = "";
$Dept = "";
$count = 0;

$queryfilter=""; 

	
	
	
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$bulanfrom = $_GET['bulanfrom'];
	$bulanto = $_GET['bulanto'];
	$tahunfrom = $_GET['tahunfrom'];
	$tahunto = $_GET['tahunto'];
	
	$perusahaan = $_GET['perusahaan'];
	$dept = $_GET['dept'];
	$namaKaryawan = $_GET['pemohon'];
	$status = $_GET['status'];
	
	if($tglfrom!='' || $tglto!=''){
		if($tglfrom==''){
			$tglfrom='01-01-1990';
		}
		if($tglto==''){
			$tglto='31-12-2090';
		}
		$queryfilter .=" AND MTP.CREATED_DATE >= TO_DATE('$tglfrom', 'DD-MM-YYYY') AND MTP.CREATED_DATE <= TO_DATE('$tglto', 'DD-MM-YYYY') ";
	}
	
	if($bulanfrom!='' || $tahunfrom!='' || $bulanfrom!='' || $tahunto!=''){
		$queryfilter .=" AND MTPD.CREATED_DATE BETWEEN TO_DATE('01-".$bulanfrom."-".$tahunfrom."', 'DD-MM-YYYY') AND TO_DATE('25-".$bulanto."-".$tahunto."', 'DD-MM-YYYY') ";
	}
	
	if ($perusahaan!=''){
		$queryfilter .=" AND PAF.ORGANIZATION_ID = $perusahaan ";
	}
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
	if ($namaKaryawan!=''){
		$queryfilter .=" AND MTP.PERSON_ID = $namaKaryawan ";
	} 
	if ($status!='ALL'){
		$queryfilter .=" AND UPPER(MTP.STATUS) = '$status' ";
	}
//echo $queryfilter;exit;
$query = "SELECT HD_ID, MTP.PERSON_ID, HOU.NAME PERUSAHAAN, PJ.NAME DEPT, PPF.FULL_NAME NAMA,  NAMA_PINJAMAN, NOMINAL, CICILAN, NOMINAL_CICILAN, OUTSTANDING, MTP.STATUS_NAME, TGL_INPUT_PINJAMAN
, TO_CHAR(MTPD.CREATED_DATE, 'DD-MON-YYYY') TGL_POTONGAN, TO_CHAR(MTPD.CREATED_DATE, 'MM-YYYY') TGL_POTONGAN
FROM (SELECT ID AS HD_ID
        , PERSON_ID
        , 'PINJAMAN' AS NAMA_PINJAMAN
        , PINJAMAN_P AS NOMINAL
        , CICILAN_P AS CICILAN
        , HARGA_CICILAN_P AS NOMINAL_CICILAN
        , OUTSTANDING_P AS OUTSTANDING 
        , CASE WHEN STATUS = 'A' THEN 'Active'
            WHEN STATUS = 'I' THEN 'Inactive'
            END STATUS_NAME
		, STATUS
        , TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_INPUT_PINJAMAN
		, CREATED_DATE
        FROM MJ.MJ_T_POTONGAN 
        WHERE APP_ID=1 
        UNION
        SELECT ID AS HD_ID
        , PERSON_ID
        , 'BS' AS NAMA_PINJAMAN
        , PINJAMAN_B AS NOMINAL
        , CICILAN_B AS CICILAN
        , HARGA_CICILAN_B AS NOMINAL_CICILAN
        , OUTSTANDING_B AS OUTSTANDING 
        , CASE WHEN STATUS = 'A' THEN 'Active'
            WHEN STATUS = 'I' THEN 'Inactive'
            END STATUS_NAME
		, STATUS
        , TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_INPUT_PINJAMAN
		, CREATED_DATE
        FROM MJ.MJ_T_POTONGAN 
        WHERE APP_ID=1 
        ORDER BY NAMA_PINJAMAN DESC) MTP
INNER JOIN MJ.MJ_T_POTONGAN_DETAIL MTPD ON MTP.HD_ID = MTPD.MJ_T_POTONGAN_ID AND MTP.NAMA_PINJAMAN = DECODE(MTPD.TIPE, 'POTONGAN', 'PINJAMAN', 'BS') 
INNER JOIN PER_ASSIGNMENTS_F PAF ON MTP.PERSON_ID = PAF.PERSON_ID AND PAF.PRIMARY_FLAG = 'Y' AND PAF.EFFECTIVE_END_DATE > TRUNC(SYSDATE)
INNER JOIN APPS.HR_OPERATING_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID AND SYSDATE BETWEEN NVL(DATE_FROM, SYSDATE) AND NVL(DATE_TO, SYSDATE)
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN PER_PEOPLE_F PPF ON MTP.PERSON_ID = PPF.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
WHERE NOMINAL > 0 AND MTPD.JUMLAH_POTONGAN != 0 
$queryfilter
ORDER BY HD_ID, MTP.PERSON_ID,MTPD.ID";
//echo $query;exit;
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	//$TransID=$row[0];
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_PERSON_ID']=$row[1];
	$record['DATA_PERUSAHAAN']=$row[2];
	$record['DATA_DEPT']=$row[3];
	$record['DATA_NAMA']=$row[4];
	$record['DATA_NAMA_PINJAMAN']=$row[5];
	$record['DATA_NOMINAL']=$row[6];
	$record['DATA_CICILAN']=$row[7];
	$record['DATA_NOMINAL_CICILAN']=$row[8];
	$record['DATA_OUTSTANDING']=$row[9];
	$record['DATA_STATUS']=$row[10];
	$record['DATA_TGL_INPUT_PINJAMAN']=$row[11];
	$record['DATA_TGL_POTONGAN']=$row[12];
	
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 $totalrow = $count;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>