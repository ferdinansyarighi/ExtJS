<?PHP
include '../../main/koneksi.php';
$data="";
$headerid=$_GET['headerid']; 
$result = oci_parse($con, "SELECT ID
, RITASI_KE 
, JARAK_FROM
, JARAK_TO
, NOMINAL 
FROM MJ.MJ_M_NOMINAL_RD_DETAIL 
WHERE MJ_M_NOMINAL_RITASI_DRIVER_ID = $headerid AND STATUS = 'Y'
ORDER BY RITASI_KE, JARAK_FROM");
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_RITASI_KE']=$row[1];
	$record['DATA_JARAK_AWAL']=$row[2];
	$record['DATA_JARAK_AKHIR']=$row[3];
	$record['DATA_NOMINAL']=$row[4];
	$data[]=$record;
}	

echo json_encode($data); 
?>