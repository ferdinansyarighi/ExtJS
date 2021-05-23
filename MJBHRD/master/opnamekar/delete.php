<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
  
$hdid="";
$data="gagal";

 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];
	$result = oci_parse($con, "DELETE FROM MJ.MJ_MASTER_OPNAME WHERE ID=$hdid");
  }
  
oci_execute($result);
$data="sukses";

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);







?>