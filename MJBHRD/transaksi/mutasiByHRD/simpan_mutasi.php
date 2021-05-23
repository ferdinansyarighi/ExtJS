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
$bulanGenNo=substr($tglskr, 5, 2);
$data="gagal";
$hdid="";
$typeform="";
$tingkat=0;
$vPersonID=0;
$noReq="";
$kodeMutasi="";
$kodePlant="";
$arrNamaFile=array();
$countID=0;



// Penambahan tipe "Mutasi dan Promosi" dan "Mutasi dan Demosi" oleh Yuke di 27 Sept 2018.

 if(isset($_POST['typeform']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$noReq=$_POST['noReq'];
  	$tipe=$_POST['tipe'];
	if($tipe == 'Promosi'){
		$kodeMutasi="SM1";
	} else if($tipe == 'Mutasi'){
		$kodeMutasi="SM2";
	} else if($tipe == 'Demosi'){
		$kodeMutasi="SM3";
	} else if($tipe == 'Mutasi dan Promosi'){
		$kodeMutasi="SM4";
	} else if($tipe == 'Mutasi dan Demosi'){
		$kodeMutasi="SM5";
	
	// } else {
	// 	$kodeMutasi="SM3";
	
	
	
	}
  	$statuskar=$_POST['statuskar'];
  	$sifatperubahan=$_POST['sifatperubahan'];
  	$lama=$_POST['lama'];
  	$karyawan=$_POST['karyawan'];
  	$deptlama=$_POST['deptlama'];
  	$mgrlama=$_POST['mgrlama'];
  	$posisilama=$_POST['posisilama'];
  	$lokasilama=$_POST['lokasilama'];
  	$gradelama=$_POST['gradelama'];
  	$gajilama=$_POST['gajilama'];
  	$dept=$_POST['dept'];
  	$mgrbaru=$_POST['mgrbaru'];
  	$posisi=$_POST['posisi'];
  	$lokasi=$_POST['lokasi'];
  	$grade=$_POST['grade'];
  	$gaji=$_POST['gaji'];
  	$direksi=$_POST['direksi'];
  	$alasan=str_replace("'", "''", $_POST['alasan']);
  	$keterangan=str_replace("'", "''", $_POST['keterangan']);
  	$tanggal=$_POST['tanggal'];
  	$aktif=$_POST['aktif'];
	$arrNamaFile=json_decode($_POST['arrDataFile']);
	
	$countID = count($arrNamaFile);
	
	if($aktif == 'true'){
		$aktif = 'Y';
	} else {
		$aktif = 'N';
	}
  }

if ($typeform=='tambah')
{
	
	$query_inprocess = "
		SELECT  COUNT( * )
		FROM    MJ_T_MUTASI
		WHERE   KARYAWAN_ID = $karyawan
		AND     STATUS_DOK = 'In process'";
	
	// echo $query_inprocess; exit;
	
	$result_inprocess = oci_parse( $con, $query_inprocess );
	oci_execute( $result_inprocess );
	$row_inprocess = oci_fetch_row( $result_inprocess );
	$jum_inprocess = $row_inprocess[0]; 
	
	// echo '$jum_inprocess:' . $jum_inprocess; exit;
	
	if ( $jum_inprocess != 0) {
		
		$data = "sudah_ada";
		
	} else {
		
		$queryCek = "SELECT COUNT(-1) FROM MJ.MJ_T_MUTASI WHERE REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 6) = '$tahunGenNo'";
		$resultCek = oci_parse($con,$queryCek);
		oci_execute($resultCek);
		$rowCek = oci_fetch_row($resultCek);
		$jumCek = $rowCek[0]; 
		
		if($jumCek > 0){
			$querycount = "SELECT MAX(REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 1)) + 1 NO_REQ 
			FROM MJ.MJ_T_MUTASI 
			WHERE REGEXP_SUBSTR(NO_REQUEST, '[^/]+', 1, 6) = '$tahunGenNo'";
			$resultcount = oci_parse($con,$querycount);
			oci_execute($resultcount);
			$rowcount = oci_fetch_row($resultcount);
			$lastNo = $rowcount[0]; 
		} else {
			$lastNo = "1"; 
		}
		
		$queryPlant = "SELECT ADDRESS_LINE_3 
		FROM APPS.HR_LOCATIONS 
		WHERE LOCATION_ID = '$lokasilama' ";
		$resultPlant = oci_parse($con,$queryPlant);
		oci_execute($resultPlant);
		$rowPlant = oci_fetch_row($resultPlant);
		$kodePlant = $rowPlant[0]; 
		
		$jumno=strlen($lastNo);
		if($jumno==1){
			$nourut = "0000".$lastNo;
		} else if ($jumno==2){
			$nourut = "000".$lastNo;
		} else if ($jumno==3){
			$nourut = "00".$lastNo;
		} else if ($jumno==4){
			$nourut = "0".$lastNo;
		} else {
			$nourut = $lastNo;
		}
		
		$noReq = $nourut . "/" . $kodeMutasi . "/HRD/" . $kodePlant  . "/" . $bulanGenNo. "/" . $tahunGenNo;
		
		//insert header mutasi
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_MUTASI_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$hdid = $rowHSeq[0];
		
		$query = "INSERT INTO MJ.MJ_T_MUTASI (ID, NO_REQUEST, TIPE, KARYAWAN_ID, DEPT_LAMA_ID, POSISI_LAMA_ID, LOKASI_LAMA_ID, GRADE_LAMA_ID, GAJI_LAMA, DEPT_BARU_ID, POSISI_BARU_ID, LOKASI_BARU_ID, GRADE_BARU_ID, GAJI_BARU, ALASAN, KETERANGAN, TGL_EFFECTIVE, TINGKAT, DIREKSI, STATUS_DOK, STATUS, CREATED_BY, CREATED_DATE, MGR_LAMA, MGR_BARU, STATUS_KARYAWAN, SIFAT_PERUBAHAN, JUMLAH_BULAN) 
		VALUES ($hdid, '$noReq', '$tipe', '$karyawan', '$deptlama', '$posisilama', '$lokasilama', '$gradelama', '$gajilama', '$dept', '$posisi', '$lokasi', '$grade', '$gaji', '$alasan', '$keterangan', TO_DATE('$tanggal', 'YYYY-MM-DD'), '3', $direksi, 'Approved', '$aktif', '$emp_id', SYSDATE, $mgrlama, $mgrbaru, '$statuskar', '$sifatperubahan', $lama)";
		$result = oci_parse($con,$query);
		oci_execute($result);
		
		for ($x=0; $x<$countID; $x++){
			$resultUpload = oci_parse($con,"UPDATE MJ.MJ_TEMP_UPLOAD SET TRANSAKSI_ID = $hdid WHERE TRANSAKSI_KODE = 'MPD' AND FILENAME = '$arrNamaFile[$x]'");
			oci_execute($resultUpload);
		}
		//end insert header MPD
		
		$data="sukses";
	
	}
	
} else {
	//update header MPD
	// $queryUpdate = "UPDATE MJ.MJ_T_MUTASI SET DEPT_BARU_ID='$dept', POSISI_BARU_ID='$posisi', LOKASI_BARU_ID='$lokasi', GRADE_BARU_ID='$grade', GAJI_BARU = '$gaji', ALASAN='$alasan', KETERANGAN='$keterangan', TGL_EFFECTIVE = TO_DATE('$tanggal', 'YYYY-MM-DD'), STATUS='$aktif', STATUS_DOK='In process', TINGKAT='$tingkat', LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE, MGR_LAMA = $mgrlama, MGR_BARU = $mgrbaru , STATUS_KARYAWAN='$statuskar', SIFAT_PERUBAHAN='$sifatperubahan', JUMLAH_BULAN=$lama WHERE ID=$hdid";
	// //echo $queryUpdate;exit;
	// $result = oci_parse($con, $queryUpdate );
	// oci_execute($result);
	//end update header MPD
}

$querydata = "SELECT ID || '|' || NO_REQUEST FROM MJ.MJ_T_MUTASI WHERE ID=$hdid ";
$resultdata = oci_parse($con,$querydata);
oci_execute($resultdata);
$rowdata = oci_fetch_row($resultdata);
$dataNoSIK = $rowdata[0]; 



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
				'results_payroll' => $data_count_payroll
			);
			
			// 'results_non_payroll' => $data_count_non_payroll
			
echo json_encode($result);


?>