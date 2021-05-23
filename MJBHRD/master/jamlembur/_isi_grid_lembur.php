<?PHP
include '../../main/koneksi.php';
	$status ='';
	$result = oci_parse($con, "SELECT MMJ.ID AS HD_ID
    , MMJ.PLANT_ID AS DATA_PLANTID
    , MMJ.POSITION_ID AS DATA_POSISIID
	, HL.LOCATION_CODE AS DATA_NAMAPLANT
	, PP.NAME AS DATA_NAMAPOSISI
	, MMJ.JUMLAH_SHIFT AS DATA_JUMLAHSHIFT
	, MMJ.JUMLAH_LEMBUR AS DATA_JUMLAHLEMBUR
    , MMJ.STATUS AS DATA_STATUS 
    FROM MJ.MJ_M_JAMLEMBUR MMJ
	INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMJ.PLANT_ID
	INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=MMJ.POSITION_ID
    WHERE MMJ.APP_ID= " . APPCODE . "");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_PLANTID']=$row[1];
		$record['DATA_POSISIID']=$row[2];
		$record['DATA_NAMAPLANT']=$row[3];
		$record['DATA_NAMAPOSISI']=$row[4];
		$record['DATA_JUMLAHSHIFT']=$row[5];
		$record['DATA_JUMLAHLEMBUR']=$row[6];
		if($row[7]=='A'){
			$status = 'ACTIVE';
		} else {
			$status = 'INACTIVE';
		}
		$record['DATA_STATUS']=$status;
		$data[]=$record;
	}
	
echo json_encode($data); 
?>