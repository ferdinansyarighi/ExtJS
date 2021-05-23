<?PHP
include '../../main/koneksi.php';
$union='';
$filter='';	
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
	//$dtid = $_GET['dtid'];
	//$filter = " AND nvl(MGE.ID_GROUP, 0) = $dtid";
	$result = oci_parse($con, "SELECT MPD.ID, PPF.FULL_NAME
		, CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END DEPT
		, PPS.NAME
		, HL.LOCATION_CODE, MPD.PERIODE_GAJI
		, MPD.SATUAN, MPD.NOMINAL
		, CASE WHEN MPD.SATUAN = 'RP' THEN 'Rp '||to_char(MPD.NOMINAL, '9,999,999,999,999,999.00')
			ELSE MPD.NOMINAL||MPD.SATUAN END PENDING
		, TO_CHAR(MPD.PERIODE_AWAL, 'YYYY-MM-DD'), TO_CHAR(MPD.PERIODE_AKHIR, 'YYYY-MM-DD')
		, TO_CHAR(MPD.PERIODE_AWAL, 'DD-MON-YYYY')||' s/d '||TO_CHAR(MPD.PERIODE_AKHIR, 'DD-MON-YYYY') PERIODE
		, MPD.KETERANGAN, MPD.STATUS
		, PAF.PERSON_ID
		, to_char(created_date, 'YYYY-MM-DD') CREATED_DATE
		FROM MJ.MJ_T_PENDING_GAJI MPD
		INNER JOIN PER_ASSIGNMENTS_F PAF ON MPD.ASSIGNMENT_ID = PAF.ASSIGNMENT_ID AND PRIMARY_FLAG = 'Y'
		INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > TRUNC(SYSDATE)
		LEFT JOIN per_positions pps ON paf.position_id = pps.position_id
		INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
		INNER JOIN HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
	");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA']=$row[1];
		$record['DATA_DEPT']=$row[2];
		$record['DATA_POS']=$row[3];
		$record['DATA_LOC']=$row[4];
		$record['DATA_PERIODE_GAJI']=$row[5];
		$record['DATA_SATUAN']=$row[6];
		$record['DATA_NOMINAL']=$row[7];
		$record['DATA_PENDING']=$row[8];
		$record['DATA_PERIODE_AWAL']=$row[9];
		$record['DATA_PERIODE_AKHIR']=$row[10];
		$record['DATA_PERIODE']=$row[11];
		$record['DATA_KET']=$row[12];
		$record['DATA_STATUS']=$row[13];
		$record['DATA_PERSON_ID']=$row[14];
		$record['DATA_CREATED_DATE']=$row[15];
		// $query = "SELECT MMG.TOTAL_GAJIAN
			// FROM MJHR.MJ_M_GAJI MMG
			// INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			// INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
			// LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
			// LEFT JOIN APPS.FND_FLEX_VALUES_TL TL ON REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING
			// LEFT JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
			// LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
			// WHERE BULAN='$bulan' AND TAHUN='$tahun'
			// AND MMG.REVISI = 0--CASE WHEN TO_CHAR(SYSDATE, 'DD') <= 10 THEN 0 ELSE 1 END
			// --AND REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) = 'Information Technology'
			// AND PPF.PERSON_ID = ".$row[14]."
			// ORDER BY MMG.PERSON_ID";
		// $result = oci_parse($con, $query);
		// oci_execute($result);
		// while($row = oci_fetch_row($result))
		// {
			// $hasil=$row[0];
		// }
		// $record['DATA_MAX_NOMINAL']=$hasil;
		$data[]=$record;
	}


	
	
echo json_encode($data); 
?>