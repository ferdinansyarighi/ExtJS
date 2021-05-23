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
	$hdid=$_GET['hd_id']; 
	$count=0;
	$result = oci_parse($con, "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD'), TRANSAKSI_KODE FROM MJ.MJ_M_UPLOAD WHERE transaksi_id = $hdid AND TRANSAKSI_KODE='PINJAMANDIR'");
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
if($count==0)
{
	$data='';
}
echo json_encode($data); 
?>