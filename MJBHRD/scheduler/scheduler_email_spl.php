<?PHP
 include 'E:/dataSource/MJBHRD/main/koneksi2.php'; //Koneksi ke database
 include 'E:/dataSource/MJBHRD/scheduler/fungsiAutoemailSPL.php';

//========================================================================================Generate Excel===============================================================================
// Function penanda awal file (Begin Of File) Excel

function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

// Function penanda akhir file (End Of File) Excel

function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}

// Function untuk menulis data (angka) ke cell excel

function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

// Function untuk menulis data (text) ke cell excel

function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

$count = 0;
 
$query = "SELECT COUNT(-1) 
FROM MJ.MJ_T_SPL MTS
INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTSD.TINGKAT AND MMUP.APP_ID=1
AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
FROM MJ.MJ_M_AREA MMA
INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
WHERE 1=1 AND MTSD.STATUS_DOK='In process'";
$result = oci_parse($con, $query);
oci_execute($result);
$row = oci_fetch_row($result);
$countInProcess=$row[0];
//echo $countInProcess;
if ($countInProcess >=1){
	$query = "SELECT DISTINCT
	CASE MTSD.TINGKAT WHEN 0 THEN NVL(MTS.SPV, '-') WHEN 1 THEN NVL(MTS.MANAGER, '-') ELSE NVL(MMUP.FULL_NAME, '-') END AS DATA_PIC_APPROVED 
	FROM MJ.MJ_T_SPL MTS
	INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
	LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTSD.TINGKAT AND MMUP.APP_ID=1
	AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
	FROM MJ.MJ_M_AREA MMA
	INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
	INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
	WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
	WHERE 1=1 AND MTSD.STATUS_DOK='In process' 
	";
	$result = oci_parse($con,$query);
	oci_execute($result);

	while($row = oci_fetch_row($result))
	{
		$dataName = $row[0];
		//echo $dataName;	
		if($dataName!='-'){
			KirimEmailSPL($con, trim($dataName));
		}
	}
}		
?>