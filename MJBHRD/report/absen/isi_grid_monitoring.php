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
if (isset($_GET['status']) || isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$status = $_GET['status'];
	$plant = $_GET['plant'];
	$dept = $_GET['dept'];
	$noDok = $_GET['noDok'];
	$tglfrom = $_GET['tglfrom'];
	if ($tglfrom==""){	
		$tglfrom = "01/01/15";
	}
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglto = $_GET['tglto'];
	if ($tglto==""){	
		$tglto = "31/12/99";
	}
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$tglto'  ";
	if($status!='All'){
		if($status!='Inactive'){
			$queryfilter .= " AND MTSD.STATUS_DOK='$status'";
		} else {
			$queryfilter .= " AND MTS.STATUS=0";
		}
	}
	if($plant!=''){
		$queryfilter .= " AND MTS.PLANT='$plant'";
	}
	if($noDok!=''){
		$queryfilter .= " AND MTS.NOMOR_SPL='$noDok'";
	}
	if($dept!=''){
		$queryfilter .= " AND MTSD.DEPARTEMEN='$dept'";
	}
} 

$query = "SELECT MTS.ID AS hd_id
, MTS.NOMOR_SPL AS DATA_NO_SPL
, MTSD.NAMA AS DATA_NAMA
, MTS.PLANT AS DATA_PLANT
, TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') AS DATA_TGL_SPL
, MTSD.JAM_FROM AS DATA_JAM_FROM
, MTSD.JAM_TO AS DATA_JAM_TO
, MTSD.PEKERJAAN AS DATA_PEKERJAAN 
, MTSD.STATUS_DOK AS DATA_STATUS
,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) AS DATA_TANGGAL_APPROVED
,(SELECT MTA.CREATED_BY
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) AS DATA_USER_APPROVED
, CASE MTSD.STATUS_DOK 
WHEN 'Approved' THEN '-' 
WHEN 'Disapproved' THEN '-' 
ELSE (CASE MTSD.TINGKAT WHEN 0 THEN MTS.SPV WHEN 1 THEN MTS.MANAGER ELSE NVL(MMUP.FULL_NAME, '-') END) END AS DATA_PIC_APPROVED
, CASE MTSD.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.CREATED_BY 
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) ELSE '-' END AS DATA_PIC_DISAPPROVED
, CASE MTSD.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.KETERANGAN
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) ELSE '-' END AS DATA_ALASAN
, MTSD.DEPARTEMEN
, to_char (mts.created_date, 'YYYY-MM-DD') tgl_buat
FROM MJ.MJ_T_SPL MTS
INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTSD.TINGKAT AND MMUP.APP_ID=" . APPCODE . "
        AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
        FROM MJ.MJ_M_AREA MMA
        INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
        INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
        WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
WHERE 1=1 AND (MTS.PEMBUAT='$emp_name' OR MTSD.PERSON_ID='$emp_id') $queryfilter";
//echo $query;EXIT;
$result = oci_parse($con,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_SPL']=$row[1];
	$record['DATA_NAMA']=$row[2];
	$record['DATA_PLANT']=$row[3];
	$record['DATA_TGL_SPL']=$row[4];
	$record['DATA_JAM_FROM']=$row[5];
	$record['DATA_JAM_TO']=$row[6];
	$record['DATA_PEKERJAAN']=$row[7];
	$record['DATA_STATUS']=$row[8];
	$record['DATA_TANGGAL']=$row[9];
	$record['DATA_USER_APP']=$row[10];
	$record['DATA_PIC_APPROVED']=$row[11];
	$record['DATA_PIC_DISAPPROVED']=$row[12];
	$record['DATA_ALASAN']=$row[13];
	$record['DATA_DEPT']=$row[14];
	$record['DATA_CREATEDATE']=$row[15];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>