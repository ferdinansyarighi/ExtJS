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
 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
  	$keterangan=str_replace("'","`",$_POST['keterangan']);
	
  	$jml=$_POST['jml'];
  	$nominal=$_POST['nominal']/$jml;
	$bulan_pot = $_POST['bulan_pot'];
	$tahun_pot = $_POST['tahun_pot'];	
	
	$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
	oci_execute($resultSeqAPP);
	$rowApp = oci_fetch_row($resultSeqAPP);
	$IdTransAPP = $rowApp[0];
	
	$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
	VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 3, '$typeform', '$keterangan', $emp_id, SYSDATE)";
	
	// VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 2, '$typeform', '$keterangan', $emp_id, SYSDATE)";
	
	$resultApp = oci_parse($con,$sqlQueryApp);
	oci_execute($resultApp);
	
	if( $typeform == "Approved" ) {
		
		$sqlQueryA = "
		UPDATE MJ.MJ_T_PINJAMAN
		SET KETERANGAN_DIR = '$keterangan',
			TINGKAT = 3,
			NOMINAL = $nominal,
			JUMLAH_CICILAN = $jml,
			JUMLAH_PINJAMAN = ( $nominal * $jml ),
			START_POTONGAN_BULAN = $bulan_pot,
			START_POTONGAN_TAHUN = $tahun_pot,
			JUMLAH_CICILAN_AWAL = $jml
		WHERE ID = $hdid";
		
		$resultA = oci_parse($con,$sqlQueryA);
		oci_execute($resultA);
		
	} else {
		
		$resultbyhrd = oci_parse($con,"SELECT BYHRD FROM MJ.MJ_T_PINJAMAN WHERE ID = $hdid");
		oci_execute($resultbyhrd);
		$rowbyhrd = oci_fetch_row($resultbyhrd);
		$byhrd = $rowbyhrd[0];
		if ( $byhrd == 'N' ) {
			$sqlQueryA = "
			UPDATE MJ.MJ_T_PINJAMAN 
			SET KETERANGAN_DIR = '$keterangan', 
				TINGKAT = 0, 
				STATUS_DOKUMEN = '$typeform' 
			WHERE ID = $hdid";
			$resultA = oci_parse($con,$sqlQueryA);
			oci_execute($resultA);
		} else {
			$sqlQueryA = "
			UPDATE MJ.MJ_T_PINJAMAN 
			SET KETERANGAN_DIR = '$keterangan', 
				TINGKAT = 2, 
				STATUS_DOKUMEN = '$typeform' 
			WHERE ID = $hdid";
			$resultA = oci_parse($con,$sqlQueryA);
			oci_execute($resultA);
		}
	}
	
	
	//delete  file attachment PINJAMAN sebelum dilakukan insert sesuai data di temp upload
	
	$queryUpload = "
	DELETE FROM MJ.MJ_M_UPLOAD 
	WHERE APP_ID=" . APPCODE . " 
	AND USERNAME='$user_id' 
	AND TRANSAKSI_KODE='PINJAMANDIR' 
	AND TRANSAKSI_ID=$hdid";
	//echo $query;
	$resultUpload = oci_parse($con,$queryUpload);
	oci_execute($resultUpload);
	
	
	//insert upload PINJAMAN
	$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
	FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='PINJAMANDIR'";
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
	AND TRANSAKSI_KODE='PINJAMANHRD'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	//end insert upload PINJAMAN
	
	$data="sukses";
	
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