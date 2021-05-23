<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_pem'])){
	$nama_pem=str_replace("'", "''", $_POST['nama_pem']);               

	$query = "SELECT NVL(GP.SCREEN_ENTRY_VALUE,0)
	+ (NVL(UM.SCREEN_ENTRY_VALUE,0) * 25)
	+ (NVL(UT.SCREEN_ENTRY_VALUE,0) * 25)
	+ NVL(TJ.SCREEN_ENTRY_VALUE,0)
	+ NVL(TG.SCREEN_ENTRY_VALUE,0)
	+ NVL(TL.SCREEN_ENTRY_VALUE,0)
	+ NVL(TK.SCREEN_ENTRY_VALUE,0) GAJI
	FROM APPS.PER_PEOPLE_F p
		, APPS.PER_ALL_ASSIGNMENTS_F a
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Gaji_Pokok'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) GP
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Tunjangan_Jabatan'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) TJ
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Tunjangan_Grade'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) TG
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Tunjangan_Kerajinan'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) TK
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Uang_Makan'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) UM
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Uang_Transport'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) UT
		,(SELECT  PEEF.ASSIGNMENT_ID
				,PEEF.ELEMENT_TYPE_ID
				,PET.ELEMENT_NAME
				,PIVF.NAME
				,PEEVF.SCREEN_ENTRY_VALUE
		FROM    APPS.PAY_ELEMENT_ENTRIES_F PEEF
				,APPS.PAYBV_ELEMENT_TYPE PET
				,APPS.PAY_ELEMENT_ENTRY_VALUES_F PEEVF
				,APPS.PAY_INPUT_VALUES_F PIVF
		WHERE   PEEF.ELEMENT_TYPE_ID=PET.ELEMENT_TYPE_ID
		AND     PEEVF.ELEMENT_ENTRY_ID = PEEF.ELEMENT_ENTRY_ID
		AND     PIVF.INPUT_VALUE_ID=PEEVF.INPUT_VALUE_ID
		AND     PIVF.NAME<>'Pay Value'
		AND     PET.ELEMENT_NAME='E_Tunjangan_Lokasi'
		AND     PET.PROCESSING_TYPE='Recurring'
		AND     (SYSDATE BETWEEN PEEVF.EFFECTIVE_START_DATE AND PEEVF.EFFECTIVE_END_DATE)
		ORDER BY PEEF.ASSIGNMENT_ID) TL
	WHERE   P.PERSON_ID=A.PERSON_ID
	AND     P.PERSON_ID=$nama_pem
	AND     SYSDATE BETWEEN P.EFFECTIVE_START_DATE AND P.EFFECTIVE_END_DATE
	AND     LOWER(P.FULL_NAME) NOT LIKE '%salah%'
	AND     LOWER(P.LAST_NAME) NOT LIKE '%trial%'
	AND     GP.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     TJ.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     TG.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     TK.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     UM.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     UT.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     TL.ASSIGNMENT_ID(+)=A.ASSIGNMENT_ID
	AND     NVL(GP.SCREEN_ENTRY_VALUE,0)<>0
	AND A.PAYROLL_ID IS NOT NULL
	AND A.PRIMARY_FLAG='Y'
	AND A.PEOPLE_GROUP_ID <> 3061
	ORDER BY P.PERSON_ID";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$hasil=$row[0] . "|" .number_format($row[0], 2, ',', '.');
	}
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>