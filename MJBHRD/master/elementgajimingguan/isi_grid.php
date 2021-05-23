<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT EGM.ID, NAMA_ELEMENT, KOMPONEN, TYPE_ELEMENT, DECODE(EGM.STATUS,'Y', 'Active', 'N', 'Inactive') STATUS, NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH, 
    NVL(TO_CHAR(EGM.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(EGM.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL 
    FROM MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM
    INNER JOIN MJ.MJ_M_USER MMU ON EGM.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON EGM.LAST_UPDATED_BY = MMU2.ID
	order by komponen, nama_element asc
	");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA']=$row[1];
		$record['DATA_KOMPONEN']=$row[2];
		$record['DATA_TYPE']=$row[3];
		$record['DATA_STATUS']=$row[4];
		$record['DATA_OLEH']=$row[5];
		$record['DATA_TGL']=$row[6];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>