<?PHP
	$namaFile='intan3.xls'; 
	//$namaFile='excel finger manyar.xls';
	
	error_reporting(E_ALL);
	include 'PHPExcel/IOFactory.php';
	$objReader = new PHPExcel_Reader_Excel5();
	$objPHPExcel = $objReader->load($namaFile);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$countValue = 0;
	$rowkiri = 9;
	$rowkanan = 40;
	$rowID = 0;
	$columnID = "";
	$columnName = "";
	$countRow = count($sheetData);
	$dataConvertColumn = array("A", "B", "C", "D", "E");
	//echo $countRow;
	for($x=1; $x<=50; $x++){
		$dataKiri = $sheetData[$rowkiri];
		$dataKanan = $sheetData[$rowkanan];
		// echo $x;
		// echo "<br>";
		echo "kiri=" . $dataKiri['A'] . ", kanan=" . $dataKanan['A'];
		echo "<br>";
		// foreach($data as $data_key => $data_value) {
			// If($data_key == 'A'){
				// echo $x;
				// echo "<br>";
				// echo "Key=" . $data_key . ", Value=" . $data_value;
				// echo "<br>";
			// }
		// }
		$rowkiri = $rowkiri + 41;
		$rowkanan = $rowkanan + 41;
	}
	
?>