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
$dokstat = "";
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

$queryfilter=""; 
	
$noDok = $_GET['noPinjaman'];
$tipePinjaman = $_GET['tipePinjaman'];
$pemohon = $_GET['pemohon'];
$tglfrom = $_GET['tglfrom'];
$tglto = $_GET['tglto'];

$hari="";
$bulan="";
$tahun=""; 
$queryfilter=""; 
$queryfilterRange= "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);

if ($tglfrom!='')
{
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
}

if ($tglto!='')	
{
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);

	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
}

if ($noDok!=''){
	$queryfilter .=" AND MTP.NOMOR_PINJAMAN LIKE '%$noDok%' ";
}
if ($tipePinjaman!='- Pilih -'){
	$queryfilter .=" AND MTP.TIPE LIKE '%$tipePinjaman%'";
}
if ($pemohon!='- Pilih -'){
	$queryfilter .=" AND MTP.PERSON_ID = $emp_id";
}

if ($tglfrom!='' && $tglto!=''){
	$queryfilter .=" AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') <= '$tglto' ";
}
else if ($tglfrom=='' && $tglto!=''){
	$queryfilter .=" AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') >= '1994-11-27' AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') <= '$tglto' ";
}
else if ($tglfrom!='' && $tglto==''){
	$queryfilter .=" AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') <= '9999-11-27' ";
}



/*$queryAssignLogin = "SELECT ASSIGNMENT_ID 
FROM APPS.PER_ASSIGNMENTS_F
WHERE PERSON_ID = $emp_id AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
$resultAssignLogin = oci_parse($con,$queryAssignLogin);
oci_execute($resultAssignLogin);
$rowAssignLogin = oci_fetch_row($resultAssignLogin);
$assignment_id_login = $rowAssignLogin[0]; */

$query = "SELECT DISTINCT MTP.ID, MTP.NOMOR_PINJAMAN, PPF.FULL_NAME PEMOHON, PPF2.FULL_NAME MANAGER, MTP.TIPE, HOU.NAME PERUSAHAAN
, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
, PG.NAME GRADE
, HL.LOCATION_CODE LOCATION
, MTP.TANGGAL_PINJAMAN TGL_PENGAJUAN
, DECODE (MTP.START_POTONGAN_BULAN,
    1, 'Januari',
    2, 'Februari',
    3, 'Maret',
    4, 'April',
    5, 'Mei',
    6, 'Juni',
    7, 'Juli',
    8, 'Agustus',
    9, 'September',
    10,'Oktober',
    11,'November',
    12,'Desember')
|| ' ' || MTP.START_POTONGAN_TAHUN AS START_POTONGAN
, MTP.JUMLAH_PINJAMAN NOMINAL_PINJAMAN
, MTP.JUMLAH_CICILAN_AWAL CICILAN
, MTP.NOMINAL NOMINAL_CICILAN
, MTP.TUJUAN_PINJAMAN
, MTP.STATUS_DOKUMEN
, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'Disapproved' THEN 
		(
			SELECT PPF.FULL_NAME
            FROM MJ.MJ_T_APPROVAL MTA, PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
            WHERE TRANSAKSI_KODE = 'PINJAMAN'
            AND TRANSAKSI_ID = MTP.ID
            AND PPF.PERSON_ID = MTA.EMP_ID
            AND MTA.STATUS = MTP.STATUS_DOKUMEN
            AND MTP.STATUS_DOKUMEN = 'Disapproved'
            AND MTP.ID = 2
        )
  ELSE PPF3.FULL_NAME END AS APPR_TERAKHIR
  , CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'Disapproved' THEN 
		(
			SELECT MTA.CREATED_DATE
            FROM MJ.MJ_T_APPROVAL MTA, PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
            WHERE TRANSAKSI_KODE = 'PINJAMAN'
            AND TRANSAKSI_ID = MTP.ID
            AND PPF.PERSON_ID = MTA.EMP_ID
            AND MTA.STATUS = MTP.STATUS_DOKUMEN
            AND MTP.STATUS_DOKUMEN = 'Disapproved'
            AND MTP.ID = 2
        )
  ELSE MTA.CREATED_DATE END AS TGL_APPR_TERAKHIR
, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'In process' THEN PPF2.FULL_NAME
    WHEN MTP.TINGKAT = 1 THEN 'MGR HRD' 
    WHEN MTP.TINGKAT = 2  THEN 'Direksi' 
    WHEN MTP.TINGKAT = 3  THEN 'MGR FINANCE' 
    WHEN MTP.TINGKAT = 4  THEN 'Validasi Kasir' 
  ELSE '-' END AS NEXT_APPR
, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'Disapproved' THEN 
		(
			SELECT MTA.KETERANGAN
            FROM MJ.MJ_T_APPROVAL MTA, PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
            WHERE TRANSAKSI_KODE = 'PINJAMAN'
            AND TRANSAKSI_ID = MTP.ID
            AND PPF.PERSON_ID = MTA.EMP_ID
            AND MTA.STATUS = MTP.STATUS_DOKUMEN
            AND MTP.STATUS_DOKUMEN = 'Disapproved'
            AND MTP.ID = 2
        )
  ELSE MTA.KETERANGAN END AS KETERANGAN
, MTP.TINGKAT
FROM MJ.MJ_T_PINJAMAN MTP
INNER JOIN PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
INNER JOIN PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN  MJ.MJ_T_APPROVAL MTA ON MTA.TRANSAKSI_ID = MTP.ID AND TRANSAKSI_KODE = 'PINJAMAN' AND DECODE(MTP.TINGKAT,5,MTP.TINGKAT-1,MTP.TINGKAT) = MTA.TINGKAT
LEFT JOIN  PER_PEOPLE_F PPF3 ON MTA.CREATED_BY = PPF3.PERSON_ID AND PPF3.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF3.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
WHERE 1=1
$queryfilter
ORDER BY MTP.ID";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$TransID=$row[0];
	$dokstat=$row[16];
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_PINJAMAN']=$row[1];
	$record['DATA_PEMOHON']=$row[2];
	$record['DATA_MGR']=$row[3];
	$record['DATA_TIPE_PINJAMAN']=$row[4];
	$record['DATA_PERUSAHAAN']=$row[5];
	$record['DATA_DEPARTEMEN']=$row[6];
	$record['DATA_JABATAN']=$row[7];
	$record['DATA_GRADE']=$row[8];
	$record['DATA_LOCATION']=$row[9];
	$record['DATA_TGL_PENGAJUAN']=$row[10];
	$record['DATA_START_POTONGAN']=$row[11];
	$record['DATA_NOMINAL_PINJAMAN']=$row[12];
	$record['DATA_CICILAN']=$row[13];
	$record['DATA_NOMINAL_CICILAN']=$row[14];
	$record['DATA_TUJUAN_PINJAMAN']=$row[15];
	$record['DATA_STATUS']=$row[16];
	$record['DATA_APPR_TERAKHIR']=$row[17];
	$record['DATA_TGL_APPR_TERAKHIR']=$row[18];
	$record['DATA_APPR_SELANJUTNYA']=$row[19];
	$record['DATA_KETERANGAN']=$row[20];
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'PINJAMAN'";
	$resultAtt = oci_parse($con, $queryAtt);
	oci_execute($resultAtt);
	$doccount=0;
	$dataAtt='';
	while($rowAtt = oci_fetch_row($resultAtt))
	{
		$vTransID =$rowAtt[0];
		$vFilename=$rowAtt[1];
		$vFilesize=$rowAtt[2];
		$vFiletype=$rowAtt[3];
		$vFileuser=$rowAtt[4];
		$vFiledate=$rowAtt[5];
		$ekstensi	= end(explode(".", $vFilename));
		$docattachment = "<a href= " . PATHAPP . "/upload/pinjaman/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
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
	
	$queryAtthr = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'PINJAMANHRD'";
	$resultAtthr = oci_parse($con, $queryAtthr);
	oci_execute($resultAtthr);
	$doccounthr=0;
	$dataAtthr='';
	while($rowAtthr = oci_fetch_row($resultAtthr))
	{
		$vTransIDhr =$rowAtthr[0];
		$vFilenamehr=$rowAtthr[1];
		$vFilesizehr=$rowAtthr[2];
		$vFiletypehr=$rowAtthr[3];
		$vFileuserhr=$rowAtthr[4];
		$vFiledatehr=$rowAtthr[5];
		$ekstensihr	= end(explode(".", $vFilenamehr));
		$docattachmenthr = "<a href= " . PATHAPP . "/upload/pinjaman/" . $vFileuserhr.md5($vFiledatehr).$vFilesizehr.md5($vFilenamehr).".".$ekstensihr . " target=_blank>" . $vFilenamehr . "</a>";
		//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		if($doccounthr==0){
			$dataAtthr = $docattachmenthr;
		} else {
			$dataAtthr .= ", " . $docattachmenthr;
		}
		//echo $docattachment;
		//$mail->addAttachment( $docattachment );
		$doccounthr++;
	}
	$record['DATA_ATTACHMENT_HR']=$dataAtthr;

	$queryAttdir = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'PINJAMANDIR'";
	$resultAttdir = oci_parse($con, $queryAttdir);
	oci_execute($resultAttdir);
	$doccountdir=0;
	$dataAttdir='';
	while($rowAttdir = oci_fetch_row($resultAttdir))
	{
		$vTransIDdir =$rowAttdir[0];
		$vFilenamedir=$rowAttdir[1];
		$vFilesizedir=$rowAttdir[2];
		$vFiletypedir=$rowAttdir[3];
		$vFileuserdir=$rowAttdir[4];
		$vFiledatedir=$rowAttdir[5];
		$ekstensidir = end(explode(".", $vFilenamedir));
		$docattachmentdir = "<a href= " . PATHAPP . "/upload/pinjaman/" . $vFileuserdir.md5($vFiledatedir).$vFilesizedir.md5($vFilenamedir).".".$ekstensidir . " target=_blank>" . $vFilenamedir . "</a>";
		//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		if($doccounthr==0){
			$dataAttdir = $docattachmentdir;
		} else {
			$dataAttdir .= ", " . $docattachmentdir;
		}
		//echo $docattachment;
		//$mail->addAttachment( $docattachment );
		$doccountdir++;
	}
	$record['DATA_ATTACHMENT_DIR']=$dataAttdir;
	
	$data[]=$record;
	$count++;
}

if ($count==0)
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