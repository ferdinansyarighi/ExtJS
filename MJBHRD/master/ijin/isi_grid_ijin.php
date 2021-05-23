<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT ID AS HD_ID
	, JENIS_IJIN AS DATA_JENIS_IJIN
	, JUMLAH_HARI AS DATA_JUMLAH_HARI
	, STATUS AS DATA_STATUS 
	FROM MJ.MJ_M_IJIN
	WHERE APP_ID= " . APPCODE . "");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_JENIS_IJIN']=$row[1];
		$record['DATA_JUMLAH_HARI']=$row[2];
		$record['DATA_STATUS']=$row[3];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>