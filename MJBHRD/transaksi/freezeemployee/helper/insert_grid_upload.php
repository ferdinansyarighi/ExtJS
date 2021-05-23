<?PHP
	session_start();
	require_once ('../../../main/koneksi.php');
	require_once ('../../../main/define_session.php');
	
	$data = "";
	$idtrans="";
	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';

	if(isset($_POST['idtrans']))
	{
		$idtrans=$_POST['idtrans']; 
		$query = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE
					FROM MJ.MJ_M_UPLOAD
					WHERE APP_ID=" . APPCODE . " 
					AND TRANSAKSI_ID='".$idtrans."'
					AND TRANSAKSI_KODE = '".$dt_in['TRANSAKSI_KODE']."'";
		//echo $query;exit;
		$result = oci_parse($con, $query);
		oci_execute($result);
		
		while($row = oci_fetch_row($result))
		{
			$vTransID=$row[0];
			$vFilename=$row[1];
			$vFilesize=$row[2];
			$vFiletype=$row[3];
			$vFileuser=$row[4];
			$vFiledate=$row[5];
			$vFilekode=$row[6];
			
			$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_TEMP_UPLOAD_SEQ.nextval FROM DUAL");
			oci_execute($resultDetSeq);
			$rowDSeq = oci_fetch_row($resultDetSeq);
			$seqD = $rowDSeq[0];
			
			$q_cek_upload = "SELECT COUNT(-1) FROM MJ.MJ_TEMP_UPLOAD
							WHERE APP_ID = '".APPCODE."' AND TRANSAKSI_ID = '".$vTransID."' AND FILENAME='".$vFilename."'
								AND FILESIZE = '".$vFilesize."' AND FILETYPE='".$vFiletype."' AND USERNAME = '".$user_id."'
								AND CREATEDDATE='".$vFiledate."' AND TRANSAKSI_KODE='".$vFilekode."'";
			
			$r_cek_upload = oci_parse($con,$q_cek_upload);
			oci_execute($r_cek_upload);
			$r_cek_upload = oci_fetch_row($r_cek_upload);
			
			if($r_cek_upload[0] == 0)
			{
				$queryUpload = "INSERT INTO MJ.MJ_TEMP_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, 
									USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
								VALUES ('".$seqD."', '".APPCODE."', '".$vTransID."', '".$vFilename."', '".$vFilesize."',
									'".$vFiletype."', '".$user_id."', '".$vFiledate."', '".$vFilekode."')";
			//	echo $queryUpload;exit;
				$resultUpload = oci_parse($con,$queryUpload);
				oci_execute($resultUpload);
			}
		}
	}
?>