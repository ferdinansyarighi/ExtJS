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
$rangeDate = 0;
if (isset($_GET['kategori']) || isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$queryfilterRange= "";
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$tffilter = $_GET['kategori'];
	$tglfrom = $_GET['tglfrom'];
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglto = $_GET['tglto'];
	$namaKaryawan = $_GET['namaKaryawan'];
	$plant = $_GET['plant'];
	$noDok = $_GET['noDok'];
	$Dept = $_GET['Dept'];
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	
	// $queryRange = "SELECT COUNT(-1) 
	// FROM (
		// SELECT LEVEL AS DNUM FROM DUAL
		// CONNECT BY (TO_DATE('$tglto', 'YYYY-MM-DD') - TO_DATE('$tglfrom', 'YYYY-MM-DD')) - LEVEL >= 0
	// )";
	// $resultRange = oci_parse($con, $queryRange);
	// oci_execute($resultRange);
	// while($rowRange = oci_fetch_row($resultRange))
	// {
		// $rangeDate=$rowRange[0];
	// }
	// for ($x = 0; $x < $rangeDate; $x++){
		// if($x == 0){
			// $queryfilterRange = " AND (TO_DATE('$tglfrom', 'YYYY-MM-DD')+$x BETWEEN TANGGAL_FROM AND TANGGAL_TO ";
		// } else {
			// $queryfilterRange .= " OR TO_DATE('$tglfrom', 'YYYY-MM-DD')+$x BETWEEN TANGGAL_FROM AND TANGGAL_TO ";
		// }
	// }
	// if ($queryfilterRange != ""){
		// $queryfilterRange .= ")";
	// }
	//echo $queryfilterRange;
	if ($tffilter!='All'){
		$queryfilter .=" AND KATEGORI LIKE '%$tffilter%' ";
	}
	if ($namaKaryawan!=''){
		$queryfilter .=" AND PERSON_ID = '$namaKaryawan' ";
	}
	if ($plant!=''){
		$queryfilter .=" AND PLANT LIKE '%$plant%' ";
	}
	if ($noDok!=''){
		$queryfilter .=" AND NOMOR_SIK LIKE '%$noDok%' ";
	}
	if ($Dept!=''){
		$queryfilter .=" AND (DEPARTEMEN LIKE '%$Dept%' OR DEPARTEMEN IS NULL)";
	}
} 

$resultCount = oci_parse($con, "SELECT COUNT(-1)
FROM MJ.MJ_M_USERAPPROVAL
WHERE STATUS='A' AND APP_ID= " . APPCODE . " AND EMP_ID=$emp_id ");
oci_execute($resultCount);
$rowCount = oci_fetch_row($resultCount);
$userCount = $rowCount[0];

//echo $userCount;

//Masuk ketika jabatan SPV atau Manager
if ($userCount==0){
	$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NO_SIK
	, PEMOHON AS DATA_PEMOHON
	, PLANT AS DATA_PLANT
	, KATEGORI AS DATA_KATEGORI
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, KETERANGAN AS DATA_KETERANGAN
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO 
	, DEPARTEMEN AS DATA_DEPARTEMEN
	, IJIN_KHUSUS AS DATA_KHUSUS
	, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') DATA_CREATED_DATE
	,(SELECT MTA.CREATED_BY
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
	,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TGL_APPROVED
	FROM MJ.MJ_T_SIK MTS
	WHERE TINGKAT=0 AND SPV='$emp_name'
	AND STATUS='1' 
	AND STATUS_DOK='In process' $queryfilter
	$queryfilterRange";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$TransID=$row[0];
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NO_SIK']=$row[1];
		$record['DATA_PEMOHON']=$row[2];
		$record['DATA_PLANT']=$row[3];
		$record['DATA_KATEGORI']=$row[4];
		$record['DATA_TGL_FROM']=$row[5];
		$record['DATA_TGL_TO']=$row[6];
		$record['DATA_KETERANGAN']=$row[7];
		$record['DATA_JAM_FROM']=$row[8];
		$record['DATA_JAM_TO']=$row[9];
		$record['DATA_ALASAN']='';
		$record['DATA_DEPARTEMEN']=$row[10];
		$record['DATA_KHUSUS']=$row[11];
		$record['DATA_CREATED_DATE']=$row[12];
		$record['DATA_USER_APPROVED']=$row[13];
		$record['DATA_TGL_APPROVED']=$row[14];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_M_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
		$resultAtt = oci_parse($con, $queryAtt);
		oci_execute($resultAtt);
		$doccount=0;
		$dataAtt='';
		while($rowAtt = oci_fetch_row($resultAtt))
		{
			$vTransID=$rowAtt[0];
			$vFilename=$rowAtt[1];
			$vFilesize=$rowAtt[2];
			$vFiletype=$rowAtt[3];
			$vFileuser=$rowAtt[4];
			$vFiledate=$rowAtt[5];
			$ekstensi	= end(explode(".", $vFilename));
			$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
			//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			//echo $docattachment;
			//$mail->addAttachment( $docattachment );
			$doccount++;
		}
		$record['DATA_ATTACHMENT']=$dataAtt;
		$data[]=$record;
		$countSpv++;
	}
	$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NO_SIK
	, PEMOHON AS DATA_PEMOHON
	, PLANT AS DATA_PLANT
	, KATEGORI AS DATA_KATEGORI
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, KETERANGAN AS DATA_KETERANGAN
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO 
	, DEPARTEMEN AS DATA_DEPARTEMEN
	, IJIN_KHUSUS AS DATA_KHUSUS
	, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') DATA_CREATED_DATE
	,(SELECT MTA.CREATED_BY
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
	,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TGL_APPROVED
	FROM MJ.MJ_T_SIK MTS
	WHERE TINGKAT=1 AND MANAGER='$emp_name'
	AND STATUS='1' 
	AND STATUS_DOK='In process' $queryfilter
	$queryfilterRange";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$TransID=$row[0];
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NO_SIK']=$row[1];
		$record['DATA_PEMOHON']=$row[2];
		$record['DATA_PLANT']=$row[3];
		$record['DATA_KATEGORI']=$row[4];
		$record['DATA_TGL_FROM']=$row[5];
		$record['DATA_TGL_TO']=$row[6];
		$record['DATA_KETERANGAN']=$row[7];
		$record['DATA_JAM_FROM']=$row[8];
		$record['DATA_JAM_TO']=$row[9];
		$record['DATA_ALASAN']='';
		$record['DATA_DEPARTEMEN']=$row[10];
		$record['DATA_KHUSUS']=$row[11];
		$record['DATA_CREATED_DATE']=$row[12];
		$record['DATA_USER_APPROVED']=$row[13];
		$record['DATA_TGL_APPROVED']=$row[14];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_M_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
		$resultAtt = oci_parse($con, $queryAtt);
		oci_execute($resultAtt);
		$doccount=0;
		$dataAtt='';
		while($rowAtt = oci_fetch_row($resultAtt))
		{
			$vTransID=$rowAtt[0];
			$vFilename=$rowAtt[1];
			$vFilesize=$rowAtt[2];
			$vFiletype=$rowAtt[3];
			$vFileuser=$rowAtt[4];
			$vFiledate=$rowAtt[5];
			$ekstensi	= end(explode(".", $vFilename));
			$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
			//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			//echo $docattachment;
			//$mail->addAttachment( $docattachment );
			$doccount++;
		}
		$record['DATA_ATTACHMENT']=$dataAtt;
		$data[]=$record;
		$countMan++;
	}
} else {
	$resultCount = oci_parse($con, "SELECT NVL(TINGKAT, 0) AS TINGKAT
	FROM MJ.MJ_M_USERAPPROVAL
	WHERE STATUS='A' AND APP_ID= " . APPCODE . " AND EMP_ID=$emp_id ");
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$tingkat = $rowCount[0];
	
	$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NO_SIK
	, PEMOHON AS DATA_PEMOHON
	, PLANT AS DATA_PLANT
	, KATEGORI AS DATA_KATEGORI
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, KETERANGAN AS DATA_KETERANGAN
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO 
	, DEPARTEMEN AS DATA_DEPARTEMEN
	, IJIN_KHUSUS AS DATA_KHUSUS
	, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') DATA_CREATED_DATE
	,(SELECT MTA.CREATED_BY
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
	,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TGL_APPROVED
	FROM MJ.MJ_T_SIK MTS
	WHERE TINGKAT=0 AND SPV='$emp_name'
	AND STATUS='1' 
	AND STATUS_DOK='In process' $queryfilter
	$queryfilterRange";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$TransID=$row[0];
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NO_SIK']=$row[1];
		$record['DATA_PEMOHON']=$row[2];
		$record['DATA_PLANT']=$row[3];
		$record['DATA_KATEGORI']=$row[4];
		$record['DATA_TGL_FROM']=$row[5];
		$record['DATA_TGL_TO']=$row[6];
		$record['DATA_KETERANGAN']=$row[7];
		$record['DATA_JAM_FROM']=$row[8];
		$record['DATA_JAM_TO']=$row[9];
		$record['DATA_ALASAN']='';
		$record['DATA_DEPARTEMEN']=$row[10];
		$record['DATA_KHUSUS']=$row[11];
		$record['DATA_CREATED_DATE']=$row[12];
		$record['DATA_USER_APPROVED']=$row[13];
		$record['DATA_TGL_APPROVED']=$row[14];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_M_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
		$resultAtt = oci_parse($con, $queryAtt);
		oci_execute($resultAtt);
		$doccount=0;
		$dataAtt='';
		while($rowAtt = oci_fetch_row($resultAtt))
		{
			$vTransID=$rowAtt[0];
			$vFilename=$rowAtt[1];
			$vFilesize=$rowAtt[2];
			$vFiletype=$rowAtt[3];
			$vFileuser=$rowAtt[4];
			$vFiledate=$rowAtt[5];
			$ekstensi	= end(explode(".", $vFilename));
			$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
			//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			//echo $docattachment;
			//$mail->addAttachment( $docattachment );
			$doccount++;
		}
		$record['DATA_ATTACHMENT']=$dataAtt;
		$data[]=$record;
		$countSpv++;
	}
	$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NO_SIK
	, PEMOHON AS DATA_PEMOHON
	, PLANT AS DATA_PLANT
	, KATEGORI AS DATA_KATEGORI
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, KETERANGAN AS DATA_KETERANGAN
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO 
	, DEPARTEMEN AS DATA_DEPARTEMEN
	, IJIN_KHUSUS AS DATA_KHUSUS
	, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') DATA_CREATED_DATE
	,(SELECT MTA.CREATED_BY
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
	,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TGL_APPROVED
	FROM MJ.MJ_T_SIK MTS
	WHERE TINGKAT=1 AND MANAGER='$emp_name'
	AND STATUS='1' 
	AND STATUS_DOK='In process' $queryfilter
	$queryfilterRange";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$TransID=$row[0];
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NO_SIK']=$row[1];
		$record['DATA_PEMOHON']=$row[2];
		$record['DATA_PLANT']=$row[3];
		$record['DATA_KATEGORI']=$row[4];
		$record['DATA_TGL_FROM']=$row[5];
		$record['DATA_TGL_TO']=$row[6];
		$record['DATA_KETERANGAN']=$row[7];
		$record['DATA_JAM_FROM']=$row[8];
		$record['DATA_JAM_TO']=$row[9];
		$record['DATA_ALASAN']='';
		$record['DATA_DEPARTEMEN']=$row[10];
		$record['DATA_KHUSUS']=$row[11];
		$record['DATA_CREATED_DATE']=$row[12];
		$record['DATA_USER_APPROVED']=$row[13];
		$record['DATA_TGL_APPROVED']=$row[14];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_M_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
		$resultAtt = oci_parse($con, $queryAtt);
		oci_execute($resultAtt);
		$doccount=0;
		$dataAtt='';
		while($rowAtt = oci_fetch_row($resultAtt))
		{
			$vTransID=$rowAtt[0];
			$vFilename=$rowAtt[1];
			$vFilesize=$rowAtt[2];
			$vFiletype=$rowAtt[3];
			$vFileuser=$rowAtt[4];
			$vFiledate=$rowAtt[5];
			$ekstensi	= end(explode(".", $vFilename));
			$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
			//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			//echo $docattachment;
			//$mail->addAttachment( $docattachment );
			$doccount++;
		}
		$record['DATA_ATTACHMENT']=$dataAtt;
		$data[]=$record;
		$countMan++;
	}
	$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NOSIK
	, PEMOHON AS DATA_PEMOHON
	, PLANT AS DATA_PLANT
	, KATEGORI AS DATA_KATEGORI
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, KETERANGAN AS DATA_KETERANGAN 
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO 
	, DEPARTEMEN AS DATA_DEPARTEMEN
	, IJIN_KHUSUS AS DATA_KHUSUS
	, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') DATA_CREATED_DATE
	,(SELECT MTA.CREATED_BY
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
	,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TGL_APPROVED
	FROM MJ.MJ_T_SIK MTS
	WHERE 1=1 AND TINGKAT IN (SELECT NVL(TINGKAT, 0) AS TINGKAT
	FROM MJ.MJ_M_USERAPPROVAL
	WHERE STATUS='A' AND APP_ID= " . APPCODE . " AND EMP_ID=$emp_id) 
	AND (PLANT IN (SELECT HL.LOCATION_CODE 
        FROM MJ.MJ_M_USERAPPROVAL MMU
        INNER JOIN MJ.MJ_M_AREA MMA ON MMA.NAMA_AREA=MMU.NAMA_AREA AND MMA.APP_ID=MMU.APP_ID 
        INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
        INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
        WHERE MMU.APP_ID=" . APPCODE . " AND EMP_ID=$emp_id) OR 3 IN (SELECT NVL(TINGKAT, 0) AS TINGKAT
		FROM MJ.MJ_M_USERAPPROVAL
		WHERE STATUS='A' AND APP_ID= 1 AND EMP_ID=$emp_id))
	AND STATUS_DOK='In process' $queryfilter
	$queryfilterRange";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$TransID=$row[0];
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NO_SIK']=$row[1];
		$record['DATA_PEMOHON']=$row[2];
		$record['DATA_PLANT']=$row[3];
		$record['DATA_KATEGORI']=$row[4];
		$record['DATA_TGL_FROM']=$row[5];
		$record['DATA_TGL_TO']=$row[6];
		$record['DATA_KETERANGAN']=$row[7];
		$record['DATA_JAM_FROM']=$row[8];
		$record['DATA_JAM_TO']=$row[9];
		$record['DATA_ALASAN']='';
		$record['DATA_DEPARTEMEN']=$row[10];
		$record['DATA_KHUSUS']=$row[11];
		$record['DATA_CREATED_DATE']=$row[12];
		$record['DATA_USER_APPROVED']=$row[13];
		$record['DATA_TGL_APPROVED']=$row[14];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_M_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
		$resultAtt = oci_parse($con, $queryAtt);
		oci_execute($resultAtt);
		$doccount=0;
		$dataAtt='';
		while($rowAtt = oci_fetch_row($resultAtt))
		{
			$vTransID=$rowAtt[0];
			$vFilename=$rowAtt[1];
			$vFilesize=$rowAtt[2];
			$vFiletype=$rowAtt[3];
			$vFileuser=$rowAtt[4];
			$vFiledate=$rowAtt[5];
			$ekstensi	= end(explode(".", $vFilename));
			$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . ">" . $vFilename . "</a>";
			//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			//echo $docattachment;
			//$mail->addAttachment( $docattachment );
			$doccount++;
		}
		$record['DATA_ATTACHMENT']=$dataAtt;
		$data[]=$record;
		$count++;
	}
	// $query = "SELECT DISTINCT ID AS hd_id
	// , NOMOR_SIK AS DATA_NOSIK
	// , PEMOHON AS DATA_PEMOHON
	// , PLANT AS DATA_PLANT
	// , KATEGORI AS DATA_KATEGORI
	// , TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	// , TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	// , KETERANGAN AS DATA_KETERANGAN 
	// , JAM_FROM AS DATA_JAM_FROM
	// , JAM_TO AS DATA_JAM_TO 
	// , DEPARTEMEN AS DATA_DEPARTEMEN
	// , IJIN_KHUSUS AS DATA_KHUSUS
	// FROM MJ.MJ_T_SIK 
	// WHERE 1=1 AND TINGKAT IN (SELECT NVL(TINGKAT, 0) AS TINGKAT
	// FROM MJ.MJ_M_USERAPPROVAL
	// WHERE STATUS='A' AND TINGKAT=3 AND APP_ID= " . APPCODE . " AND EMP_ID=$emp_id)
	// AND STATUS_DOK='In process' $queryfilter";
	// //echo $query;
	// $result = oci_parse($con,$query);
	// oci_execute($result);
	// while($row = oci_fetch_row($result))
	// {
		// $TransID=$row[0];
		// $record = array();
		// $record['HD_ID']=$row[0];
		// $record['DATA_NO_SIK']=$row[1];
		// $record['DATA_PEMOHON']=$row[2];
		// $record['DATA_PLANT']=$row[3];
		// $record['DATA_KATEGORI']=$row[4];
		// $record['DATA_TGL_FROM']=$row[5];
		// $record['DATA_TGL_TO']=$row[6];
		// $record['DATA_KETERANGAN']=$row[7];
		// $record['DATA_JAM_FROM']=$row[8];
		// $record['DATA_JAM_TO']=$row[9];
		// $record['DATA_ALASAN']='';
		// $record['DATA_DEPARTEMEN']=$row[10];
		// $record['DATA_KHUSUS']=$row[11];
		
		// $queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		// FROM MJ.MJ_M_UPLOAD
		// WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
		// $resultAtt = oci_parse($con, $queryAtt);
		// oci_execute($resultAtt);
		// $doccount=0;
		// $dataAtt='';
		// while($rowAtt = oci_fetch_row($resultAtt))
		// {
			// $vTransID=$rowAtt[0];
			// $vFilename=$rowAtt[1];
			// $vFilesize=$rowAtt[2];
			// $vFiletype=$rowAtt[3];
			// $vFileuser=$rowAtt[4];
			// $vFiledate=$rowAtt[5];
			// $ekstensi	= end(explode(".", $vFilename));
			// $docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . ">" . $vFilename . "</a>";
			// //$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			// if($doccount==0){
				// $dataAtt = $docattachment;
			// } else {
				// $dataAtt .= ", " . $docattachment;
			// }
			// //echo $docattachment;
			// //$mail->addAttachment( $docattachment );
			// $doccount++;
		// }
		// $record['DATA_ATTACHMENT']=$dataAtt;
		// $data[]=$record;
		// $count++;
	// }
}

if ($count==0 && $countSpv==0 && $countMan==0)
{
	$data="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>