<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$tglhariini = date('Y-F-d H:i:s', time());
//$bulan = date('F', time());
$bulan = date('F', strtotime("-1 month"));
$tahun = date('Y', time());
	
	if($bulan == 'January'){
		$bulanAngka = '01';
		$bulanBesok = '02';
	} elseif ($bulan == 'February'){
		$bulanAngka = '02';
		$bulanBesok = '03';
	} elseif ($bulan == 'March'){
		$bulanAngka = '03';
		$bulanBesok = '04';
	} elseif ($bulan == 'April'){
		$bulanAngka = '04';
		$bulanBesok = '05';
	} elseif ($bulan == 'May'){
		$bulanAngka = '05';
		$bulanBesok = '06';
	} elseif ($bulan == 'June'){
		$bulanAngka = '06';
		$bulanBesok = '07';
	} elseif ($bulan == 'July'){
		$bulanAngka = '07';
		$bulanBesok = '08';
	} elseif ($bulan == 'August'){
		$bulanAngka = '08';
		$bulanBesok = '09';
	} elseif ($bulan == 'September'){
		$bulanAngka = '09';
		$bulanBesok = '10';
	} elseif ($bulan == 'October'){
		$bulanAngka = '10';
		$bulanBesok = '11';
	} elseif ($bulan == 'November'){
		$bulanAngka = '11';
		$bulanBesok = '12';
	} else {
		$bulanAngka = '12';
		$bulanBesok = '01';
		$tahun = date('Y', strtotime("-1 year"));
	}
	
if(isset($_POST['personid'])){
	$person_id=$_POST['personid'];

	// $query = "SELECT PJ.NAME 
	// FROM APPS.PER_PEOPLE_F PPF
	// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	// INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PPF.FULL_NAME LIKE '$nama_pem'";
	$query = "SELECT nvl(MMG.TOTAL_GAJIAN,0)
        FROM MJHR.MJ_M_GAJI MMG
        INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
        INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
        LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
        LEFT JOIN APPS.FND_FLEX_VALUES_TL TL ON REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING
        LEFT JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
        LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
        WHERE BULAN='$bulan' AND TAHUN='$tahun'
        AND MMG.REVISI = 0--CASE WHEN TO_CHAR(SYSDATE, 'DD') <= 10 THEN 0 ELSE 1 END
        --AND REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) = 'Information Technology'
        AND PPF.PERSON_ID = $person_id
        ORDER BY MMG.PERSON_ID";
		//echo $query;exit;
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$hasil=$row[0];
	}
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>