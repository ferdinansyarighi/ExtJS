<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 
// deklarasi variable dan session
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
  
$data = "gagal";
$tglskr=date('Y-m-d'); 
$tahunGenNo=substr($tglskr, 0, 4);
$bulanGenNo=substr($tglskr, 5, 2);
if(isset($_POST['emp_id'])){
	$formtype=$_POST['formtype'];
	$hdid=$_POST['hd_id'];
	$employ_id=$_POST['emp_id'];
	$pembuat= $emp_id;
	$alasan=$_POST['alasan'];
	$aktif=$_POST['aktif'];
	if ($aktif == 'false')
	{
		$aktif = 'N';
	}
	else
	{
		$aktif = 'Y';
	}
}

$alasan = str_replace("'", "`", $alasan);

if ($formtype=='tambah')
{
	$queryCek = "SELECT COUNT(-1) FROM MJ.MJ_PERMINTAAN_REKENING WHERE SUBSTR(NO_REQUEST, 11, 4) = '$tahunGenNo'";
	$resultCek = oci_parse($con,$queryCek);
	oci_execute($resultCek);
	$rowCek = oci_fetch_row($resultCek);
	$jumCek = $rowCek[0]; 
	
	if($jumCek > 0){
		$querycount = "SELECT MAX(REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 4)) + 1 NO_REQ 
		FROM MJ.MJ_PERMINTAAN_REKENING 
		WHERE SUBSTR(NO_REQUEST, 11, 4) = '$tahunGenNo'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$lastNo = $rowcount[0]; 
	} else {
		$lastNo = "1"; 
	}
	
/*	$queryPosisi = "SELECT COUNT(-1)
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
	AND (PP.NAME LIKE '%MGR%')
	AND PPF.PERSON_ID=$emp_id";
	$resultPosisi = oci_parse($con,$queryPosisi);
	oci_execute($resultPosisi);
	$rowPosisi = oci_fetch_row($resultPosisi);
	$jumPosisi = $rowPosisi[0]; 
	
	if ($jumPosisi > 0){
		$tingkat = 1;
	} else {
		$tingkat = 0;
	}
	
	$queryPlant = "SELECT ADDRESS_LINE_3 
	FROM APPS.HR_LOCATIONS 
	WHERE LOCATION_ID = '$lokasilama' ";
	$resultPlant = oci_parse($con,$queryPlant);
	oci_execute($resultPlant);
	$rowPlant = oci_fetch_row($resultPlant);
	$kodePlant = $rowPlant[0];*/ 
	
	$jumno=strlen($lastNo);
	if($jumno==1){
		$nourut = "0000".$lastNo;
	} else if ($jumno==2){
		$nourut = "000".$lastNo;
	} else if ($jumno==3){
		$nourut = "00".$lastNo;
	} else if ($jumno==4){
		$nourut = "0".$lastNo;
	} else {
		$nourut = $lastNo;
	}
	
	$noppr = "REK/HRD/" . $bulanGenNo .  "" . $tahunGenNo . "/" . $nourut;
	
	//insert header mutasi
	$resultSeq = oci_parse($con,"SELECT MJ.MJ_PERMINTAAN_REKENING_SEQ.nextval FROM DUAL");
	oci_execute($resultSeq);
	$rowHSeq = oci_fetch_row($resultSeq);
	$gencountseq = $rowHSeq[0];
	
	$result = oci_parse($con,"INSERT INTO MJ.MJ_PERMINTAAN_REKENING (ID, NO_REQUEST, EMP_ID, ALASAN, AKTIF, CREATED_BY, CREATED_DATE, STATUS_REQUEST) VALUES ($gencountseq, '$noppr', $employ_id,'$alasan', '$aktif', $user_id, sysdate, '0') ");
	oci_execute($result); 

	$resultid = oci_parse($con,"SELECT id from MJ.MJ_PERMINTAAN_REKENING where no_request = '$noppr'");
	oci_execute($resultid);
	$rowHid = oci_fetch_row($resultid);
	$hdid2 = $rowHid[0];

	$data = "sukses";

} else {
	//update header MPD
	$result = oci_parse($con,"UPDATE MJ.MJ_PERMINTAAN_REKENING SET ALASAN = '$alasan', AKTIF = '$aktif', LAST_UPDATED_DATE = sysdate , LAST_UPDATED_BY = $user_id WHERE ID = $hdid");
	oci_execute($result); 

	$hdid2 = $hdid;
	$data = "sukses";
}

//echo $hdid2;exit;
$result = array('success' => true,
				'results' => $user_id,
				'rows' => $data,
				'hdid2' => $hdid2,
			);
echo json_encode($result);

?>