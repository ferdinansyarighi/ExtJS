<?php
include '../../main/koneksi.php';
$namaFile = 'Rekap_Ritasi_QC.csv';
$tglskr=date('Ymd'); 
$periode=$tglskr; 
$vPeriode1="";
$vPeriode2="";
$queryfilter = "";
$buatPlant = "";
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$rekening = trim($_GET['rekening']);
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRL.TGL, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRL.TGL, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND TRL.PERSON_ID = '$nama'";
	}
	if($plant!=''){
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '$plant')";
	}
} 
$dataArray = array();

$countData = 0;
$sumGaji = 0;

	$queryExcel = "SELECT PPF.PERSON_ID
	, PPF.FULL_NAME
	, SUM(SUM_VOLUM) VOLUM
	, SUM(TRL.LEMBUR) LEMBUR
	, SUM(SUM_VOLUM * TRL.NOMINAL) NOMINAL
	, (SUM(TRL.LEMBUR) + SUM(SUM_VOLUM * TRL.NOMINAL)) TOTAL 
	, NVL(TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)), '-') AS NO_REKENING
	, TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AS ATAS_NAMA
	FROM MJ.MJ_T_RITASI_LEMBUR TRL
	INNER JOIN MJ.MJ_T_RITASI_QC TRQ ON TRQ.ID = TRL.TRANSAKSI_ID
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRL.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
	WHERE TRL.STATUS = 'Y' AND NVL(TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)), '-') <> '-'
	$queryfilter
	GROUP BY PPF.FULL_NAME, TRL.NOMINAL, PPF.PERSON_ID, PPF.PREVIOUS_LAST_NAME";
	//echo $queryGaji;
	$resultExcel = oci_parse($con,$queryExcel);
	oci_execute($resultExcel);
	while($rowExcel = oci_fetch_row($resultExcel))
	{
		$vNama = $rowExcel[1];
		$vVolum = $rowExcel[2];
		$vLembur = $rowExcel[3];
		$vNominal = $rowExcel[4];
		$vTotal = $rowExcel[5];
		$vNorek = $rowExcel[6];
		$vAtasNama = $rowExcel[7];
		$sumGaji = $sumGaji + $vTotal;
		
		$recordDetail = array($vNorek, $vAtasNama, $rekening, '', '', 'IDR', $vTotal, '', '', 'IBU', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'extended payment detail1');
		$dataArray[$countData] = $recordDetail;
		$countData++;
	}
	
	
$fp = fopen($namaFile, "w");

$recordHeader = array('P', $periode, $rekening, $countData, $sumGaji);
fputcsv($fp, $recordHeader);

for($x = 0; $x < $countData; $x++){
	fputcsv($fp, $dataArray[$x]);
}

fclose($fp);

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $namaFile .'');
readfile($namaFile);

exit();

?>