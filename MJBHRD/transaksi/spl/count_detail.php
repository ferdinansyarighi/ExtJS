<?PHP 
include '../../main/koneksi.php';

$hdid=$_POST['hd_id']; //222209  $_POST['hd_id']
	
	$query = "SELECT COUNT(-1) FROM MJ.MJ_T_SPL_DETAIL WHERE MJ_T_SPL_ID=$hdid";
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