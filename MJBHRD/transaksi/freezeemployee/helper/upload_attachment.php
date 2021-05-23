<?PHP
	include_once '../../../main/koneksi.php';
	session_start();
	require_once ('../../../main/define_session.php');

	$data = 'gagal';
	$tmpname = $_FILES['arsipfileattc']['tmp_name'];
	$arsipType = '';
	$arsipSize = '';
	$arsipFile = '';

	$userid = $user_id;
	$date1 = date('Y-m-d');
	$date = md5($date1);

	$file_name = '';
	$file_size = 0;
	$file_type = '';
	$id = 0;

	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
		
	if (is_uploaded_file($tmpname))
	{
		$file_name = $_FILES['arsipfileattc']['name'];
		$file_size = $_FILES['arsipfileattc']['size'];
		$file_type = $_FILES['arsipfileattc']['type'];

		$queryCek = "SELECT COUNT(-1) FROM MJ.MJ_TEMP_UPLOAD WHERE FILENAME = '".$file_name."'
						AND FILETYPE = '".$file_type."' and TRANSAKSI_KODE = '".$dt_in['TRANSAKSI_KODE']."'
						AND USERNAME='".$user_id."'";
		$resultCek = oci_parse($con,$queryCek);
		oci_execute($resultCek);
		$rowCek = oci_fetch_row($resultCek);
		$jumCek = $rowCek[0]; 

		if($jumCek == 0){
			if($file_size <= 512000){
				$file_newname = $date.$file_size.md5($file_name);
				$ekstensi = end(explode(".", $file_name));
				$file_fullnewname = $file_newname.'.'.$ekstensi;
			
				if(!move_uploaded_file($tmpname, $_SERVER['DOCUMENT_ROOT'].PATHAPP."/upload/freeze_employee/".$file_fullnewname)){ //success not upload
					$result = array('success' => false);
				}
				else {
					$arsipType = $file_type;
					$arsipSize = $file_size;
					$arsipFile = $file_fullnewname;
					$userid = $user_id;		
					$id = '';
					$counter = 0;

					$resultSeq = oci_parse($con,"SELECT MJ.MJ_TEMP_UPLOAD_SEQ.nextval FROM dual");
					oci_execute($resultSeq);
					$row = oci_fetch_row($resultSeq);
					$id = $row[0];

					$sqlString 	= "	INSERT INTO MJ.MJ_TEMP_UPLOAD(ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE,
										FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE)
									VALUES('".$id."', '".APPCODE."', '1', '".$file_name."', '".$arsipSize."',
										'".$arsipType."', '".$userid."', TO_DATE('".$date1."', 'YYYY-MM-DD'),
										'".$dt_in['TRANSAKSI_KODE']."')";
								//echo $sqlString;
					$rs = oci_parse($con, $sqlString);
					oci_execute($rs);
				}
				$data = 'sukses';
			}
		}else{
			$id = 0;
			$data = 'gagal2';
		}
	}	
	$result = array('success' => true,
					'results' => $data,
					'rows' => $file_size,
					'id_temp' => $id,
				);
	echo json_encode($result);
?>