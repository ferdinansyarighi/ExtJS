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
	
	/*
	$hdid=$_GET['hd_id']; 
	$count=0;
	$result = oci_parse($con, "
	SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD'), TRANSAKSI_KODE 
	FROM MJ.MJ_M_UPLOAD 
	WHERE transaksi_id = $hdid 
	AND TRANSAKSI_KODE='PINJAMANHRD'
	");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$TransID=$row[0];
		$record['HD_ID']=$row[0];
		$record['DATA_FILE']=$row[1];
		$ekstensi	= end(explode(".", $row[1]));
		$record['DATA_ATTACHMENT']="<a href= " . PATHAPP . "/upload/pinjaman/" . $row[4].md5($row[5]).$row[2].md5($row[1]).".".$ekstensi . " target=_blank>" . $row[1] . "</a>";
		
		$data[]=$record;
		$count++;
	}
	*/
	
	
	//$hdid=$_GET['hd_id']; 
	$count=0;
	
	$result = oci_parse($con, "SELECT ID AS HD_ID , FILENAME AS DATA_FILE FROM MJ.MJ_TEMP_UPLOAD WHERE username='$user_id' AND TRANSAKSI_KODE='PINJAMANHRD'");
	
	// $result = oci_parse($con, "SELECT ID AS HD_ID , FILENAME AS DATA_FILE FROM MJ.MJ_M_UPLOAD WHERE username='$user_id' AND TRANSAKSI_KODE='PINJAMANHRD'");
	
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$TransID=$row[0];
		$record['HD_ID']=$row[0];
		$record['DATA_FILE']=$row[1];
		
		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_TEMP_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND id=$TransID AND TRANSAKSI_KODE = 'PINJAMANHRD'";
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
			$docattachment = "<a href= " . PATHAPP . "/upload/pinjaman/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
			//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
			if($doccount==0){
				$dataAtt = $docattachment;
			} else {
				$dataAtt .= ", " . $docattachment;
			}
			//echo $docattachment;
			//$mail->addAttachment( $docattachment );
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