<?PHP
include '../../main/koneksi.php';

$queryfilter='';

if(isset($_GET['tffilter'])){
	$nama = $_GET['tffilter'];
	$queryfilter = "and upper(NAMA_GROUP) like '%".$nama."%'";
}

	$result = oci_parse($con, "SELECT DISTINCT EGM.ID, NAMA_GROUP, DECODE(EGM.COMPANY, '', 'All', EGM.COMPANY) COMPANY_ID, NVL(HPU.NAME, 'All') COMPANY, 
DECODE(PLANT, '', 'All', PLANT) PLANT_ID, NVL(HL.LOCATION_CODE, 'All') PLANT, 
DECODE(GRADE_ID, '', 'All', GRADE_ID) GRADE_ID, EGM.PERIODE_GAJI, DECODE(EGM.STATUS,'Y', 'Active', 'N', 'Inactive') STATUS, 
NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH, 
NVL(TO_CHAR(EGM.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(EGM.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL,
NVL(PG.NAME, 'All') GRADE, EGM.GRUP, decode(MLG.ID,'',0,1) link_id, EGM.TIPE_LEMBUR
    FROM MJ.MJ_M_GROUP_ELEMENT EGM
    INNER JOIN MJ.MJ_M_USER MMU ON EGM.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON EGM.LAST_UPDATED_BY = MMU2.ID
    LEFT JOIN HR_OPERATING_UNITS HPU ON EGM.COMPANY = HPU.ORGANIZATION_ID
    LEFT JOIN HR_LOCATIONS HL ON EGM.PLANT = HL.LOCATION_ID
    LEFT JOIN APPS.PER_GRADES PG ON EGM.GRADE = PG.GRADE_ID
    left join mj.mj_m_link_group mlg on egm.id = mlg.id_group
		WHERE 1=1 $queryfilter
		order by nama_group");
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
		$record['DATA_GRADE_ID']=$row[6];
		$record['DATA_PERIODE']=$row[7];
		$record['DATA_STATUS']=$row[8];
		$record['DATA_OLEH']=$row[9];
		$record['DATA_TGL']=$row[10];
		$record['DATA_GRADE']=$row[11];
		$record['DATA_GROUP']=$row[12];
		$record['DATA_ID_LINK']=$row[13];
		$record['DATA_TIPE_LEMBUR']=$row[14];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>