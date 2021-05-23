<?PHP 
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database

$NAMA='';

	$queryHeader = "SELECT * FROM MastKarya MK 
			WHERE MK.KaryaName like '%LLS%' ";
	$resultHeader = odbc_exec($conn, $queryHeader);
	while(odbc_fetch_row($resultHeader)){
		$NAMA = odbc_result($result, 1);
	}
ECHO $NAMA;EXIT;
?>