<?PHP
include '../../main/koneksi.php';
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
$id	= 0;
$data	= 'gagal';
$tmpname = $_FILES['arsipfile']['tmp_name'];
$arsipType	= '';
$arsipSize	= '';
$arsipFile	= '';
$userid 	= $user_id;
$date1	= date('Y-m-d');
$date		= md5($date1);
//$date2		= $

$file_name = '';
$file_size = 0;
$file_type = '';

//print_r ($_FILES['arsipfile']);exit;
if (is_uploaded_file($tmpname))
{
	//echo $_FILES['arsipfile']['name'];exit;
	$file_name = $_FILES['arsipfile']['name'];
	$file_size = $_FILES['arsipfile']['size'];
	$file_type = $_FILES['arsipfile']['type'];
	
	$queryCek = "SELECT COUNT(-1) FROM MJ.MJ_TEMP_UPLOAD WHERE FILENAME = '$file_name' and FILETYPE = '$file_type' and TRANSAKSI_KODE = 'BON' AND USERNAME='$user_id'";
	$resultCek = oci_parse($con,$queryCek);
	oci_execute($resultCek);
	$rowCek = oci_fetch_row($resultCek);
	$jumCek = $rowCek[0]; 
	
	if($jumCek == 0){
		if($file_size <= 512000){
			$file_newname =  $userid.$date.$file_size.md5($file_name);
			$ekstensi	= end(explode(".", $file_name));
			$file_fullnewname = $file_newname.'.'.$ekstensi;
			if(!move_uploaded_file($tmpname,"../../upload/bs/".$file_fullnewname)){ //success not upload
				$result = array('success' => false);
			}
			else {
				$arsipType	= $file_type;
				$arsipSize	= $file_size;
				$arsipFile	= $file_fullnewname;
				
				$userid 	= $user_id;		
				$id		= '';
				$counter	= 0;

				$resultSeq = oci_parse($con,"SELECT MJ.MJ_TEMP_UPLOAD_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$id = $row[0];

				$sqlString 	= "	INSERT INTO MJ.MJ_TEMP_UPLOAD(ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
								VALUES($id, " . APPCODE . ", '1', '".$file_name."', '".$arsipSize."', '".$arsipType."', '".$userid."', TO_DATE('$date1', 'YYYY-MM-DD'), 'BON')";
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