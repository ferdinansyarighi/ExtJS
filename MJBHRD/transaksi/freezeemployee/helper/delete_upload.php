<?PHP
	session_start();
	require_once ('../../../main/koneksi.php');
	require_once ('../../../main/define_session.php');

	$data = "";
	$idupload="";
	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
	
	if(isset($_POST['idupload']))
	{
		$idupload=$_POST['idupload']; 
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
						FROM MJ.MJ_TEMP_UPLOAD
						WHERE APP_ID='".APPCODE."' AND id='".$idupload."'
							AND TRANSAKSI_KODE = '".$dt_in['TRANSAKSI_KODE']."' ";
		//echo $queryAtt;exit;
		$resultAtt = oci_parse($con, $queryAtt);
		oci_execute($resultAtt);
		while($rowAtt = oci_fetch_row($resultAtt))
		{
			$vTransID=$rowAtt[0];
			$vFilename=$rowAtt[1];
			$vFilesize=$rowAtt[2];
			$vFiletype=$rowAtt[3];
			$vFileuser=$rowAtt[4];
			$vFiledate=$rowAtt[5];
			$ekstensi	= end(explode(".", $vFilename));
			$file_path = $_SERVER['DOCUMENT_ROOT'].PATHAPP."/upload/freeze_employee/" . ''.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		}
		
		if(file_exists($file_path)){ 
			unlink($file_path);
		}
		
		$sqlQuery = "DELETE FROM MJ.MJ_TEMP_UPLOAD WHERE USERNAME='".$user_id."' AND id='".$idupload."'
						AND TRANSAKSI_KODE='".$dt_in['TRANSAKSI_KODE']."' ";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
	}
	else
	{
		$sqlQuery = "DELETE FROM MJ.MJ_TEMP_UPLOAD 
						WHERE USERNAME='".$user_id."' AND TRANSAKSI_KODE = '".$dt_in['TRANSAKSI_KODE']."' ";
		
		//echo $sqlQuery;exit;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);	
	}
	
	
	
?>