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

  $data = "";
  $idtrans="";
  if(isset($_POST['idtrans'])){
  	
	$idtrans=$_POST['idtrans'];
/*	echo $idtrans;exit;
	$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='RESIGN'";
	$result = oci_parse($con, $query);
	oci_execute($result); */

	$query = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " 
	AND TRANSAKSI_ID=$idtrans
	AND TRANSAKSI_KODE='RESIGN'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$vTransID=$row[0];
		$vFilename=$row[1];
		$vFilesize=$row[2];
		$vFiletype=$row[3];
		$vFilekode=$row[6];
		
		$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_TEMP_UPLOAD_SEQ.nextval FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		
		$queryUpload = "INSERT INTO MJ.MJ_TEMP_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
		VALUES ($seqD, " . APPCODE . ", $vTransID, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
		//echo $query;
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);

		$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
		FROM MJ.MJ_M_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=".$row[0]." AND TRANSAKSI_KODE LIKE 'RESIGN'";
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
			$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
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
	}


  }
	
	
	
?>