<?PHP
include '../../main/koneksi.php';

	$status = "";
	$result = oci_parse($con, "SELECT MNRD.ID AS HD_ID
	, MNRD.TIPE_PLANT AS DATA_TIPE
	, MNRD.PLANT_AREA AS DATA_PLANT_AREA
	, TO_CHAR(MNRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') AS DATA_START_DATE 
	, TO_CHAR(MNRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') AS DATA_END_DATE 
	, NVL(UPD.FULL_NAME, CRE.FULL_NAME) DATA_UPDATE_BY
	, NVL(TO_CHAR(MNRD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:MI'), TO_CHAR(MNRD.CREATED_DATE, 'DD-MON-YYYY HH24:MI')) DATA_UPDATE_DATE
	FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER MNRD
	LEFT JOIN
	(
		SELECT MMU.ID, PPF.FULL_NAME
		FROM MJ.MJ_M_USER MMU
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MMU.EMP_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	) UPD ON UPD.ID = MNRD.LAST_UPDATED_BY
	LEFT JOIN
	(
		SELECT MMU.ID, PPF.FULL_NAME
		FROM MJ.MJ_M_USER MMU
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MMU.EMP_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	) CRE ON CRE.ID = MNRD.CREATED_BY
	WHERE 1=1
	ORDER BY MNRD.TIPE_PLANT, MNRD.PLANT_AREA");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_TIPE']=$row[1];
		$record['DATA_PLANT_AREA']=$row[2];
		$record['DATA_START_DATE']=$row[3];
		$record['DATA_END_DATE']=$row[4];
		$record['DATA_UPDATE_BY']=$row[5];
		$record['DATA_UPDATE_DATE']=$row[6];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>