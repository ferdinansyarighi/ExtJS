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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunGenNo=substr($tglskr, 0, 4);
$bulanGenNo=substr($tglskr, 5, 2);
$data="gagal";
$hdid="";
$typeform="";
$tingkat=0;
$vPersonID=0;
$noReq="";
$kodeMutasi="";
$kodePlant="";

 if(isset($_POST['typeform']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$noReq=$_POST['noReq'];
  	$tipe=$_POST['tipe'];
	if($tipe == 'Promosi'){
		$kodeMutasi="SM1";
	} else if($tipe == 'Mutasi'){
		$kodeMutasi="SM2";
	} else {
		$kodeMutasi="SM3";
	}
  	$statuskar=$_POST['statuskar'];
  	$sifatperubahan=$_POST['sifatperubahan'];
  	$lama=$_POST['lama'];
  	$karyawan=$_POST['karyawan'];
  	$deptlama=$_POST['deptlama'];
  	$mgrlama=$_POST['mgrlama'];
  	$posisilama=$_POST['posisilama'];
  	$lokasilama=$_POST['lokasilama'];
  	$gradelama=$_POST['gradelama'];
  	$gajilama=$_POST['gajilama'];
  	$dept=$_POST['dept'];
  	$mgrbaru=$_POST['mgrbaru'];
  	$posisi=$_POST['posisi'];
  	$lokasi=$_POST['lokasi'];
  	$grade=$_POST['grade'];
  	$gaji=$_POST['gaji'];
  	$alasan=str_replace("'", "''", $_POST['alasan']);
  	$keterangan=str_replace("'", "''", $_POST['keterangan']);
  	$tanggal=$_POST['tanggal'];
  	$aktif=$_POST['aktif'];
	
	if($aktif == 'true'){
		$aktif = 'Y';
	} else {
		$aktif = 'N';
	}
  }

if ($typeform=='tambah')
{
	$queryCek = "SELECT COUNT(-1) FROM MJ.MJ_T_MUTASI WHERE REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 6) = '$tahunGenNo'";
	$resultCek = oci_parse($con,$queryCek);
	oci_execute($resultCek);
	$rowCek = oci_fetch_row($resultCek);
	$jumCek = $rowCek[0]; 
	
	if($jumCek > 0){
		$querycount = "SELECT MAX(REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 1)) + 1 NO_REQ 
		FROM MJ.MJ_T_MUTASI 
		WHERE REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 6) = '$tahunGenNo'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$lastNo = $rowcount[0]; 
	} else {
		$lastNo = "1"; 
	}
	
	$queryPosisi = "SELECT COUNT(-1)
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
	$kodePlant = $rowPlant[0]; 
	
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
	
	$noReq = $nourut . "/" . $kodeMutasi . "/HRD/" . $kodePlant  . "/" . $bulanGenNo. "/" . $tahunGenNo;
	
	//insert header mutasi
	$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_MUTASI_SEQ.nextval FROM DUAL");
	oci_execute($resultSeq);
	$rowHSeq = oci_fetch_row($resultSeq);
	$hdid = $rowHSeq[0];
	
	$query = "INSERT INTO MJ.MJ_T_MUTASI (ID, NO_REQUEST, TIPE, KARYAWAN_ID, DEPT_LAMA_ID, POSISI_LAMA_ID, LOKASI_LAMA_ID, GRADE_LAMA_ID, GAJI_LAMA, DEPT_BARU_ID, POSISI_BARU_ID, LOKASI_BARU_ID, GRADE_BARU_ID, GAJI_BARU, ALASAN, KETERANGAN, TGL_EFFECTIVE, TINGKAT, STATUS_DOK, STATUS, CREATED_BY, CREATED_DATE, MGR_LAMA, MGR_BARU, STATUS_KARYAWAN, SIFAT_PERUBAHAN, JUMLAH_BULAN) 
	VALUES ($hdid, '$noReq', '$tipe', '$karyawan', '$deptlama', '$posisilama', '$lokasilama', '$gradelama', '$gajilama', '$dept', '$posisi', '$lokasi', '$grade', '$gaji', '$alasan', '$keterangan', TO_DATE('$tanggal', 'YYYY-MM-DD'), '$tingkat', 'In process', '$aktif', '$emp_id', SYSDATE, $mgrlama, $mgrbaru, '$statuskar', '$sifatperubahan', $lama)";
	$result = oci_parse($con,$query);
	oci_execute($result);
	//end insert header MPD
	
	$data="sukses";
} else {
	$queryPosisi = "SELECT COUNT(-1)
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
		$filtertingkat = " AND TINGKAT IN (0,1)";
	} else {
		$filtertingkat = " AND TINGKAT = 0";
	}
	
	$resultCek = oci_parse($con,"SELECT COUNT(-1) FROM MJ.MJ_T_MUTASI WHERE ID=$hdid $filtertingkat ");
	oci_execute($resultCek);
	$rowCek = oci_fetch_row($resultCek);
	$cek_approve = $rowCek[0];
	
	if($cek_approve > 0){
		//update header MPD
		$queryUpdate = "UPDATE MJ.MJ_T_MUTASI SET DEPT_BARU_ID='$dept', POSISI_BARU_ID='$posisi', LOKASI_BARU_ID='$lokasi', GRADE_BARU_ID='$grade', GAJI_BARU = '$gaji', ALASAN='$alasan', KETERANGAN='$keterangan', TGL_EFFECTIVE = TO_DATE('$tanggal', 'YYYY-MM-DD'), STATUS='$aktif', STATUS_DOK='In process', TINGKAT='$tingkat', LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE, MGR_LAMA = $mgrlama, MGR_BARU = $mgrbaru , STATUS_KARYAWAN='$statuskar', SIFAT_PERUBAHAN='$sifatperubahan', JUMLAH_BULAN=$lama WHERE ID=$hdid";
		//echo $queryUpdate;exit;
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
		//end update header MPD
		
		//Delete Approval MPD
		$queryApproval = "DELETE FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=$hdid AND TRANSAKSI_KODE='MPD'";
		//echo $query;
		$resultApproval = oci_parse($con,$queryApproval);
		oci_execute($resultApproval);
			
		$data="sukses";
	}else{
		$data="gagal";
	}
}

$querydata = "SELECT ID || '|' || NO_REQUEST FROM MJ.MJ_T_MUTASI WHERE ID=$hdid ";
$resultdata = oci_parse($con,$querydata);
oci_execute($resultdata);
$rowdata = oci_fetch_row($resultdata);
$dataNoSIK = $rowdata[0]; 


$result = array('success' => true,
				'results' => $dataNoSIK,
				'rows' => $data
			);
echo json_encode($result);







?>