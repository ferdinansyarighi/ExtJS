<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 
// deklarasi variable dan session
session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
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
$JamMasuk=0;
$JamKeluar=0;
$dataId =0;
$dataTgl='';
// if(isset($_POST['namaFile']))
// {
	// $namaFile=$_POST['namaFile']; 
	$namaFile='face in outbarata 21ok-20nov.xls';
	
	error_reporting(E_ALL);
	include 'PHPExcel/IOFactory.php';
	$objReader = new PHPExcel_Reader_Excel5();
	$objPHPExcel = $objReader->load($namaFile);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$countValue = 0;
	$rowStart = 0;
	$rowTgl = 0;
	$rowID = 0;
	$rowNama = 0;
	$valueID = 1;
	$proses = 0;
	$columnID = 0;
	$columnStart = "";
	$countRow = count($sheetData);
	$dataConvertColumn = array("A", "B", "C", "D", "E", "F", "G", "H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY");
	//echo $countRow;
	for($x=1; $x<=$countRow; $x++){
		$countColumn = 0;
		$tempDate = "";
		$data = $sheetData[$x];
		echo json_encode($data);
		foreach($data as $data_key => $data_value) {
			if ($data_value == $valueID && $countColumn == 0){
				$rowID = $x - 1;
				$columnID = $countColumn + 2;
				$rowNama = $x + 1;
				$proses = 1;
			} else if($data_value == 'Tanggal'){
				$columnStart = $countColumn - 1;
				$rowTgl = $x + 2;
			}
			$countColumn++;
		}
		if($proses == 1){
			$dataId = $sheetData[$rowID];
			$dataNama = $sheetData[$rowNama];
			$dataTgl = $sheetData[$rowTgl];
			//echo json_encode($dataTgl);
			echo $columnStart;
			$tempColumn = $columnStart;
			foreach($data as $data_key => $data_value) {
				echo "ID = " . $dataId[$dataConvertColumn[$columnID]] . " Tanggal = " . $dataTgl[$dataConvertColumn[$columnStart]];
				echo "<br>";
				// echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . $Status . ", Selisih=" . $Selisih;
				// echo "<br>";
				$tempColumn = $tempColumn + 2;
			}
		}
		$proses = 0;
		$valueID++;
	}
	

?>