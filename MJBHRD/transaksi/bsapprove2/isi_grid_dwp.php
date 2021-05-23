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

$tglnow=date('Y-m-d'); 
$bulannow=substr($tglnow, 5, 2);
$bulanName="";
$tahunnow=substr($tglnow, 0, 4);
if($bulannow == '01'){
	$bulanName = 'January';
} elseif ($bulannow == '02'){
	$bulanName = 'February';
} elseif ($bulannow == '03'){
	$bulanName = 'March';
} elseif ($bulannow == '04'){
	$bulanName = 'April';
} elseif ($bulannow == '05'){
	$bulanName = 'May';
} elseif ($bulannow == '06'){
	$bulanName = 'June';
} elseif ($bulannow == '07'){
	$bulanName = 'July';
} elseif ($bulannow == '08'){
	$bulanName = 'August';
} elseif ($bulannow == '09'){
	$bulanName = 'September';
} elseif ($bulannow == '10'){
	$bulanName = 'October';
} elseif ($bulannow == '11'){
	$bulanName = 'November';
} else {
	$bulanName = 'December';
}

$queryfilter=""; 

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$queryfilterRange= "";
	$tglskr=date('Y-m-d'); 
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
	$namaKaryawan = $_GET['pemohon'];
	$dept = $_GET['dept'];
	$noDok = $_GET['noBS'];
	$tipeBS = $_GET['tipeBS'];
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	
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
	if ($namaKaryawan!=''){
		$queryAssign = "SELECT ASSIGNMENT_ID 
		FROM APPS.PER_ASSIGNMENTS_F
		WHERE PERSON_ID = $namaKaryawan AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
		$resultAssign = oci_parse($con,$queryAssign);
		oci_execute($resultAssign);
		$rowAssign = oci_fetch_row($resultAssign);
		$assignment_id = $rowAssign[0]; 
		$queryfilter .=" AND BS.ASSIGNMENT_ID = $assignment_id ";
	}
	if ($noDok!=''){
		$queryfilter .=" AND BS.NO_BS LIKE '%$noDok%' ";
	}
	if ($tipeBS!='- Pilih -'){
		$queryfilter .=" AND BS.TIPE = '$tipeBS' ";
	}
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
} 


$queryAssignLogin = "SELECT ASSIGNMENT_ID 
FROM APPS.PER_ASSIGNMENTS_F
WHERE PERSON_ID = $emp_id AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
$resultAssignLogin = oci_parse($con,$queryAssignLogin);
oci_execute($resultAssignLogin);
$rowAssignLogin = oci_fetch_row($resultAssignLogin);
$assignment_id_login = $rowAssignLogin[0]; 

$queryLogin = "SELECT CASE WHEN PP.NAME LIKE '%FIN%' THEN '2'
                WHEN PP.NAME LIKE '%MGR%FIN%' THEN '4'
				ELSE '0' END AS TINGKAT
            , PPF.FULL_NAME, PP.NAME
        FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
        PER_PEOPLE_F PPF
        WHERE PAF.JOB_ID=PJ.JOB_ID
            AND PAF.POSITION_ID=PP.POSITION_ID
            AND PAF.EFFECTIVE_END_DATE > SYSDATE
            AND PRIMARY_FLAG = 'Y'
            AND PAF.PERSON_ID = PPF.PERSON_ID 
            AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
            AND PAF.ASSIGNMENT_ID = $assignment_id_login";
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$tingkat = $rowLogin[0]; 
//$tingkat = 4; 
//echo $tingkat;exit;
if($tingkat == 0){
	$query = "SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
	, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
	, POS.NAME AS JABATAN
	, HL.LOCATION_CODE PLANT
	, PG.NAME GRADE
	, BS.NOMINAL
	, BS.KETERANGAN, UPL.ID ATTACHMENT
	FROM MJ.MJ_T_BS BS
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
	AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
	AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
	AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
	LEFT JOIN MJ.MJ_TEMP_UPLOAD UPL ON BS.ID = UPL.TRANSAKSI_ID AND UPL.TRANSAKSI_KODE = 'BON'
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
	INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
	INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
	INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
	WHERE 1=1 and BS.PENANGGUNG_JAWAB = $emp_id and BS.TINGKAT IN (0,1) AND BS.STATUS = 'PROCESS' $queryfilter
	ORDER BY BS.ID";
}else if($tingkat == 2){
	$query = "SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
    , REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
    , REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
    , HL.LOCATION_CODE PLANT
    , PG.NAME GRADE
    , BS.NOMINAL
    , BS.KETERANGAN, UPL.ID ATTACHMENT
    FROM MJ.MJ_T_BS BS
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
    AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
    INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
    AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
    INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
    AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
    LEFT JOIN MJ.MJ_TEMP_UPLOAD UPL ON BS.ID = UPL.TRANSAKSI_ID AND UPL.TRANSAKSI_KODE = 'BON'
    INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
    INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
    INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
    INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
    INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
    WHERE 1=1 and (BS.ID IN (SELECT ID FROM MJ.MJ_T_BS WHERE NOMINAL > 500000) AND BS.TINGKAT = $tingkat) $queryfilter
	ORDER BY BS.ID";
}else{
	$query = "SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
    , REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
    , REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
    , HL.LOCATION_CODE PLANT
    , PG.NAME GRADE
    , BS.NOMINAL
    , BS.KETERANGAN, UPL.ID ATTACHMENT
    FROM MJ.MJ_T_BS BS
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
    AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
    INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
    AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
    INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
    AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
    LEFT JOIN MJ.MJ_TEMP_UPLOAD UPL ON BS.ID = UPL.TRANSAKSI_ID AND UPL.TRANSAKSI_KODE = 'BON'
    INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
    INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
    INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
    INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
    INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
    WHERE 1=1 AND BS.TINGKAT = $tingkat
	--and ((BS.ID IN (SELECT ID FROM MJ.MJ_T_BS WHERE NOMINAL <= 500000) AND BS.TINGKAT = 2) 
	--OR (BS.ID IN (SELECT ID FROM MJ.MJ_T_BS WHERE NOMINAL > 500000) AND BS.TINGKAT = $tingkat)) 
	$queryfilter
	ORDER BY BS.ID";
}
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
	$record['DATA_NOMINAL']=$row[8];
	$record['DATA_KETERANGAN']=$row[9];
	$record['DATA_ALASAN']='';
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'BON'";
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
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'BONMGRHRD'";
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
	$record['DATA_ATTACHMENT_HRD']=$dataAtt;
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