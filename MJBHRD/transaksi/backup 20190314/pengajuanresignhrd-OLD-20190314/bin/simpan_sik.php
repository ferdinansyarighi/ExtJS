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
$data="gagal";
$hdid="";
$typeform="";
$noSik="";
$pembuat="";
$pemohon="";
$dept="";
$plant="";
$manager="";
$spv="";
$tglFrom="";
$tglTo="";
$jamFrom="";
$jamTo="";
$keterangan="";
$alamat="";
$noTelp="";
$noHP="";
$email="";
$email_man="";
$email_spv="";
$kategoriForm="";
$ijinKhusus="";
$nama_man="";
$nama_spv="";
$tingkat=0;
$vPersonID=0;

 if(isset($_POST['typeform']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$noSik="0";
	$pembuat=str_replace("'", "''", $_POST['pembuat']);
  	$pemohon=str_replace("'", "''", $_POST['pemohon']);
	
	$resultPersonID = oci_parse($con,"SELECT PERSON_ID 
	FROM APPS.PER_PEOPLE_F 
	WHERE FULL_NAME LIKE '%$pemohon%' 
	AND EFFECTIVE_END_DATE > SYSDATE
	AND CURRENT_EMPLOYEE_FLAG='Y'");
	oci_execute($resultPersonID);
	$rowHPersonID = oci_fetch_row($resultPersonID);
	$vPersonID = $rowHPersonID[0];
	
	$resultPembuatID = oci_parse($con,"SELECT PERSON_ID 
	FROM APPS.PER_PEOPLE_F 
	WHERE FULL_NAME LIKE '%$pembuat%' 
	AND EFFECTIVE_END_DATE > SYSDATE");
	oci_execute($resultPembuatID);
	$rowHPembuatID = oci_fetch_row($resultPembuatID);
	$vPembuatID = $rowHPembuatID[0];
	
	$dept=$_POST['dept'];
	$plant=$_POST['plant'];
	$nama_man=$_POST['manager'];
	$nama_man = explode(" - ",$nama_man);
	$manager=$nama_man[0];
	$nama_spv=$_POST['spv'];
	$nama_spv = explode(" - ",$nama_spv);
	$spv=$nama_spv[0];
	$email_man=$_POST['email_man'];
	$email_spv=$_POST['email_spv'];
  	$tglFrom=$_POST['tglFrom'];
	$hari=substr($tglFrom, 0, 2);
	$bulan=substr($tglFrom, 3, 2);
	$tahun=substr($tglFrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglFrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglTo=$_POST['tglTo'];
	$hari=substr($tglTo, 0, 2);
	$bulan=substr($tglTo, 3, 2);
	$tahun=substr($tglTo, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglTo = $tahun . "-" . $bulan . "-" . $hari;
	$jamFrom=$_POST['jamFrom'];
	$jamTo=$_POST['jamTo'];
  	$keterangan=$_POST['keterangan'];
	$alamat=$_POST['alamat'];
	$noTelp=$_POST['noTelp'];
	$noHP=$_POST['noHP'];
	$email=$_POST['email'];
	$status=$_POST['status'];
	$kategoriForm=$_POST['kategoriForm'];
	$ijinKhusus=$_POST['ijinKhusus'];
  }

if($spv=='' || $spv == '- Pilih -'){
	$tingkat=1;
} else {
	$tingkat=0;
}
if ($typeform=='tambah')
{
	$query2 = "SELECT PAF.JOB_ID, PAF.POSITION_ID
        ,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1) PERUSAHAAN
        ,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPT
        ,CASE WHEN REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)='0' THEN 'MGR'
        ELSE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)
        END AS DIV
	FROM APPS.PER_ASSIGNMENTS_F PAF
		,APPS.PER_JOBS PJ
		,APPS.PER_POSITIONS PP
	WHERE PAF.PERSON_ID=$emp_id
		AND PAF.JOB_ID=PJ.JOB_ID
		AND PAF.POSITION_ID=PP.POSITION_ID
		AND PAF.EFFECTIVE_END_DATE > SYSDATE";
	$result2 = oci_parse($con,$query2);
	oci_execute($result2);
	$rowOU = oci_fetch_row($result2);
	$namePer = $rowOU[2];
	$nameDept = $rowOU[3];
	$nameDiv = $rowOU[4];
	
	$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SIK'";
	$resultcount = oci_parse($con,$querycount);
	oci_execute($resultcount);
	$rowcount = oci_fetch_row($resultcount);
	$jumgen = $rowcount[0]; 
	
	if ($jumgen>0){
		$query = "SELECT LASTNO FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SIK'";
		$result = oci_parse($con,$query);
		oci_execute($result);
		$rowGLastno = oci_fetch_row($result);
		$lastNo = $rowGLastno[0];
	} else {
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$gencountseq = $rowHSeq[0];
	
		$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE) VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '$namePer', '$nameDept', '$nameDiv', 'SIK')";
		$result = oci_parse($con,$query);
		oci_execute($result);
		$lastNo = 0;
	} 
	
	$querycount = "SELECT TAHUN FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SIK'";
	$resultcount = oci_parse($con,$querycount);
	oci_execute($resultcount);
	$rowcount = oci_fetch_row($resultcount);
	$thnGen = $rowcount[0]; 
	
	if($thnGen!=$tahunGenNo){
		$lastNo = 0;
		$lastNo=$lastNo+1;
		$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo', TAHUN='$tahunGenNo' WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SIK'";
		$resultLast = oci_parse($con,$queryLast);
		oci_execute($resultLast);
	} else {
		$lastNo=$lastNo+1;
		$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo' WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SIK'";
		$resultLast = oci_parse($con,$queryLast);
		oci_execute($resultLast);
	}
	
	$jumno=strlen($lastNo);
	if($jumno==1){
		$nourut = "00000".$lastNo;
	} else if ($jumno==2){
		$nourut = "0000".$lastNo;
	} else if ($jumno==3){
		$nourut = "000".$lastNo;
	} else if ($jumno==4){
		$nourut = "00".$lastNo;
	} else if ($jumno==5){
		$nourut = "0".$lastNo;
	} else {
		$nourut = $lastNo;
	}
	
	$noSik = "SIK/" . $namePer . "/" . $nameDept . "/" . $nameDiv . "/" . $tahunGenNo . "/" . $nourut;
	
	//echo $pp_no;
	//insert header sik
	$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_SIK_SEQ.nextval FROM DUAL");
	oci_execute($resultSeq);
	$rowHSeq = oci_fetch_row($resultSeq);
	$hdid = $rowHSeq[0];
	
	$query = "INSERT INTO MJ.MJ_T_SIK (ID, NOMOR_SIK, PEMBUAT, PEMOHON, DEPARTEMEN, PLANT, SPV, EMAIL_SPV, MANAGER, EMAIL_MANAGER, TANGGAL_FROM, TANGGAL_TO, JAM_FROM, JAM_TO, KETERANGAN, ALAMAT, NO_TELP, NO_HP, EMAIL, STATUS, STATUS_DOK, TINGKAT, KATEGORI, IJIN_KHUSUS, CREATED_BY, CREATED_DATE, PERSON_ID, PEMBUAT_ID) VALUES ($hdid, '$noSik', '$pembuat', '$pemohon', '$dept', '$plant', '$spv', '$email_spv', '$manager', '$email_man', TO_DATE('$tglFrom', 'YYYY-MM-DD'), TO_DATE('$tglTo', 'YYYY-MM-DD'), '$jamFrom', '$jamTo', '$keterangan', '$alamat', '$noTelp', '$noHP', '$email', '$status', 'In process', $tingkat, '$kategoriForm', '$ijinKhusus', '$emp_name', SYSDATE, $vPersonID, $vPembuatID)";
	$result = oci_parse($con,$query);
	oci_execute($result);
	//end insert header SIK
	//insert upload SIK
	$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
	FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='SIK'";
	//echo $query;
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$vFilename=$row[0];
		$vFilesize=$row[1];
		$vFiletype=$row[2];
		$vFilekode=$row[3];
		
		$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		
		$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
		VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
		//echo $query;
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);
	}
	
	$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='SIK'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	//end insert upload SIK
	
	$data="sukses";
} else {
	//update header SIK
	$queryUpdate = "UPDATE MJ.MJ_T_SIK SET IJIN_KHUSUS='$ijinKhusus', PEMOHON='$pemohon', PERSON_ID=$vPersonID, DEPARTEMEN='$dept', PLANT='$plant', SPV='$spv', EMAIL_SPV='$email_spv', MANAGER='$manager', EMAIL_MANAGER='$email_man', TANGGAL_FROM=TO_DATE('$tglFrom', 'YYYY-MM-DD'), TANGGAL_TO=TO_DATE('$tglTo', 'YYYY-MM-DD'), JAM_FROM='$jamFrom', JAM_TO='$jamTo', KETERANGAN='$keterangan', ALAMAT='$alamat', NO_TELP='$noTelp', NO_HP='$noHP', EMAIL='$email', STATUS='$status', STATUS_DOK='In process', TINGKAT=$tingkat, LAST_UPDATED_BY='$emp_name', LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
	$result = oci_parse($con, $queryUpdate );
	oci_execute($result);
	//end update header SIK
	//Delete Approval SIK
	$queryApproval = "DELETE FROM MJ.MJ_T_APPROVAL WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$hdid AND TRANSAKSI_KODE='SIK'";
	//echo $query;
	$resultApproval = oci_parse($con,$queryApproval);
	oci_execute($resultApproval);
		
	//insert upload SIK
	$queryUpload = "DELETE FROM MJ.MJ_M_UPLOAD WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='SIK' AND TRANSAKSI_ID=$hdid";
	//echo $query;
	$resultUpload = oci_parse($con,$queryUpload);
	oci_execute($resultUpload);
		
	$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
	FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='SIK'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$vFilename=$row[0];
		$vFilesize=$row[1];
		$vFiletype=$row[2];
		$vFilekode=$row[3];
		
		$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		
		$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
		VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
		//echo $query;
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);
	}
	
	$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='SIK'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	//end insert upload SIK
	$data="sukses";
}

$querydata = "SELECT ID || '|' || NOMOR_SIK FROM MJ.MJ_T_SIK WHERE ID=$hdid ";
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