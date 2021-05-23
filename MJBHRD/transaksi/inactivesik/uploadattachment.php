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

$idsik 	= $_POST['idsik'];
$tmpname = $_FILES['arsipfile']['tmp_name'];
$vPath	= "../../upload/SIK".$idsik.".pdf";
$file_name	= '';
if (is_uploaded_file($tmpname))
{
	$file_name = $_FILES['arsipfile']['name'];
	if(!move_uploaded_file($tmpname, $vPath)){ //success not upload
		$result = array('success' => false);
	}
}	 
// $userid 	= $user_id;		
// $id		= '';
// $counter	= 0;

// $resultSeq = oci_parse($con,"SELECT MJ.MJ_TEMP_UPLOAD_SEQ.nextval FROM dual");
// oci_execute($resultSeq);
// $row = oci_fetch_row($resultSeq);
// $id = $row[0];

// $sqlString 	= "UPDATE MJ.MJ_T_SIK SET ATTACHMENT_BA = '".$file_name."' WHERE ID=".$idsik;
// //echo $sqlString;
// $rs = oci_parse($con, $sqlString);
// oci_execute($rs);
$output = array();
// if($rs){
   $output ['success'] = true;
// }
// else{
   // $output ['success'] = false;
// }
//$output['jumlah']=$file_name;
echo json_encode($output);
?>