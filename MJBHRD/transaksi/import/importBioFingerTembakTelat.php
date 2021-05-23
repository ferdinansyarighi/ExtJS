<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/import/fungsiTimecardtelat.php';

 
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
$x = 11;
$TglHeaderTemp = '1990-01-01';

$queryHeader = "SELECT DISTINCT MK.KaryaCode FROM MastKarya MK WHERE MK.KaryaCode=10535 ";
$resultHeader = odbc_exec($conn, $queryHeader);
while(odbc_fetch_row($resultHeader)){
	$dataId = odbc_result($resultHeader, 1);
	
	$JamMasukAsli = '';
	// $query = "select CONVERT(VARCHAR(10), TranDate,108), CONVERT(VARCHAR(10), TranDate,120) from dbo.CardInOut 
	// where CONVERT(VARCHAR(10), TranDate,120) >= '2017-04-21'
	// AND CONVERT(VARCHAR(10), TranDate,120) <= '2017-05-20'
	// AND KaryaCode=$dataId
	// AND CONVERT(VARCHAR(10), TranDate,108) >= '08:06:00'
	// AND StateCode = 0 
	// ORDER BY CONVERT(VARCHAR(10), TranDate,108)";
	$query = "select jam, tanggal from (
		select MIN(CONVERT(VARCHAR(10), TranDate,108)) jam, CONVERT(VARCHAR(10), TranDate,120) tanggal
		from dbo.CardInOut 
		where CONVERT(VARCHAR(10), TranDate,120) >= '2017-04-21'
		AND CONVERT(VARCHAR(10), TranDate,120) <= '2017-05-20'
		AND KaryaCode=$dataId
		AND StateCode = 0
		GROUP BY CONVERT(VARCHAR(10), TranDate,120) 
	) x
	where jam >= '08:06:00' ";
	$result = odbc_exec($conn, $query);
	while(odbc_fetch_row($result)){
		$Tgl = odbc_result($result, 1);
		$TglHeader = odbc_result($result, 2);
		// echo $Tgl . " dan " . $TglHeader;
		if($TglHeader != $TglHeaderTemp){
			$TglHeaderTemp = $TglHeader;
			$JamMasukAsli = substr($Tgl, 0, 5);
			// echo $JamMasukAsli;
			InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli);
		}
	}
}
?>