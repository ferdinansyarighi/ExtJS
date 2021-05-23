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

$queryfilter=""; 

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$queryfilterRange= "";
	$tglskr=date('Y-m-d'); 
	
	//$namaKaryawan = $_GET['pemohon'];
	$noDok = $_GET['noBS'];
	$tipeBS = $_GET['tipeBS'];
	//$perusahaan = $_GET['perusahaan'];
	//$dept = $_GET['dept'];
	//$plant = $_GET['plant'];
	$status = $_GET['status'];
	
	$tahunbaru=substr($tglskr, 0, 2);
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
	
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	
	
	$tgljtfrom = $_GET['tgljtfrom'];
	if($tgljtfrom != '' ){
		$hari=substr($tgljtfrom, 0, 2);
		$bulan=substr($tgljtfrom, 3, 2);
		$tahun=substr($tgljtfrom, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tgljtfrom = $tahun . "-" . $bulan . "-" . $hari;
	}else{
		$tgljtfrom = '2000-01-01';
	}
	$tgljtto = $_GET['tgljtto'];
	if($tgljtto != '' ){
		$hari=substr($tgljtto, 0, 2);
		$bulan=substr($tgljtto, 3, 2);
		$tahun=substr($tgljtto, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tgljtto = $tahun . "-" . $bulan . "-" . $hari;
	}else{
		$tgljtto = '2030-12-31';
	}
	$queryfilter .=" AND TO_CHAR(BS.TGL_JT, 'YYYY-MM-DD') >= '$tgljtfrom' AND TO_CHAR(BS.TGL_JT, 'YYYY-MM-DD') <= '$tgljtto' ";
	
	
	/* if ($namaKaryawan!=''){
		$queryAssign = "SELECT ASSIGNMENT_ID 
		FROM APPS.PER_ASSIGNMENTS_F
		WHERE PERSON_ID = $namaKaryawan AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
		$resultAssign = oci_parse($con,$queryAssign);
		oci_execute($resultAssign);
		$rowAssign = oci_fetch_row($resultAssign);
		$assignment_id = $rowAssign[0]; 
		$queryfilter .=" AND BS.ASSIGNMENT_ID = $assignment_id ";
	}  */
	if ($noDok!=''){
		$queryfilter .=" AND BS.NO_BS LIKE '%$noDok%' ";
	}
	if ($tipeBS!='- Pilih -'){
		$queryfilter .=" AND BS.TIPE = '$tipeBS' ";
	}
	/* if ($perusahaan!=''){
		//$queryfilter .=" AND PAF.ORGANIZATION_ID = $perusahaan ";
		$queryfilter .=" AND BS.PERUSAHAAN_BS = $perusahaan ";
	}
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
	if ($plant!=''){
		$queryfilter .=" AND PAF.LOCATION_ID = $plant ";
	} */
	if ($status!='- Pilih -'){
		$queryfilter .=" AND upper(BS.STATUS) = '$status' ";
	}
} 


$queryAssignLogin = "SELECT ASSIGNMENT_ID 
FROM APPS.PER_ASSIGNMENTS_F
WHERE PERSON_ID = $emp_id AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
$resultAssignLogin = oci_parse($con,$queryAssignLogin);
oci_execute($resultAssignLogin);
$rowAssignLogin = oci_fetch_row($resultAssignLogin);
$assignment_id_login = $rowAssignLogin[0]; 

$query = "
SELECT DISTINCT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
--, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
--, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
, PJ.NAME
, POS.NAME
, HL.LOCATION_CODE PLANT
, PG.NAME GRADE
, BS.NOMINAL
, BS.KETERANGAN
, BS.STATUS
, BS.TINGKAT
, CASE WHEN BS.STATUS = 'PROCESS' THEN '-'
    ELSE PPF3.FULL_NAME END AS APP_TERAKHIR
, NVL(BS.LAST_UPDATED_DATE, BS.CREATED_DATE) AS TGL_TERAKHIR
, CASE BS.STATUS
    WHEN 'Disapproved' THEN (SELECT MTA.KETERANGAN
    FROM MJ.MJ_T_APPROVAL MTA 
    WHERE MTA.TRANSAKSI_ID=BS.ID AND MTA.APP_ID= 1 AND MTA.TRANSAKSI_KODE='BON'
    AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=BS.ID AND APP_ID= 1 AND TRANSAKSI_KODE='BON')) ELSE '-' 
    END AS KET_DISAPP
, CASE WHEN BS.TINGKAT = 0 AND BS.STATUS = 'PROCESS' THEN PPF2.FULL_NAME
    WHEN (BS.TINGKAT = 4 or (BS.TINGKAT = 1 AND BS.STATUS = 'PROCESS')) THEN 'MGR FINANCE' 
    WHEN BS.TINGKAT = 2 THEN 'Tim Checker' 
    WHEN BS.TINGKAT = 3 THEN 'MGR HRD' 
    WHEN BS.TINGKAT = 5 AND BS.STATUS = 'Approved' and BS.TGL_PENCAIRAN IS NULL THEN 'Validasi Kasir' 
    ELSE '-' END AS NEXT_APPR
    ,BS.TIPE
    ,BS.TGL_PENCAIRAN
    ,
         CASE
            WHEN BSD.TIPE_EXPENSE IN ('CLOSE','POTONGAN') AND BS.ID = BSD.BS_HD_ID
            THEN
               TGL_EXPENSE
            WHEN BSD.TIPE_EXPENSE = 'EXPENSE CLAIM' AND BS.ID = BSD.BS_HD_ID AND  BS.NOMINAL - (SELECT SUM(BSD.NOMINAL) FROM MJ.MJ_T_BS_DT BSD WHERE BSD.BS_HD_ID = BS.ID) = 0
            THEN
               (SELECT MAX(BSD.TGL_EXPENSE) FROM MJ.MJ_T_BS_DT BSD WHERE BSD.BS_HD_ID = BS.ID)
            ELSE
               TO_DATE('','DD/MM/YY')
         END AS TANGGAL_CLOSE
    ,
         CASE
            WHEN BSD.TIPE_EXPENSE IN ('CLOSE','POTONGAN') AND BS.ID = BSD.BS_HD_ID
            THEN
               0
            WHEN BSD.TIPE_EXPENSE = 'EXPENSE CLAIM' AND BS.ID = BSD.BS_HD_ID
            THEN
               BS.NOMINAL - (SELECT SUM(BSD.NOMINAL) FROM MJ.MJ_T_BS_DT BSD WHERE BSD.BS_HD_ID = BS.ID)
            ELSE
               BS.NOMINAL
         END AS NOMINAL_OUTSTANDING
    ,BS.TGL_JT
    ,HOU2.NAME PERUSAHAAN_BS
    , BSD.NO_BBK
    , BSD.KETERANGAN
    , BSD.TIPE_EXPENSE
    , BSD.NOMINAL
    , DECODE(CBA.BANK_ACCOUNT_NUM, '', '', CBA.BANK_ACCOUNT_NUM|| ' - ' ||BB.BANK_NAME|| ' a/n ' ||CBA.BANK_ACCOUNT_NAME) BANK_PENERIMA
    , TO_CHAR(BSD.TGL_EXPENSE, 'DD-MON-YYYY')
    , TO_CHAR(BSD.CREATED_DATE, 'DD-MON-YYYY')
FROM MJ.MJ_T_BS BS
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
LEFT JOIN MJ.MJ_T_BS_DT BSD ON BS.ID = BSD.BS_HD_ID 
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
LEFT JOIN PER_PEOPLE_F PPF3 ON BS.LAST_UPDATED_BY = PPF3.PERSON_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON BS.ID = MTA.TRANSAKSI_ID AND MTA.TRANSAKSI_KODE = 'BON' AND BS.TINGKAT = MTA.TINGKAT
LEFT JOIN APPS.CE_BANK_ACCOUNTS CBA ON BSD.BANK_ACCOUNT_ID = CBA.BANK_ACCOUNT_ID
LEFT JOIN APPS.CEFV_BANK_BRANCHES BB ON CBA.BANK_BRANCH_ID = BB.BANK_BRANCH_ID
WHERE 1=1 and BS.ASSIGNMENT_ID = $assignment_id_login  
$queryfilter
ORDER BY BS.ID";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$TransID=$row[0];
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_BS']=$row[1];
	$record['DATA_PEMOHON']=$row[2];
	$record['DATA_PERUSAHAAN']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_JABATAN']=$row[5];
	$record['DATA_PLANT']=$row[6];
	$record['DATA_GRADE']=$row[7];
	$record['DATA_NOMINAL']='Rp '.number_format($row[8], 2, ',', '.');
	$record['DATA_KETERANGAN']=$row[9];
	$record['DATA_STATUS']=$row[10];
	$record['DATA_TINGKAT']=$row[11];
	$record['DATA_APP_TERAKHIR']=$row[12];
	$record['DATA_TGL_TERAKHIR']=$row[13];
	$record['DATA_KET_DISAPP']=$row[14];
	$record['DATA_NEXT_APP']=$row[15];
	$record['DATA_TIPE_BS']=$row[16];
	$record['DATA_TANGGAL_PENCAIRAN']=$row[17];
	$record['DATA_TANGGAL_CLOSE']=$row[18];
	$record['DATA_NOMINAL_OUTSTANDING']='Rp '.number_format($row[19], 2, ',', '.');
	$record['DATA_TANGGAL_JT']=$row[20];
	$record['DATA_PERUSAHAAN_BS']=$row[21];
	$record['DATA_NO_CM']=$row[22];
	$record['DATA_KET_NOL']=$row[23];
	$record['DATA_TIPE_NOL']=$row[24];
	$record['DATA_NOMINAL_NOL']='Rp '.number_format($row[25], 2, ',', '.');
	$record['DATA_BANK_PENERIMA']=$row[26];
	$record['DATA_TGL_NOL']=$row[27];
	$record['DATA_TGL_ACTION_NOL']=$row[28];
	
/*	echo "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " --AND TRANSAKSI_ID=$TransID 
	AND TRANSAKSI_KODE = 'BON'";exit;
*/
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'BON'";
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
		$docattachment = "<a href= " . PATHAPP . "/upload/BS/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
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
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'BONMGRHRD'";
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
		$docattachmenthr = "<a href= " . PATHAPP . "/upload/BS/" . $vFileuserhr.md5($vFiledatehr).$vFilesizehr.md5($vFilenamehr).".".$ekstensihr . " target=_blank>" . $vFilenamehr . "</a>";
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