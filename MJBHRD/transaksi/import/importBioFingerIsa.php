<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/import/fungsiTimecardIsa.php';

 
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$namaFile=""; 
$data="gagal";
$data_value = '';
$HariLibur=0;
$HariIni='';
$Status='';
$DataTgl='';
$JamMasuk='';
$JamKeluar='';
$dataId =0;
$dataTgl='';
$selisihhari = 3;

$queryDate = "SELECT TO_CHAR(TRUNC(SYSDATE)-".$selisihhari.", 'YYYY-MM-DD') FROM DUAL";
$resultDate = oci_parse($con,$queryDate);
oci_execute($resultDate);
$rowDate = oci_fetch_row($resultDate);
$vSdate = $rowDate[0];

	/*
	$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
	FROM MastKarya MK --WHERE MK.KaryaCode=10392 ";
	$resultHeader = odbc_exec($conn, $queryHeader);
	*/
	$qryKaryawan = "
		SELECT PPF.PERSON_ID, PAF.ASSIGNMENT_ID, PPF.FULL_NAME, PPF.HONORS, NVL(MMCS.SHIFT_ID, MMS.SHIFT_ID) SHIFT_ID 
		FROM APPS.PER_PEOPLE_F PPF
			INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
			INNER JOIN MJ.MJ_M_LINK_GROUP MMLG ON PAF.PERSON_ID = MMLG.PERSON_ID AND PAF.ASSIGNMENT_ID = MMLG.ASSIGNMENT_ID
			INNER JOIN MJ.MJ_M_SHIFT MMS ON PAF.ASSIGNMENT_ID = MMS.ASSIGNMENT_ID AND TRUNC(SYSDATE)-".$selisihhari." BETWEEN MMS.DATE_FROM AND MMS.DATE_TO
			LEFT JOIN MJ.MJ_M_CHANGE_SHIFT MMCS ON MMS.ASSIGNMENT_ID = MMCS.ASSIGNMENT_ID AND MMCS.DATE_DETAIL = TRUNC(SYSDATE)-".$selisihhari."
		WHERE TRUNC(SYSDATE)-".$selisihhari." BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.HONORS IS NOT NULL
			AND TRUNC(SYSDATE)-".$selisihhari." BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE
			AND MMLG.PERIODE_GAJI = 'MINGGUAN'
			AND PAF.ORGANIZATION_ID = 81 AND PAF.LOCATION_ID = 6317 AND MMLG.ID_GROUP =7 AND PPF.PERSON_ID = 3596
	";
	$resultKaryawan = oci_parse($con,$qryKaryawan);
	oci_execute($resultKaryawan);
	while($rowKaryawan = oci_fetch_row($resultKaryawan)){
		//print('<pre/>'); print_r($rowKaryawan); exit;		
		$JamMasukAsli = '';
		$JamKeluarAsli = '';
		$countIn = 0;
		$countOut = 0;
		
		//Query detail presensi IN BioFinger
		$query = "
			SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
			FROM CardInOut CIO 
			WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) = '".$vSdate."'
				AND CIO.KaryaCode=$dataId
			ORDER BY CIO.TranDate, CIO.StateCode
		";
		$result = odbc_exec($conn, $query);
		while(odbc_fetch_row($result)){
			$Tgl = odbc_result($result, 2);
			$Status = odbc_result($result, 3);

			if($Status == 0 && $countIn == 0){
				$JamMasukAsli = substr($Tgl, 11, 5);
				$JamMasuk = str_replace(':', '', $JamMasukAsli);
				$countIn++;
			}
			if($Status == 1){
				$JamKeluarAsli = substr($Tgl, 11, 5);
				$JamKeluar = str_replace(':', '', $JamKeluarAsli);
				$countOut++;
			}
		}
		
		
		InsertTimeCard($con, trim($dataId), $vSdate, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24);
	}
?>