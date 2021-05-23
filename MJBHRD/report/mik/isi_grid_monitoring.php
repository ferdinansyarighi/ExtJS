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
	$empname= $emp_name;
	$empname = str_replace("'", "%", $emp_name); //LFN

	// echo $empname;exit;

	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$status = $_GET['status'];
	$plant = $_GET['plant'];
	$noDok = $_GET['noDok'];
	$dept = $_GET['dept'];
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
	$queryfilter .=" AND TO_CHAR(MTS.TANGGAL_FROM, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTS.TANGGAL_FROM, 'YYYY-MM-DD') <= '$tglto'  ";
	if($status!='All'){
		if($status!='Inactive'){
			$queryfilter .= " AND MTS.STATUS_DOK='$status'";
		} else {
			$queryfilter .= " AND MTS.STATUS=0";
		}
	}
	if($plant!=''){
		$queryfilter .= " AND MTS.PLANT='$plant'";
	}
	if($noDok!=''){
		$queryfilter .= " AND MTS.NOMOR_SIK='$noDok'";
	}
	if($dept!=''){
		$queryfilter .= " AND MTS.DEPARTEMEN='$dept'";
	}
} 

$query = "SELECT DISTINCT MTS.ID AS hd_id
, MTS.NOMOR_SIK AS DATA_NO_SIK
, MTS.PEMOHON AS DATA_PEMOHON
, MTS.KATEGORI AS DATA_KATEGORI
, TO_CHAR(MTS.TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
, TO_CHAR(MTS.TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
, MTS.JAM_FROM AS DATA_JAM_FROM
, MTS.JAM_TO AS DATA_JAM_TO
, MTS.KETERANGAN AS DATA_KETERANGAN 
, MTS.STATUS_DOK AS DATA_STATUS
,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TANGGAL_APPROVED
,(SELECT MTA.CREATED_BY
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
, CASE MTS.STATUS_DOK 
WHEN 'Approved' THEN '-' 
WHEN 'Disapproved' THEN '-' 
ELSE (CASE MTS.TINGKAT WHEN 0 THEN MTS.SPV WHEN 1 THEN MTS.MANAGER ELSE (CASE MTS.TINGKAT WHEN 2 THEN NVL(MMUP.FULL_NAME, '-') ELSE NVL(MMUP2.FULL_NAME, '-') END) END) END AS DATA_PIC_APPROVED
, CASE MTS.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.CREATED_BY
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) ELSE '-' END AS DATA_PIC_DISAPPROVED
, CASE MTS.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.KETERANGAN
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) ELSE '-' END AS DATA_ALASAN
, NVL(MTS.IJIN_KHUSUS, '') AS IJIN_KHUSUS
, MTS.DEPARTEMEN
, to_char (mts.created_date, 'YYYY-MM-DD')
FROM MJ.MJ_T_SIK MTS
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTS.TINGKAT AND MMUP.APP_ID=" . APPCODE . "
        AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
        FROM MJ.MJ_M_AREA MMA
        INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
        INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
        WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP2 ON MMUP2.STATUS='A' AND MMUP2.TINGKAT=MTS.TINGKAT AND MMUP2.APP_ID=" . APPCODE . "
		AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
		FROM MJ.MJ_M_AREA MMA
		INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
		INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
		WHERE MMA.APP_ID=MMUP2.APP_ID AND MMA.NAMA_AREA=MMUP2.NAMA_AREA )
WHERE 1=1 AND (MTS.PEMBUAT like '%$empname%' OR MTS.PERSON_ID='$emp_id') AND (MTS.STATUS != 0 OR MTS.STATUS_DOK != 'Inactive') $queryfilter";
// echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_SIK']=$row[1];
	$record['DATA_PEMOHON']=$row[2];
	$record['DATA_KATEGORI']=$row[3];
	$record['DATA_TGL_FROM']=$row[4];
	$record['DATA_TGL_TO']=$row[5];
	$record['DATA_JAM_FROM']=$row[6];
	$record['DATA_JAM_TO']=$row[7];
	$record['DATA_KETERANGAN']=$row[8];
	$record['DATA_STATUS']=$row[9];
	$record['DATA_TANGGAL']=$row[10];
	$record['DATA_USER_APP']=$row[11];
	$record['DATA_PIC_APPROVED']=$row[12];
	$record['DATA_PIC_DISAPPROVED']=$row[13];
	$record['DATA_ALASAN']=$row[14];
	$record['DATA_KHUSUS']=$row[15];
	$record['DATA_DEPT']=$row[16];
	$record['DATA_CREATEDATE']=$row[17];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>