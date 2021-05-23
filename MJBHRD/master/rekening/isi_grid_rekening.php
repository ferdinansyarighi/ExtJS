<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT ID AS HD_ID
	, NAMA_BANK AS DATA_NAMA_BANK
	, ATAS_NAMA AS DATA_ATAS_NAMA
	, NO_REKENING AS DATA_NO_REKENING
	, STATUS AS DATA_STATUS 
	FROM MJ.MJ_M_REKENING
	WHERE APP_ID= " . APPCODE . "");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA_BANK']=$row[1];
		$record['DATA_ATAS_NAMA']=$row[2];
		$record['DATA_NO_REKENING']=$row[3];
		$record['DATA_STATUS']=$row[4];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>