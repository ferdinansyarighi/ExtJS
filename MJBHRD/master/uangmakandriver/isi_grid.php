<?PHP
include '../../main/koneksi.php';

	$status = "";
	$result = oci_parse($con, "SELECT MUMD.ID AS HD_ID
	, MMRD.ID AS DATA_PLANT_AREA_ID
	, MMRD.TIPE_PLANT AS DATA_TIPE
	, MMRD.PLANT_AREA AS DATA_PLANT_AREA
	, MUMD.MIN_JAM_KERJA AS DATA_JAM_KERJA
	, MUMD.NOMINAL AS DATA_NOMINAL
	, TO_CHAR(MUMD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') AS DATA_START_DATE 
	, TO_CHAR(MUMD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') AS DATA_END_DATE 
	, NVL(UPD.FULL_NAME, CRE.FULL_NAME) DATA_UPDATE_BY
	, NVL(TO_CHAR(MUMD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:MI'), TO_CHAR(MUMD.CREATED_DATE, 'DD-MON-YYYY HH24:MI')) DATA_UPDATE_DATE
	FROM MJ.MJ_M_UANG_MAKAN_DRIVER MUMD
	INNER JOIN MJ.MJ_M_NOMINAL_RITASI_DRIVER MMRD ON MMRD.ID = MUMD.MJ_M_NOMINAL_RITASI_DRIVER_ID
	LEFT JOIN
	(
		SELECT MMU.ID, PPF.FULL_NAME
		FROM MJ.MJ_M_USER MMU
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MMU.EMP_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	) UPD ON UPD.ID = MUMD.LAST_UPDATED_BY
	LEFT JOIN
	(
		SELECT MMU.ID, PPF.FULL_NAME
		FROM MJ.MJ_M_USER MMU
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MMU.EMP_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	) CRE ON CRE.ID = MUMD.CREATED_BY
	WHERE 1=1
	ORDER BY MMRD.TIPE_PLANT, MMRD.PLANT_AREA");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_PLANT_AREA_ID']=$row[1];
		$record['DATA_TIPE']=$row[2];
		$record['DATA_PLANT_AREA']=$row[3];
		$record['DATA_JAM_KERJA']=$row[4];
		$record['DATA_NOMINAL']=$row[5];
		$record['DATA_START_DATE']=$row[6];
		$record['DATA_END_DATE']=$row[7];
		$record['DATA_UPDATE_BY']=$row[8];
		$record['DATA_UPDATE_DATE']=$row[9];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>