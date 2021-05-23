<?PHP
//require('smtpemailattachment.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
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
 if(isset($_POST['nama_user']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama_user=$_POST['nama_user'];
  	$tgl=$_POST['tgl'];
  	$jml=$_POST['jml'];
  	$nominal=$_POST['nominal']/$jml;
  	$ket=$_POST['ket'];
  	$status=$_POST['status'];
	
	if($typeform == "tambah"){
		$query2 = "SELECT PAF.JOB_ID, PAF.POSITION_ID
			,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1) PERUSAHAAN
			,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPT
			,CASE WHEN REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)='0' THEN 'MGR'
			ELSE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)
			END AS DIV
		FROM APPS.PER_ASSIGNMENTS_F PAF
			,APPS.PER_JOBS PJ
			,APPS.PER_POSITIONS PP
		WHERE PAF.PERSON_ID=$nama_user
			AND PAF.JOB_ID=PJ.JOB_ID
			AND PAF.POSITION_ID=PP.POSITION_ID
			AND PAF.EFFECTIVE_END_DATE > SYSDATE";
		$result2 = oci_parse($con,$query2);
		oci_execute($result2);
		$rowOU = oci_fetch_row($result2);
		$namePer = $rowOU[2];
		$nameDept = $rowOU[3];
		$nameDiv = $rowOU[4];
		
		$querycount = "SELECT COUNT(-1) 
		FROM MJ.MJ_M_GENERATENO 
		WHERE TEMP1='$namePer' 
		AND TEMP2='$nameDept' 
		AND TEMP3='$nameDiv' 
		AND APPCODE='" . APPCODE . "' 
		AND TRANSAKSI_KODE='BS'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumgen = $rowcount[0]; 
		
		if ($jumgen>0){
			$query = "SELECT LASTNO 
			FROM MJ.MJ_M_GENERATENO 
			WHERE TEMP1='$namePer' 
			AND TEMP2='$nameDept' 
			AND TEMP3='$nameDiv' 
			AND APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BS'";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$rowGLastno = oci_fetch_row($result);
			$lastNo = $rowGLastno[0];
		} else {
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$gencountseq = $rowHSeq[0];
		
			$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE) 
			VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '$namePer', '$nameDept', '$nameDiv', 'BS')";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$lastNo = 0;
		} 
		
		$querycount = "SELECT TAHUN 
		FROM MJ.MJ_M_GENERATENO 
		WHERE TEMP1='$namePer' 
		AND TEMP2='$nameDept' 
		AND TEMP3='$nameDiv' 
		AND APPCODE='" . APPCODE . "' 
		AND TRANSAKSI_KODE='BS'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$thnGen = $rowcount[0]; 
		
		if($thnGen!=$tahunGenNo){
			$lastNo = 0;
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO 
			SET LASTNO='$lastNo', TAHUN='$tahunGenNo' 
			WHERE TEMP1='$namePer' 
			AND TEMP2='$nameDept' 
			AND TEMP3='$nameDiv' 
			AND APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BS'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		} else {
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO 
			SET LASTNO='$lastNo' 
			WHERE TEMP1='$namePer' 
			AND TEMP2='$nameDept' 
			AND TEMP3='$nameDiv' 
			AND APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BS'";
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
		
		$noPinjaman = "BS/" . $namePer . "/" . $nameDept . "/" . $nameDiv . "/" . $tahunGenNo . "/" . $nourut;
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_PINJAMAN_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$hdid = $row[0];
		
		$sqlQuery = "INSERT INTO MJ.MJ_T_PINJAMAN (ID, APP_ID, JENIS_PINJAMAN, NOMOR_PINJAMAN, PERSON_ID, TANGGAL_PINJAMAN, NOMINAL, JUMLAH_CICILAN, TUJUAN_PINJAMAN, TINGKAT, STATUS, STATUS_DOKUMEN, CREATED_BY, CREATED_DATE) 
		VALUES ( $hdid, " . APPCODE . ", 'BS', '$noPinjaman', $nama_user, TO_DATE('$tgl', 'YYYY-MM-DD'), $nominal, $jml, '$ket', 0, '$status', 'In process', $emp_id, SYSDATE)";
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		//insert upload BS
		$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
		FROM MJ.MJ_TEMP_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
		AND TRANSAKSI_KODE='BS'";
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
		AND TRANSAKSI_KODE='BS'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		//end insert upload BS
	
		$data="sukses";
	} else {
		$sqlQuery = "UPDATE MJ.MJ_T_PINJAMAN SET JUMLAH_CICILAN='$jml', NOMINAL='$nominal', TUJUAN_PINJAMAN='$ket', STATUS='$status', LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE WHERE APP_ID=" . APPCODE . " AND ID=$hdid ";
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		//insert upload BS
		$queryUpload = "DELETE FROM MJ.MJ_M_UPLOAD WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='BS' AND TRANSAKSI_ID=$hdid";
		//echo $query;
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);
			
		$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
		FROM MJ.MJ_TEMP_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
		AND TRANSAKSI_KODE='BS'";
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
		AND TRANSAKSI_KODE='BS'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		//end insert upload BS
		
		$data="sukses";
	}
	
	$resultNo = oci_parse($con,"SELECT NOMOR_PINJAMAN FROM MJ.MJ_T_PINJAMAN WHERE ID = $hdid");
	oci_execute($resultNo);
	$rowNo = oci_fetch_row($resultNo);
	$noPinjaman = $rowNo[0];
	
	
	$result = array('success' => true,
					'results' => $hdid .'|'. $noPinjaman,
					'rows' => $data
				);
	echo json_encode($result);
  }


?>