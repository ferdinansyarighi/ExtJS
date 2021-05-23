<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi2.php'; //Koneksi ke database

// $vPeriode1='2016-03-21';
// $vPeriode2='2016-03-31';
$vHariCuti = 0;
$vTransaksi = 0;
 
$queryPeriode = "SELECT TO_CHAR(SYSDATE, 'YYYY') || '-' || TO_CHAR(TANGGAL_REFRESH - 1, 'MM') || '-21' Periode1
, TO_CHAR(SYSDATE, 'YYYY') || '-' || TO_CHAR(TANGGAL_REFRESH - 1, 'MM-DD') Periode2
FROM MJ.MJ_PM_CUTI 
WHERE JENIS_CUTI='TAHUNAN'";
//echo $queryCount;
$resultPeriode = oci_parse($con, $queryPeriode);
oci_execute($resultPeriode);
while($rowPeriode = oci_fetch_row($resultPeriode))
{
	$vPeriode1=$rowPeriode[0];
	$vPeriode2=$rowPeriode[1];
}
//echo $vPeriode1 . ' dan ' . $vPeriode2;

$queryCount = "SELECT CASE WHEN TO_CHAR(TANGGAL_REFRESH, 'MM-DD') = TO_CHAR(SYSDATE, 'MM-DD') THEN 1 ELSE 0 END 
FROM MJ.MJ_PM_CUTI 
WHERE JENIS_CUTI='TAHUNAN'";
//echo $queryCount;
$resultCount = oci_parse($con, $queryCount);
oci_execute($resultCount);
while($rowCount = oci_fetch_row($resultCount))
{
	$vTransaksi=$rowCount[0];
}
//echo $vTransaksi;
if ($vTransaksi >= 1){
	$queryCutiTahunan = "SELECT PERSON_ID, CUTI_TAHUNAN FROM MJ.MJ_M_CUTI WHERE CUTI_TAHUNAN >= 1";
	$resultCutiTahunan = oci_parse($con, $queryCutiTahunan);
	oci_execute($resultCutiTahunan);
	while($rowCutiTahunan = oci_fetch_row($resultCutiTahunan))
	{
		$vPersonId = $rowCutiTahunan[0];
		$vCutiTahunan = $rowCutiTahunan[1];
		
		$queryJumCuti = "SELECT COUNT(-1) JumCuti FROM
		(
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('CUTI')
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
			AND MTS.KATEGORI='Cuti'
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA')
			AND MTT.PERSON_ID=$vPersonId
		)";
		$resultJumCuti = oci_parse($con,$queryJumCuti);
		oci_execute($resultJumCuti);
		while($rowJumCuti = oci_fetch_row($resultJumCuti))
		{
			$vHariCuti = $rowJumCuti[0]; 
		}
		if($vHariCuti > $vCutiTahunan){
			$vCutiTahunan = 0;
		} else {
			$vCutiTahunan = $vCutiTahunan - $vHariCuti;
		}
		// echo $vCutiTahunan;
		$queryUpdate = "UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN = $vCutiTahunan, LAST_UPDATED_BY='schedulerMasterCuti', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPersonId";
		$resultUpdate = oci_parse($con,$queryUpdate);
		oci_execute($resultUpdate);
	} 

	$queryUangCuti = "SELECT DISTINCT P.PERSON_ID
	, NVL(A.JOB_ID, 0) JOB_ID
	, NVL(A.POSITION_ID, 0) POSITION_ID
	, NVL(MMC.ID, 0) MJ_M_CUTI_ID
	, (NVL(GP.SCREEN_ENTRY_VALUE, 0) / 25) DATA_TRP
	, (CASE WHEN MMC.CUTI_TAHUNAN < 0 THEN 0 ELSE MMC.CUTI_TAHUNAN END * (NVL(GP.SCREEN_ENTRY_VALUE, 0) / 25)) DATA_UANGCUTI
	, TO_CHAR(SYSDATE, 'YYYY') TAHUN
	FROM APPS.PER_PEOPLE_F P
	INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F A ON P.PERSON_ID=A.PERSON_ID 
	INNER JOIN MJ.MJ_M_CUTI MMC ON MMC.PERSON_ID=P.PERSON_ID
	INNER JOIN 
	(SELECT  PEEF.ASSIGNMENT_ID
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
	ORDER BY PEEF.ASSIGNMENT_ID) GP ON GP.ASSIGNMENT_ID=A.ASSIGNMENT_ID AND NVL(GP.SCREEN_ENTRY_VALUE,0)<>0
	WHERE P.EFFECTIVE_END_DATE > SYSDATE 
	AND A.EFFECTIVE_END_DATE > SYSDATE
	AND A.PAYROLL_ID IS NOT NULL
	AND A.PRIMARY_FLAG='Y'
	--AND MMC.CUTI_TAHUNAN >= 1";
	$resultUangCuti = oci_parse($con, $queryUangCuti);
	oci_execute($resultUangCuti);
	while($rowUangCuti = oci_fetch_row($resultUangCuti))
	{
		$vPersonId = $rowUangCuti[0];
		$vJobId = $rowUangCuti[1];
		$vPositionId = $rowUangCuti[2];
		$vCutiId = $rowUangCuti[3];
		$vTrp = $rowUangCuti[4];
		$vUangCuti = $rowUangCuti[5];
		$vTahun = $rowUangCuti[6];
		
		$queryInsert = "INSERT INTO MJHR.MJ_M_UANG_CUTI (ID, TAHUN, PERSON_ID, JOB_ID, POSITION_ID, MJ_M_CUTI_ID, UANG_TRP, UANG_CUTI, CREATED_BY, CREATED_DATE)
		VALUES (MJHR.MJ_M_UANG_CUTI_SEQ.NEXTVAL, '$vTahun', $vPersonId, $vJobId, $vPositionId, $vCutiId, $vTrp, $vUangCuti, 1, SYSDATE)";
		//echo $queryInsert;
		$resultInsert = oci_parse($conHR,$queryInsert);
		oci_execute($resultInsert);
	}
}

$tglskr=date('Y-m-d'); 
// $tglskr='2017-06-08';
$bulan=substr($tglskr, 5, 2);

$resultSeq = oci_parse($con,"SELECT TO_CHAR(TANGGAL_REFRESH, 'YYYY-MM-DD') FROM MJ.MJ_PM_CUTI WHERE JENIS_CUTI='TAHUNAN'");
oci_execute($resultSeq);
$row = oci_fetch_row($resultSeq);
$tglCutiTahunan = $row[0];
$subtglCutiTahunan = substr($tglCutiTahunan, 5);
//echo $subtglCutiTahunan;

$resultSeq = oci_parse($con,"SELECT TO_CHAR(TANGGAL_REFRESH, 'YYYY-MM-DD') FROM MJ.MJ_PM_CUTI WHERE JENIS_CUTI='SAKIT'");
oci_execute($resultSeq);
$row = oci_fetch_row($resultSeq);
$tglCutiSakit = $row[0];
$subtglCutiSakit = substr($tglCutiSakit, 5);

$query = "SELECT PERSON_ID, TO_CHAR(ORIGINAL_DATE_OF_HIRE + 90, 'YYYY-MM-DD'), TO_CHAR(ADD_MONTHS(ORIGINAL_DATE_OF_HIRE, 12), 'YYYY-MM-DD')
FROM APPS.PER_PEOPLE_F 
WHERE EFFECTIVE_END_DATE > SYSDATE
AND CURRENT_EMPLOYEE_FLAG = 'Y'
--AND PERSON_ID = 15972 ";
// echo $query;
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$vPersonId = $row[0];
	$vEfDateTahunan = $row[2];
	$vsubEfDateTahunan = substr($vEfDateTahunan, 5);
	$vsubEfDateTahunan2 = substr($vEfDateTahunan, 0, 4);
	$vsubEfDateTahunan3 = substr($vEfDateTahunan, 5, 2);
	$vsubTglSkr = substr($tglskr, 5);
	$vsubTglSkr2 = substr($tglskr, 0, 4);
	$vsubTglSkr3 = substr($tglskr, 5, 2);
	//echo $vsubTglSkr;
	
	$resultCount = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$vCount = $rowCount[0];
	echo " Count : ". $vCount;
	//Masuk ketika data sudah pernah ada di master cuti
	if($vCount > 0){
		//echo ", Tgl Skr : " . $tglskr . ", Tgl Masuk : " . $vEfDateTahunan;
		//Masuk ketika sudah 1 tahun dari effective start date
		if($tglskr >= $vEfDateTahunan){
			//echo ", Tahun Masuk : " . $vsubEfDateTahunan2 . ", Tahun Skr : " . $vsubTglSkr2;
			//Masuk ketika pas 1 tahun dari effective start date
			if(($vsubEfDateTahunan2 - $vsubTglSkr2) == 0){
				//echo ", Hari Bulan Skr : " . $vsubTglSkr . ", Hari Bulan Masuk : " . $vsubEfDateTahunan;
				//Masuk ketika hari dan bulan masuk sama dengan hari dan bulan hari ini
				if($vsubTglSkr == $vsubEfDateTahunan){
					//echo ", Bulan Masuk : " . $vsubEfDateTahunan3;
					//Masuk ketika bulan masuk sama dengan bulan 1, 2, dan 3
					if($vsubEfDateTahunan3 == '01' || $vsubEfDateTahunan3 == '02' || $vsubEfDateTahunan3 == '03'){
						$vsubTglSkr2Sebelum = $vsubTglSkr2 - 1;
						$resultSeq = oci_parse($con,"SELECT DISTINCT B.DESCRIPTION
						FROM APPS.HXT_HOLIDAY_DAYS A
						, APPS.HXT_HOLIDAY_CALENDARS B
						WHERE A.HCL_ID=B.ID 
						AND B.EFFECTIVE_END_DATE>SYSDATE 
						AND TO_CHAR(B.EFFECTIVE_START_DATE, 'YYYY') = '$vsubTglSkr2Sebelum'
						AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY'");
						oci_execute($resultSeq);
						$row = oci_fetch_row($resultSeq);
						$jumlahCuti = 12 - $row[0];
						
						$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN=$jumlahCuti, LAST_UPDATED_BY='schedulerMasterCuti', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
						oci_execute($resultCuti);
					} else {
						$resultSeq = oci_parse($con,"SELECT DISTINCT B.DESCRIPTION
						FROM APPS.HXT_HOLIDAY_DAYS A
						, APPS.HXT_HOLIDAY_CALENDARS B
						WHERE A.HCL_ID=B.ID 
						AND B.EFFECTIVE_END_DATE>SYSDATE 
						AND TO_CHAR(B.EFFECTIVE_START_DATE, 'YYYY') = '$vsubTglSkr2'
						AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY'");
						oci_execute($resultSeq);
						$row = oci_fetch_row($resultSeq);
						$jumlahCuti = 12 - $row[0];
						
						$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN=$jumlahCuti, LAST_UPDATED_BY='schedulerMasterCuti', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
						oci_execute($resultCuti);
					}
				}
			} 
			//echo ", Tahun Masuk : " . $vsubEfDateTahunan2 . ", Tahun Skr : " . $vsubTglSkr2;
			//Masuk ketika > 1 tahun dari effective start date untuk refresh cuti
			elseif(($vsubTglSkr2 - $vsubEfDateTahunan2) >= 1) {
				// echo ", Hari bulan skr : " . $vsubTglSkr . ", Hari bulan refresh : " . $subtglCutiTahunan;
				if($vsubTglSkr == $subtglCutiTahunan){
					$resultSeq = oci_parse($con,"SELECT DISTINCT B.DESCRIPTION
					FROM APPS.HXT_HOLIDAY_DAYS A
					, APPS.HXT_HOLIDAY_CALENDARS B
					WHERE A.HCL_ID=B.ID 
					AND B.EFFECTIVE_END_DATE>SYSDATE 
					AND TO_CHAR(B.EFFECTIVE_START_DATE, 'YYYY') = '$vsubTglSkr2'
					AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY'");
					oci_execute($resultSeq);
					$row = oci_fetch_row($resultSeq);
					$jumlahCuti = 12 - $row[0];
						
					$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN=$jumlahCuti, LAST_UPDATED_BY='schedulerMasterCuti', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
					oci_execute($resultCuti);
				}
			}
		}
	} else {
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_CUTI_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$rowSeq = oci_fetch_row($resultSeq);
		$seq = $rowSeq[0];
		
		$queryCuti = "INSERT INTO MJ.MJ_M_CUTI (ID, APP_ID, PERSON_ID, CUTI_TAHUNAN, CUTI_SAKIT, BULAN) VALUES ($seq, " . APPCODE . ", $vPersonId, 0, 0, 0)";
		$resultCuti = oci_parse($con,$queryCuti);
		oci_execute($resultCuti); 
	}
} 
?>