<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT ID AS HD_ID
	, JENIS_CUTI AS DATA_JENIS_CUTI
	, TO_CHAR(TANGGAL_REFRESH, 'YYYY-MM-DD') AS DATA_TANGGAL_REFRESH
	, STATUS AS DATA_STATUS 
	FROM MJ.MJ_PM_CUTI
	WHERE APP_ID= " . APPCODE . "");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_JENIS_CUTI']=$row[1];
		$record['DATA_TANGGAL_REFRESH']=$row[2];
		$record['DATA_STATUS']=$row[3];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>