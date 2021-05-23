<?php
error_reporting(E_ALL);
include 'PHPExcel/IOFactory.php';
$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = $objReader->load('excel finger manyar.xls');
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//var_dump($sheetData);
//$data = $sheetData[1];
//$data1 = $data[1];
//$tes = $data1[0];
$countValue = 0;
$rowStart = -1;
$rowTgl = 0;
$columnID = "";
$columnName = "";
$countRow = 9;//count($sheetData);
$dataConvertColumn = array("A", "B", "C", "D", "E");
//echo $countRow;
for($x=1; $x<=$countRow; $x++){
	$countColumn = 0;
	$tempDate = "";
	$data = $sheetData[$x];
	//$countColumn = count($data);
	foreach($data as $data_key => $data_value) {
		// echo "Key=" . $data_key . ", Value=" . $data_value;
		// echo "<br>";
		if ($data_value == 'No.' && $rowStart==-1){
			$rowStart = $x + 2;
			$rowTgl = $x;
			$columnStart = $countColumn + 4;
			//echo $rowStart;
		} elseif ($data_value == "Fingerprint ID" && $columnID==""){
			$columnID = $data_key;
		} elseif ($data_value == "Employee Name" && $columnName==""){
			$columnName = $data_key;
		}
		$countColumn++;
	}
	if($x >= $rowStart && $rowStart!=-1){
		//echo $columnStart;
		$dataTgl = $sheetData[$rowTgl];
		foreach($data as $data_key => $data_value) {
			//echo "ID : " . $data[$columnID] . " Nama : " . $data[$columnName] . " In : " . ;
			$time = strtotime($dataTgl[$data_key]);
			$newformat = date('Y-m-d',$time);
			if ($newformat != '1970-01-01'){
				$tempDate = $newformat;
			} else {
				$newformat = $tempDate;
			}
			echo "Key=" . $data_key . ", Value=" . $data_value . ", Tanggal=" . $newformat . ", DataTGL : " . $dataTgl[$data_key];
			echo "<br>";
		}
		// echo "ID : " . $data[$columnID] . " Nama : " . $data[$columnName] . " Tanggal : " . $dataTgl[$dataConvertColumn[$columnStart]] . " In : " . $data[$dataConvertColumn[$columnStart]];
		// echo "<br>";
	}
	//echo $dataConvertColumn[0];
}

?>