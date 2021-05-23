<?PHP
include '../../main/koneksi.php';

	$result = oci_parse($con, "select mlg.id, DECODE(mlg.COMPANY, '', 'All', mlg.COMPANY) COMPANY_ID, NVL(HPU.NAME, 'All') COMPANY,
    mlg.person_id, ppf.full_name, mlg.id_group, egm.nama_group,
    NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH, 
    NVL(TO_CHAR(EGM.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(EGM.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL,
	mlg.periode_gaji
    from mj.mj_m_link_group mlg
    inner join MJ.MJ_M_GROUP_ELEMENT EGM on mlg.id_group = EGM.id 
    LEFT JOIN HR_OPERATING_UNITS HPU ON mlg.COMPANY = HPU.ORGANIZATION_ID
    inner join per_people_f ppf on mlg.person_id = ppf.person_id and 
    current_employee_flag = 'Y' and effective_end_date > sysdate
    INNER JOIN MJ.MJ_M_USER MMU ON mlg.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON mlg.LAST_UPDATED_BY = MMU2.ID
		WHERE 1=1");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_COMPANY_ID']=$row[1];
		$record['DATA_COMPANY']=$row[2];
		$record['DATA_PERSON_ID']=$row[3];
		$record['DATA_PERSON']=$row[4];
		$record['DATA_ID_GROUP']=$row[5];
		$record['DATA_GROUP']=$row[6];
		$record['DATA_OLEH']=$row[7];
		$record['DATA_TGL']=$row[8];
		$record['DATA_PERIODE']=$row[9];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>