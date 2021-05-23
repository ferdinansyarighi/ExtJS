<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/import/fungsiTimecardFS.php';

 
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


	$queryHeader = "SELECT MMFU.PIN, TO_CHAR(SYSDATE - 3, 'YYYY-MM-DD') FROM MJ.MJ_M_FS_USER MMFU";
	$resultHeader = oci_parse($con, $queryHeader);
	oci_execute($resultHeader););
	while($rowHeader = oci_fetch_row($resultHeader)){
		$dataId = $rowHeader[0];
		$TglHeader = $rowHeader[1];
		$JamMasukAsli = '';
		$JamKeluarAsli = '';
		$countIn = 0;
		$query = "SELECT MMFS.PIN, TO_CHAR(MMFS.SCAN_DATE, 'HH24:MI')
		FROM MJ_M_FS_SCANLOG MMFS 
		WHERE TO_CHAR(MMFS.SCAN_DATE, 'YYYY-MM-DD')=TO_CHAR(SYSDATE - 3, 'YYYY-MM-DD') 
		AND MMFS.PIN=$dataId
		ORDER BY SCAN_DATE ";
		$result = oci_parse($con, $query);
		while($row = oci_fetch_row($result)){
			if($countIn == 0){
				$JamMasukAsli = $row[1];
				$JamMasuk = str_replace(':', '', $JamMasukAsli);
				$countIn++;
			} else {
				$JamKeluarAsli = $row[1];
				$JamKeluar = str_replace(':', '', $JamKeluarAsli);
			}
		}
		//echo " FingerID : " . $dataId . " Tgl : " . $TglHeader . " Jam In : " . $JamMasukAsli . " Jam Out : " . $JamKeluarAsli . " Status : " . $Status;
		// echo "<br>";
		InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24);
	}
?>