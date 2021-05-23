<?PHP
 include 'D:/dataSource/MJBHRDTEST/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRDTEST/transaksi/import/fungsiTimecard.php';
 
 // echo "Masuk";
 
	InsertTimeCard($con, trim('4758'), '2018-02-19', '', '', '', '', 24);
	
?>