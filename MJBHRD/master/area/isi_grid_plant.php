<?PHP
include '../../main/koneksi.php';
$data="";
$hdid=$_GET['hd_id']; 
	$result = oci_parse($con, "SELECT LOCATION_ID AS HD_ID , LOCATION_CODE AS DATA_PLANTNAME FROM APPS.HR_LOCATIONS WHERE LOCATION_ID IN ($hdid)");
	oci_execute($result);
	
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_PLANTNAME']=$row[1];
		$data[]=$record;
	}	

echo json_encode($data); 
?>