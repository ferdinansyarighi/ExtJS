<?php
include '../../main/koneksi.php';


$nama=""; 
$plant=""; 
$periode=""; 
$vPeriode1="";
$vPeriode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Ymd'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
if (isset($_GET['rekening']) || isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	$rekening = trim($_GET['rekening']);
	$revisi = trim($_GET['revisi']);
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	
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
	}
	
	$queryPeriode = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') PERIODE1 
	, TO_CHAR(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 'YYYY-MM-DD') PERIODE2
	FROM DUAL ";
	//echo $queryJumHari;
	$resultPeriode = oci_parse($con,$queryPeriode);
	oci_execute($resultPeriode);
	$rowPeriode = oci_fetch_row($resultPeriode);
	$vPeriode1 = $rowPeriode[0]; 
	$vPeriode2 = $rowPeriode[1]; 
}

$periode=$tglskr; 

$namaFile ="Report_Gaji_" . $bulan ."_" . $tahun . ".csv";

$dataArray = array();

$countData = 0;
$sumGaji = 0;
$isiKaryawan = ""; 

	$queryGaji = "SELECT MMG.PERSON_ID
	,MMG.TOTAL_DITRANSFER
	,TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) AS NO_REKENING
	,TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AS ATAS_NAMA
	FROM MJ.MJ_M_GAJI MMG
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
	WHERE REVISI=$revisi AND BULAN='$bulan' AND TAHUN='$tahun' AND MMG.TRANS_TUNAI=0";
	//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vPersonId = $row[0];
		$vTotGajiTransfer = $row[1];
		$vNoRek = $row[2];
		$vAtasNama = $row[3];
		
		$vTotGajiTransferRev0 = 0;
		if ($revisi == 1){
			$queryTotRev = "SELECT NVL(MMG.TOTAL_DITRANSFER, 0)
			FROM MJ.MJ_M_GAJI MMG
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			WHERE REVISI=0 AND BULAN='$bulan' AND TAHUN='$tahun' AND MMG.PERSON_ID=$vPersonId";
			//echo $queryJumHari;
			$resultTotRev = oci_parse($con,$queryTotRev);
			oci_execute($resultTotRev);
			$rowTotRev = oci_fetch_row($resultTotRev);
			$vTotGajiTransferRev0 = $rowTotRev[0]; 
		}
		$vTotGajiTransfer =$vTotGajiTransfer - $vTotGajiTransferRev0;
		if($vTotGajiTransfer > 0){
			$sumGaji = $sumGaji + $vTotGajiTransfer;
			$recordDetail = array($vNoRek, $vAtasNama, $rekening, '', '', 'IDR', $vTotGajiTransfer, '', '', 'IBU', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'extended payment detail1');
			$dataArray[$countData] = $recordDetail;
			$countData++;
		}
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