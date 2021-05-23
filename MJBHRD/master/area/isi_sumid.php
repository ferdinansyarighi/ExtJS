<?PHP 
include '../../main/koneksi.php';

$hdid=$_POST['hd_id']; //$_POST['hd_id']
	$count=0;
	$record = array();
	$result = oci_parse($con, "SELECT LOCATION_ID FROM MJ.MJ_M_AREA_DETAIL WHERE AREA_ID=$hdid");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		array_push($record, $row[0]);
		$count++;
	}
	echo json_encode($record);



?>