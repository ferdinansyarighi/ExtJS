<?PHP 
include '../../main/koneksi.php';

$hdid=$_POST['assignment_id']; //222209  $_POST['hd_id']
	
	$query = "SELECT COUNT(-1) FROM MJ.MJ_M_SHIFT WHERE ASSIGNMENT_ID=$hdid";
	//echo $query;
	$result = oci_parse($con, $query);
	oci_execute($result);
	$rowcount = oci_fetch_row($result);
	$totalRow = $rowcount[0];
	
	
	$result = array('success' => true,
					'results' => '',
					'rows' => $totalRow
				);
	echo json_encode($result);

?>