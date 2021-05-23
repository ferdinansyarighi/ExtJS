<?PHP
include '../../main/koneksi.php';
$hdid=$_GET['hd_id'];
	$result = oci_parse($con, "select MGED.ID, MGED.ID_ELEMENT, MGED.DEFAULT_VALUE, MGED.SATUAN, 
NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH, 
NVL(TO_CHAR(MGED.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MGED.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
from mj.mj_m_group_element_DETAIL MGED
INNER JOIN MJ.MJ_M_USER MMU ON MGED.CREATED_BY = MMU.ID
LEFT JOIN MJ.MJ_M_USER MMU2 ON MGED.LAST_UPDATED_BY = MMU2.ID
WHERE ID_GROUP = $hdid");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_ELEMENT_ID']=$row[1];
		$record['DATA_DEFAULT']=$row[2];
		$record['DATA_SATUAN']=$row[3];
		$record['DATA_OLEH']=$row[4];
		$record['DATA_TGL']=$row[5];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>