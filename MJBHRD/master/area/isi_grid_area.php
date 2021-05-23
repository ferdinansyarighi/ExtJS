<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT ID AS HD_ID, NAMA_AREA AS NAMA_AREA, STATUS AS DATA_STATUS FROM MJ.MJ_M_AREA WHERE APP_ID=" . APPCODE . "");
	oci_execute($result);
	
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['NAMA_AREA']=$row[1];
		$record['DATA_STATUS']=$row[2];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>