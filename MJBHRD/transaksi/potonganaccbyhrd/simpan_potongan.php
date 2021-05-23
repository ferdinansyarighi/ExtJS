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

$bulanGenNo=substr($tglskr, 5, 2);

$data="gagal";

 if(isset($_POST['nama_user']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama_user=$_POST['nama_user'];
	$manager=$_POST['manager'];
	$tipe=$_POST['tipe'];
	$nobs=$_POST['nobs'];
  	$tgl=$_POST['tgl'];
  	$jml=$_POST['jml'];
  	$nominal=$_POST['nominal']/$jml;
  	$tujuan=str_replace("'","`",$_POST['tujuan']);
  	$status=$_POST['status'];
	
	$bulan_pot = $_POST['bulan_pot'];
	$tahun_pot = $_POST['tahun_pot'];
	
	$tipe_pencairan = $_POST['tipe_pencairan'];
	$no_rek = str_replace( "'", "`", $_POST['no_rek'] );
	
	
	// mode penambahan data
	
	if($typeform == "tambah") {
		
		$query2 = "
			SELECT PAF.JOB_ID, PAF.POSITION_ID
				,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1) PERUSAHAAN
				,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPT
				,CASE WHEN REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)='0' THEN 'MGR'
				ELSE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)
				END AS DIV
			FROM APPS.PER_ASSIGNMENTS_F PAF
				,APPS.PER_JOBS PJ
				,APPS.PER_POSITIONS PP
			WHERE PAF.PERSON_ID = $nama_user
				AND PAF.JOB_ID=PJ.JOB_ID
				AND PAF.POSITION_ID=PP.POSITION_ID
				AND PAF.EFFECTIVE_END_DATE > SYSDATE
			";
		$result2 = oci_parse($con,$query2);
		oci_execute($result2);
		$rowOU = oci_fetch_row($result2);
		$namePer = $rowOU[2];
		$nameDept = $rowOU[3];
		$nameDiv = $rowOU[4];
		
		
		// Penomoran diubah menjadi per tahun dan bukan per departemen. 4 Feb 2019 oleh AJK requested by ISA
		
		/*
		$querycount = "
		SELECT COUNT(-1) 
		FROM MJ.MJ_M_GENERATENO 
		WHERE TEMP1='$namePer' 
		AND TEMP2='$nameDept' 
		AND TEMP3='$nameDiv' 
		AND APPCODE='" . APPCODE . "' 
		AND TRANSAKSI_KODE='PINJAMAN'
		";
		*/
		
		$querycount = "
			SELECT COUNT(-1) 
			FROM MJ.MJ_M_GENERATENO 
			WHERE TEMP1 IS NULL
			AND APPCODE = '" . APPCODE . "' 
			AND TRANSAKSI_KODE = 'PINJAMAN'
		";
		
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumgen = $rowcount[0]; 
		
		if ( $jumgen > 0 ) {
			
			/*
			$query = "
			SELECT LASTNO 
			FROM MJ.MJ_M_GENERATENO 
			WHERE TEMP1='$namePer' 
			AND TEMP2='$nameDept' 
			AND TEMP3='$nameDiv' 
			AND APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='PINJAMAN'
			";
			*/
			
			$query = "
				SELECT LASTNO 
				FROM MJ.MJ_M_GENERATENO 
				WHERE TEMP1 IS NULL
				AND APPCODE = '" . APPCODE . "' 
				AND TRANSAKSI_KODE = 'PINJAMAN'
			";
			
			$result = oci_parse($con,$query);
			oci_execute($result);
			$rowGLastno = oci_fetch_row($result);
			$lastNo = $rowGLastno[0];
			
			
		} else {
			
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$gencountseq = $rowHSeq[0];
			
			/*
			$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE) 
			VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '$namePer', '$nameDept', '$nameDiv', 'PINJAMAN')";
			$result = oci_parse($con,$query);
			oci_execute($result);
			*/
			
			$query = "
				INSERT INTO MJ.MJ_M_GENERATENO 
					( ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE ) 
				VALUES (
					$gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '', '', '', 'PINJAMAN' )";
			$result = oci_parse($con,$query);
			oci_execute($result);
			
			$lastNo = 0;
			
		} 
		
		/*
		$querycount = "
		SELECT TAHUN 
		FROM MJ.MJ_M_GENERATENO 
		WHERE TEMP1='$namePer' 
		AND TEMP2='$nameDept' 
		AND TEMP3='$nameDiv' 
		AND APPCODE='" . APPCODE . "' 
		AND TRANSAKSI_KODE='PINJAMAN'
		";
		*/
		
		$querycount = "
			SELECT TAHUN 
			FROM MJ.MJ_M_GENERATENO 
			WHERE TEMP1 IS NULL
			AND APPCODE = '" . APPCODE . "' 
			AND TRANSAKSI_KODE = 'PINJAMAN'
		";
		
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$thnGen = $rowcount[0]; 
		
		if( $thnGen != $tahunGenNo ) {
			
			$lastNo = 0;
			$lastNo=$lastNo+1;
			
			/*
			$queryLast = "
				UPDATE MJ.MJ_M_GENERATENO 
				SET LASTNO='$lastNo', TAHUN='$tahunGenNo' 
				WHERE TEMP1='$namePer' 
				AND TEMP2='$nameDept' 
				AND TEMP3='$nameDiv' 
				AND APPCODE='" . APPCODE . "' 
				AND TRANSAKSI_KODE='PINJAMAN'
			";
			*/
			
			$queryLast = "
				UPDATE MJ.MJ_M_GENERATENO 
				SET LASTNO = '$lastNo', TAHUN = '$tahunGenNo' 
				WHERE TEMP1 IS NULL
				AND APPCODE = '" . APPCODE . "' 
				AND TRANSAKSI_KODE = 'PINJAMAN'
			";
			
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
			
		} else {
			
			$lastNo = $lastNo + 1;
			
			/*
			$queryLast = "
				UPDATE MJ.MJ_M_GENERATENO 
				SET LASTNO='$lastNo' 
				WHERE TEMP1='$namePer' 
				AND TEMP2='$nameDept' 
				AND TEMP3='$nameDiv' 
				AND APPCODE='" . APPCODE . "' 
				AND TRANSAKSI_KODE='PINJAMAN'
			";
			*/
			
			$queryLast = "
				UPDATE MJ.MJ_M_GENERATENO 
				SET LASTNO = '$lastNo' 
				WHERE TEMP1 IS NULL
				AND APPCODE = '" . APPCODE . "' 
				AND TRANSAKSI_KODE = 'PINJAMAN'
			";
			
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
		
		
		// $noPinjaman = "PINJ/" . $namePer . "/" . $nameDiv . "/" . $tahunGenNo . "/" . $nourut;
		
		$noPinjaman = "PINJ/" . $namePer . "/" . $nameDiv . "/" . $tahunGenNo . "/" . $bulanGenNo . "/" . $nourut;
		
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_PINJAMAN_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$hdid = $row[0];
		
		$sqlQuery = "
		INSERT INTO MJ.MJ_T_PINJAMAN (
			ID, 
			APP_ID, 
			JENIS_PINJAMAN, 
			NOMOR_PINJAMAN, 
			PERSON_ID, 
			MANAGER, 
			TANGGAL_PINJAMAN, 
			NOMINAL, 
			JUMLAH_CICILAN, 
			TUJUAN_PINJAMAN, 
			TINGKAT, 
			STATUS, 
			STATUS_DOKUMEN, 
			CREATED_BY, 
			CREATED_DATE, 
			TIPE, 
			BYHRD, 
			JUMLAH_PINJAMAN, 
			START_POTONGAN_BULAN, 
			START_POTONGAN_TAHUN,
			JUMLAH_CICILAN_AWAL,
			TIPE_PENCAIRAN,
			NOMOR_REKENING,
			ID_BS
		) 
		VALUES ( 
			$hdid, 
			" . APPCODE . ", 
			'PINJAMAN', 
			'$noPinjaman', 
			$nama_user, 
			$manager, 
			TO_DATE('$tgl', 'YYYY-MM-DD'), 
			$nominal, 
			$jml, 
			'$tujuan', 
			2, 
			'$status', 
			'In process', 
			$emp_id, 
			SYSDATE, 
			'$tipe', 
			'Y', 
			($nominal*$jml), 
			$bulan_pot, 
			$tahun_pot,
			$jml,
			'$tipe_pencairan',
			'$no_rek',
			'$nobs'
		)";
		
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		//insert upload PINJAMAN
		$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
		FROM MJ.MJ_TEMP_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
		AND TRANSAKSI_KODE='PINJAMANHRD'";
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
			
			$queryUpload = "
			INSERT INTO MJ.MJ_M_UPLOAD (
				ID, 
				APP_ID, 
				TRANSAKSI_ID, 
				FILENAME, 
				FILESIZE, 
				FILETYPE, 
				USERNAME, 
				CREATEDDATE, 
				TRANSAKSI_KODE
			) 
			VALUES (
				$seqD, 
				" . APPCODE . ", 
				$hdid, 
				'$vFilename', 
				'$vFilesize', 
				'$vFiletype', 
				'$user_id', 
				SYSDATE, 
				'$vFilekode'
			)";
			
			//echo $query;
			$resultUpload = oci_parse($con,$queryUpload);
			oci_execute($resultUpload);
		}
		
		$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
		AND TRANSAKSI_KODE='PINJAMANHRD'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		//end insert upload PINJAMAN
		
		
		// Insert ke tabel approval

		$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
		oci_execute($resultSeqAPP);
		$rowApp = oci_fetch_row($resultSeqAPP);
		$IdTransAPP = $rowApp[0];
		
		$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
		VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 2, 'Approved', '$tujuan', $emp_id, SYSDATE)";
		
		$resultApp = oci_parse($con,$sqlQueryApp);
		oci_execute($resultApp);
		
		
		$data="sukses";
		
		
	// mode edit data melalui tombol 'Existing'
	
	} else {
		
		$resultCek = oci_parse($con,"
			SELECT * 
			from MJ.MJ_T_PINJAMAN 
			where id = $hdid 
			and STATUS_DOKUMEN <> 'Approved'
			AND TINGKAT = 2 
			and BYHRD = 'Y'
			"); 
		oci_execute($resultCek);
		$rowCek = oci_fetch_row($resultCek);
		$cek = $rowCek[0];
		
		if ( $cek == '' ) {
			
			$data="gagal2";
			
		} else {
		
			$sqlQuery = "
			UPDATE MJ.MJ_T_PINJAMAN 
			SET MANAGER = $manager, 
				TIPE = '$tipe', 
				TANGGAL_PINJAMAN = TO_DATE('$tgl', 'YYYY-MM-DD'), 
				JUMLAH_CICILAN='$jml', 
				NOMINAL='$nominal', 
				JUMLAH_PINJAMAN = ($nominal*$jml), 
				TUJUAN_PINJAMAN='$tujuan', 
				STATUS_DOKUMEN = 'In process', 
				STATUS='$status', KETERANGAN_DIR='', 
				KETERANGAN_ACC='', 
				LAST_UPDATED_BY=$emp_id, 
				LAST_UPDATED_DATE=SYSDATE, 
				START_POTONGAN_BULAN = $bulan_pot, 
				START_POTONGAN_TAHUN = $tahun_pot,
				JUMLAH_CICILAN_AWAL = '$jml',
				TIPE_PENCAIRAN = '$tipe_pencairan',
				NOMOR_REKENING = '$no_rek',
				ID_BS = '$nobs'
			WHERE APP_ID=" . APPCODE . " 
			AND ID=$hdid 
			";
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
			
			//insert upload PINJAMAN
			$queryUpload = "DELETE FROM MJ.MJ_M_UPLOAD WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='PINJAMANHRD' AND TRANSAKSI_ID=$hdid";
			//echo $query;
			$resultUpload = oci_parse($con,$queryUpload);
			oci_execute($resultUpload);
				
			$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
			FROM MJ.MJ_TEMP_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='PINJAMANHRD'";
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
			AND TRANSAKSI_KODE='PINJAMAN'";
			$result = oci_parse($con, $query);
			oci_execute($result);
			//end insert upload PINJAMAN
			
			
			// Insert ke tabel approval

			$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
			oci_execute($resultSeqAPP);
			$rowApp = oci_fetch_row($resultSeqAPP);
			$IdTransAPP = $rowApp[0];
			
			$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
			VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 2, 'Approved', '$tujuan', $emp_id, SYSDATE)";
			
			$resultApp = oci_parse($con,$sqlQueryApp);
			oci_execute($resultApp);

	
			$data="sukses";
		}
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