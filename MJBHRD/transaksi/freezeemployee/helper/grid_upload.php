<?PHP
	session_start();
	require_once ('../../../main/koneksi.php');
	require_once ('../../../main/define_session.php');
	
	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
	
	$count=0;
	$result = oci_parse($con, "SELECT ID AS HD_ID , FILENAME AS DATA_FILE FROM MJ.MJ_TEMP_UPLOAD
							WHERE username='".$user_id."' AND TRANSAKSI_KODE='".$dt_in['TRANSAKSI_KODE']."' ");
	oci_execute($result);
	
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$TransID=$row[0];
		$record['HD_ID']=$row[0];
		$record['DATA_FILE']=$row[1];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
						FROM MJ.MJ_TEMP_UPLOAD
						WHERE APP_ID='".APPCODE."' AND id='".$TransID."'
							AND TRANSAKSI_KODE = '".$dt_in['TRANSAKSI_KODE']."' ";
		//echo $queryAtt;exit;
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
			$docattachment = "<a href= " . PATHAPP . "/upload/freeze_employee/" . ''.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
			
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			$doccount++;
		}
		$record['DATA_ATTACHMENT']=$dataAtt;
		
		$data[]=$record;
		$count++;
	}	
	
	if($count==0)
	{
		$data='';
	}
	echo json_encode($data); 
?>