<?PHP
 include 'E:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database

 $vPeriode1='2016-03-21';
 $vPeriode2='2016-03-31';
 $vHariCuti = 0;
 
// $query = "SELECT PERSON_ID, CUTI_TAHUNAN FROM MJ.MJ_M_CUTI WHERE CUTI_TAHUNAN >= 1";
// $result = oci_parse($con, $query);
// oci_execute($result);
// while($row = oci_fetch_row($result))
// {
	// $vPersonId = $row[0];
	// $vCutiTahunan = $row[1];
	
	// $queryJumCuti = "SELECT SUM(CUTI) JumCuti FROM
	// (
		// SELECT COUNT(-1) AS CUTI
		// FROM MJ.MJ_T_TIMECARD MTT
		// INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		// WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		// AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		// AND MME.ELEMENT_NAME IN ('CUTI')
		// AND MTT.PERSON_ID=$vPersonId
		// UNION ALL
		// SELECT COUNT(-1) CUTI 
		// FROM (
			// SELECT DISTINCT MTT.TANGGAL
			// FROM MJ.MJ_T_TIMECARD MTT
			// INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			// INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			// INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
			// AND MTS.KATEGORI='Cuti'
			// AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			// AND MTS.STATUS=1
			// WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			// AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			// AND MME.ELEMENT_NAME IN ('ALPHA')
			// AND MTT.PERSON_ID=$vPersonId
		// )
	// )";
	// $resultJumCuti = oci_parse($con,$queryJumCuti);
	// oci_execute($resultJumCuti);
	// while($rowJumCuti = oci_fetch_row($resultJumCuti))
	// {
		// $vHariCuti = $rowJumCuti[0]; 
	// }
	// if($vHariCuti > $vCutiTahunan){
		// $vCutiTahunan = 0;
	// } else {
		// $vCutiTahunan = $vCutiTahunan - $vHariCuti;
	// }
	
	// $queryUpdate = "UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN = $vCutiTahunan, LAST_UPDATED_BY='schedulerCutiUpdate', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPersonId";
	// $resultUpdate = oci_parse($con,$queryUpdate);
	// oci_execute($resultUpdate);
// } 

$queryUangCuti = "SELECT DISTINCT P.PERSON_ID
, NVL(A.JOB_ID, 0) JOB_ID
, NVL(A.POSITION_ID, 0) POSITION_ID
, NVL(MMC.ID, 0) MJ_M_CUTI_ID
, (NVL(GP.SCREEN_ENTRY_VALUE, 0) / 25) DATA_TRP
, (MMC.CUTI_TAHUNAN * (NVL(GP.SCREEN_ENTRY_VALUE, 0) / 25)) DATA_UANGCUTI
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
AND MMC.CUTI_TAHUNAN >= 1";
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
	
	$queryInsert = "INSERT INTO MJHR.MJ_M_UANG_CUTI (ID, PERSON_ID, JOB_ID, POSITION_ID, MJ_M_CUTI_ID, UANG_TRP, UANG_CUTI, CREATED_BY, CREATED_DATE)
	VALUES (MJHR.MJ_M_UANG_CUTI_SEQ, $vPersonId, $vJobId, $vPositionId, $vCutiId, $vTrp, $vUangCuti, 1, SYSDATE)";
	$resultInsert = oci_parse($con,$queryInsert);
	oci_execute($resultInsert);
}


?>