<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/import/fungsiTimecard.php';

 
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
$pengurang = 5;
$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
if (!$conn){
	echo "Connection MSSQL Black failed.";
	exit;
}
$queryCronAbsen = "UPDATE MJ.MJ_SYS_CHECK_CRON SET CRON_STATUS = 1, LAST_TIME = SYSDATE WHERE CRON_NAME  = 'load_import_absensi_ho'";
$resultCronAbsen = oci_parse($con,$queryCronAbsen);
oci_execute($resultCronAbsen);

$queryDate = "SELECT SYSDATE - $pengurang FROM DUAL";
$resultDate = oci_parse($con,$queryDate);
oci_execute($resultDate);
$rowDate = oci_fetch_row($resultDate);
$vSdate = $rowDate[0];

	$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -$pengurang, getdate()),120)
	FROM MastKarya MK WHERE MK.KaryaCode >= 0 AND MK.KaryaCode <= 55000";
	$resultHeader = odbc_exec($conn, $queryHeader);

	while(odbc_fetch_row($resultHeader)){
		$dataId = odbc_result($resultHeader, 1);
		$TglHeader = substr(odbc_result($resultHeader, 2), 0, 10);
		$JamMasukAsli = '';
		$JamKeluarAsli = '';
		$countIn = 0;

		$cekShiftGanti = "SELECT COUNT(-1)
						  FROM PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF,MJ.MJ_M_CHANGE_SHIFT MCS
						  WHERE PPF.HONORS = '$dataId'
						  AND PPF.PERSON_ID = PAF.PERSON_ID 
						  AND PAF.ASSIGNMENT_ID = MCS.ASSIGNMENT_ID 
						  AND MCS.DATE_DETAIL = TO_DATE('$vSdate')";
		$resultCekGanti = oci_parse($con,$cekShiftGanti);
		oci_execute($resultCekGanti);
		$rowCekGanti = oci_fetch_row($resultCekGanti);
		$CekGanti = $rowCekGanti[0];

		if($CekGanti == 0)
		{ 
			$query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
			FROM CardInOut CIO 
			WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, -$pengurang, getdate()),120)
			AND CIO.KaryaCode=$dataId ";
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
				}
			}
			InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24, $pengurang);
		}
		else {
			$cekShift = " SELECT COUNT(-1)
						  FROM PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF,MJ.MJ_M_SHIFT MMS
						  WHERE PPF.HONORS = '$dataId'
						  AND PPF.PERSON_ID = PAF.PERSON_ID 
						  AND PAF.ASSIGNMENT_ID = MMS.ASSIGNMENT_ID 
						  AND MMS.DATE_FROM <= TO_DATE('$vSdate') AND MMS.DATE_TO >= TO_DATE('$vSdate')";
			$resultCekShift = oci_parse($con,$cekShift);
			oci_execute($resultCekShift);
			$rowCekShift = oci_fetch_row($resultCekShift);
			$cekShift = $rowCekShift[0];
			if($cekShift == 0)
			{ 
				$query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
				FROM CardInOut CIO 
				WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, -$pengurang, getdate()),120)
				AND CIO.KaryaCode=$dataId ";
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
					}
				}
					InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24, $pengurang);
			}
		}
		// if($JamMasukAsli == ''){
			// $JamMasukAsli = '00:00:00';
		// }
		// if($JamKeluarAsli == ''){
			// $JamKeluarAsli = '00:00:00';
		// }
		//echo " FingerID : " . $dataId . " Tgl : " . $TglHeader . " Jam In : " . $JamMasukAsli . " Jam Out : " . $JamKeluarAsli . " Status : " . $Status;
		// echo "<br>";
		
	}
$queryCronAbsenSelesai = "UPDATE MJ.MJ_SYS_CHECK_CRON SET CRON_STATUS = 0, LAST_TIME = SYSDATE WHERE CRON_NAME  = 'load_import_absensi_ho'";
$resultCronAbsenSelesai = oci_parse($con,$queryCronAbsenSelesai);
oci_execute($resultCronAbsenSelesai);

?>