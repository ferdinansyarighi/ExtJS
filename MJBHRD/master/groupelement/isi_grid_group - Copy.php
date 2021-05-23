<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "SELECT EGM.ID, NAMA_GROUP, DECODE(COMPANY, '', 'All', COMPANY) COMPANY_ID, NVL(HPU.NAME, 'All') COMPANY, 
DECODE(PLANT, '', 'All', PLANT) PLANT_ID, NVL(HL.LOCATION_CODE, 'All') PLANT, 
GRADE, PERIODE_GAJI, DEFAULT_VALUE, 
SATUAN, DECODE(EGM.STATUS,'Y', 'Active', 'N', 'Inactive') STATUS, 
NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH, 
NVL(TO_CHAR(EGM.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(EGM.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL,
EGMD.ID_ELEMENT
    FROM MJ.MJ_M_GROUP_ELEMENT EGM
    INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL EGMD ON EGM.ID = EGMD.ID_GROUP
    INNER JOIN MJ.MJ_M_USER MMU ON EGM.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON EGM.LAST_UPDATED_BY = MMU2.ID
    LEFT JOIN HR_OPERATING_UNITS HPU ON EGM.COMPANY = HPU.ORGANIZATION_ID
    LEFT JOIN HR_LOCATIONS HL ON EGM.PLANT = HL.LOCATION_ID
		WHERE 1=1");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA']=$row[1];
		$record['DATA_COMPANY_ID']=$row[2];
		$record['DATA_COMPANY']=$row[3];
		$record['DATA_PLANT_ID']=$row[4];
		$record['DATA_PLANT']=$row[5];
		$record['DATA_GRADE']=$row[6];
		$record['DATA_PERIODE']=$row[7];
		$record['DATA_DEFAULT']=$row[8];
		$record['DATA_SATUAN']=$row[9];
		$record['DATA_STATUS']=$row[10];
		$record['DATA_OLEH']=$row[11];
		$record['DATA_TGL']=$row[12];
		$record['DATA_ID_ELEMENT']=$row[13];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>