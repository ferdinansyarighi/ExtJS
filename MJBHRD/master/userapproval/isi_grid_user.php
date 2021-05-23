<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT MMU.ID AS HD_ID
    , MMU.FULL_NAME AS DATA_NAMA_USER
    , MMU.EMAIL AS DATA_EMAIL
    , MMU.TINGKAT - 1 AS DATA_TINGKAT
    , MMU.STATUS AS DATA_STATUS 
    , MMU.NAMA_AREA AS DATA_AREA
    FROM MJ.MJ_M_USERAPPROVAL MMU  
    WHERE APP_ID= " . APPCODE . "");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA_USER']=$row[1];
		$record['DATA_EMAIL']=$row[2];
		$record['DATA_TINGKAT']=$row[3];
		$record['DATA_STATUS']=$row[4];
		$record['DATA_AREA']=$row[5];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>