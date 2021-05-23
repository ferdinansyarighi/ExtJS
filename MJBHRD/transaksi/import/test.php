<?PHP
	include 'D:/dataSource/MJBHRD/transaksi/import/fungsiTimecard.php';
	$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
	if (!$conn){
		echo "Connection MSSQL Black failed.";
		exit;
	}
	$upd = "UPDATE CardInOut 
			SET TranDate = '2019-01-25 20:48:22.000'
			WHERE CONVERT(VARCHAR(10), TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, -29, getdate()),120)
				AND TranDate = '2019-01-25 16:48:22.000'
			AND KaryaCode=10339 ";
	$resultupd = odbc_exec($conn, $upd);
	
	$query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode, CONVERT(VARCHAR(10), CIO.TranDate,120), CIO.TranDate
			FROM CardInOut CIO 
			WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, -29, getdate()),120)
			AND CIO.KaryaCode=10339 ";
	$result = odbc_exec($conn, $query);
	while(odbc_fetch_row($result)){
		echo odbc_result($result, 4).'*|*'.odbc_result($result, 5).'</br>';
	}
?>