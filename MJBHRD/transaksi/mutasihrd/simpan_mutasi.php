<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 
// deklarasi variable dan session
session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunGenNo=substr($tglskr, 0, 4);
$bulanGenNo=substr($tglskr, 6, 2);
$data="gagal";
$hdid="";
$typeform="";
$tingkat=0;
$vPersonID=0;
$noReq="";
$Keputusan = "";

if(isset($_POST['hdid']))
{
  	$hdid=$_POST['hdid'];
  	$setuju=$_POST['setuju'];
  	$note=$_POST['note'];
  	$direksi=$_POST['direksi'];
	
	if ($setuju=='Setuju') {
		$Keputusan = 'Approved';
	} else {
		$Keputusan = 'Disapproved';
	}	
}

	$queryTingkat = "SELECT MTM.TINGKAT, MTM.CREATED_BY
	FROM MJ.MJ_T_MUTASI MTM
	WHERE ID=$hdid";
	// echo $queryTingkat;
	$resultTingkat = oci_parse($con,$queryTingkat);
	oci_execute($resultTingkat);
	while($rowTingkat = oci_fetch_row($resultTingkat))
	{
		$tingkat=$rowTingkat[0];
		$pembuat=$rowTingkat[1];
			
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$hdidApp = $rowHSeq[0];
		
		$tingkat++;

		$query = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
		VALUES ($hdidApp, " . APPCODE . ", '$emp_id', $hdid, 'MPD', $tingkat, '$Keputusan', '$note', '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
	}

	//update header MPD
	if ($setuju=='Setuju') {
		$queryUpdate = "UPDATE MJ.MJ_T_MUTASI SET DIREKSI = '$direksi', STATUS_DOK = 'Approved', NOTE_PERSETUJUAN = '$note', TINGKAT=$tingkat, LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
	} else {
		$queryPosisi = "SELECT COUNT(-1)
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
		AND (PP.NAME LIKE '%MGR%')
		AND PPF.PERSON_ID=$pembuat";
		$resultPosisi = oci_parse($con,$queryPosisi);
		oci_execute($resultPosisi);
		$rowPosisi = oci_fetch_row($resultPosisi);
		$jumPosisi = $rowPosisi[0]; 
		
		if ($jumPosisi > 0){
			$tingkat = 1;
		} else {
			$tingkat = 0;
		}
		
		$queryUpdate = "UPDATE MJ.MJ_T_MUTASI SET STATUS_DOK = 'Disapproved', NOTE_PERSETUJUAN = '$note', TINGKAT=$tingkat, LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
	}
	//end update header MPD
	
	$data="sukses";

$querydata = "SELECT ID || '|' || NO_REQUEST FROM MJ.MJ_T_MUTASI WHERE ID=$hdid ";
$resultdata = oci_parse($con,$querydata);
oci_execute($resultdata);
$rowdata = oci_fetch_row($resultdata);
$dataNoSIK = $rowdata[0]; 


// Tambahan dari Yuke, 1 Oktober 2018, untuk mendapatkan count PAYROLL_ID sebelum create file pdf 

$query_count_payroll = "
		SELECT  COUNT( * )
		FROM    APPS.PER_PEOPLE_F PPF
		INNER JOIN  MJ_T_MUTASI MTM ON KARYAWAN_ID = PPF.PERSON_ID
		INNER JOIN  APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
		LEFT JOIN   APPS.PER_JOBS PJ ON PJ.JOB_ID = PAF.JOB_ID AND PJ.JOB_ID = MTM.DEPT_LAMA_ID
		LEFT JOIN   APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID AND PP.POSITION_ID = MTM.POSISI_LAMA_ID
		LEFT JOIN   APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
		LEFT JOIN   APPS.PER_VALID_GRADES_V PVG  ON PVG.POSITION_ID = PP.POSITION_ID AND PVG.GRADE_ID = MTM.GRADE_LAMA_ID
		INNER JOIN  APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.MGR_LAMA AND PPF1.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN   APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
		INNER JOIN  APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.MGR_BARU AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN   APPS.PER_POSITIONS PP2 ON PP2.POSITION_ID = MTM.POSISI_BARU_ID
		LEFT JOIN   APPS.HR_LOCATIONS HL2 ON HL2.LOCATION_ID = MTM.LOKASI_BARU_ID
		INNER JOIN  APPS.PAY_PEOPLE_GROUPS PPG ON PPG.PEOPLE_GROUP_ID = PAF.PEOPLE_GROUP_ID 
						AND (   UPPER( GROUP_NAME ) LIKE 'CP%'
								OR UPPER( GROUP_NAME ) LIKE 'PLANT%' )
		WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE
		AND     PAF.PRIMARY_FLAG = 'Y'
		AND MTM.ID = $hdid
		";

$result_count_payroll = oci_parse( $con, $query_count_payroll );
oci_execute( $result_count_payroll );

$rowdata_count_payroll = oci_fetch_row( $result_count_payroll );
$data_count_payroll = $rowdata_count_payroll[0];



/*
$query_count_non_payroll = "
SELECT  COUNT( * )
FROM MJ.MJ_T_MUTASI MTM
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.KARYAWAN_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.CREATED_BY AND PPF1.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.DIREKSI AND PPF2.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF3 ON PPF3.PERSON_ID = MTM.MGR_LAMA AND PPF3.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF4 ON PPF4.PERSON_ID = MTM.MGR_BARU AND PPF4.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_PEOPLE_F PPF5 ON PPF5.PERSON_ID = MTM.DIREKSI AND PPF5.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_GRADES PG ON MTM.GRADE_LAMA_ID = PG.GRADE_ID 
INNER JOIN APPS.PER_GRADES PG1 ON MTM.GRADE_BARU_ID = PG1.GRADE_ID 
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID 
LEFT JOIN APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
LEFT JOIN APPS.PER_POSITIONS PP1 ON PP1.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN APPS.HR_LOCATIONS HL1 ON HL1.LOCATION_ID = MTM.LOKASI_BARU_ID 
LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PP.ORGANIZATION_ID = HOU.ORGANIZATION_ID
LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU1 ON PP1.ORGANIZATION_ID = HOU1.ORGANIZATION_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTM.ID = MTA.TRANSAKSI_ID AND MTA.TRANSAKSI_KODE = 'MPD' 
    AND MTA.STATUS = 'Approved' AND 1 = MTA.TINGKAT AND MTM.MGR_LAMA = MTA.EMP_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA2 ON MTM.ID = MTA2.TRANSAKSI_ID AND MTA2.TRANSAKSI_KODE = 'MPD' 
    AND MTA2.STATUS = 'Approved' AND 2 = MTA2.TINGKAT AND MTM.MGR_BARU = MTA2.EMP_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA3 ON MTM.ID = MTA3.TRANSAKSI_ID AND MTA3.TRANSAKSI_KODE = 'MPD' 
    AND MTA3.STATUS = 'Approved' AND 3 = MTA3.TINGKAT AND MTA3.TINGKAT = 3  
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
WHERE 1=1 AND MTM.ID = $hdid
AND PAF.PRIMARY_FLAG = 'Y'
AND PAF.PAYROLL_ID IS NULL";

$result_count_non_payroll = oci_parse( $con, $query_count_non_payroll );
oci_execute( $result_count_non_payroll );

$rowdata_count_non_payroll = oci_fetch_row( $result_count_non_payroll );
$data_count_non_payroll = $rowdata_count_non_payroll[0];
*/



$result = array('success' => true,
				'results' => $dataNoSIK,
				'rows' => $data, 
				'setuju' => $setuju,
				'keputusan' => $Keputusan,
				'results_payroll' => $data_count_payroll
				
			);
			
			// 'results_non_payroll' => $data_count_non_payroll
			
echo json_encode($result);







?>