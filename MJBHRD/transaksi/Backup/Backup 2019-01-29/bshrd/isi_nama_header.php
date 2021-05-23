<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$perusahaan="";
$departement="";
$posisi="";
$plant="";
$tglmasuk="";
$gaji = "";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_pem'])){
	$nama_pem=$_POST['nama_pem'];

	$query = "SELECT  DISTINCT P.PERSON_ID
	,HOU.NAME PERUSAHAAN
	,REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 2) AS DEPT
	,POS.NAME AS JABATAN
	,HL.LOCATION_CODE PLANT
	,TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 1)) BANK
	,TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) NOREG
	,TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AN
	,TO_CHAR(P.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
	,PG.NAME
	, HOU.ORGANIZATION_ID, P.PREVIOUS_LAST_NAME
	FROM APPS.PER_PEOPLE_F P
	, APPS.PER_ALL_ASSIGNMENTS_F A
	, APPS.HR_ORGANIZATION_UNITS HOU
	, APPS.PER_JOBS J
	, APPS.FND_FLEX_VALUES_TL TL
	, APPS.PER_POSITIONS POS
	, APPS.HR_LOCATIONS HL
	, APPS.PER_PERSON_TYPES PPT
	, APPS.per_grades PG
	WHERE   P.PERSON_ID=A.PERSON_ID
	AND     P.PERSON_ID=$nama_pem
	AND     A.JOB_ID=J.JOB_ID(+)
	AND     REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING(+)
	AND     A.POSITION_ID=POS.POSITION_ID(+)
	AND     A.LOCATION_ID=HL.LOCATION_ID(+)
	AND     SYSDATE BETWEEN P.EFFECTIVE_START_DATE AND P.EFFECTIVE_END_DATE
	AND     (SYSDATE-5) BETWEEN NVL(A.EFFECTIVE_START_DATE,SYSDATE) AND NVL(A.EFFECTIVE_END_DATE,SYSDATE)
	--and     hl.location_code in ('Barata 46', 'Pataya', 'Ruko Kuning','Jakarta','Sanur')
	AND     LOWER(P.LAST_NAME) NOT LIKE '%trial%'
	AND     PPT.PERSON_TYPE_ID(+)=P.PERSON_TYPE_ID
	AND     PPT.USER_PERSON_TYPE<>'Ex-employee'
	and 	A.GRADE_ID = PG.GRADE_ID
	--AND     A.PAYROLL_ID IS NOT NULL
	AND     A.PRIMARY_FLAG='Y'
	AND     A.PEOPLE_GROUP_ID <> 3061
	AND     P.CURRENT_EMPLOYEE_FLAG = 'Y'
	AND     A.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	ORDER BY P.PERSON_ID";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$perusahaan=$row[1];
		$departement=$row[2];
		$posisi=$row[3];
		$plant=$row[4];
		$bank=$row[5];
		$noreg=$row[6];
		$an=$row[7];
		$grade=$row[9];
		$org_id=$row[10];
		$rek=$row[11];
		
	}
	
$hasil = $perusahaan . "|" . $departement . "|" . $posisi . "|" . $plant . "|" . $bank . "|" . $noreg . "|" . $an . "|" .$grade . "|" .$org_id . "|" .$rek;
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>