<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 include 'fungsiTimecardimport.php';
 
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
$dataPersonId =0;
$dataTgl='';
if(isset($_POST['namaFile']))
{
	$namaFile=$_POST['namaFile']; 
	// echo $namaFile;
	//$namaFile='absen.xls';
	
	error_reporting(E_ALL);
	include 'PHPExcel/IOFactory.php';
	$objReader = new PHPExcel_Reader_Excel5();
	$objPHPExcel = $objReader->load($namaFile);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$countValue = 0;
	$rowStart = -1;
	$rowTgl = 0;
	$columnID = "";
	$columnMasuk = "";
	$columnPulang = "";
	$countRow = count($sheetData);
	// $dataConvertColumn = array("A", "B", "C", "D", "E");
	//echo $countRow;
	for($x=1; $x<=$countRow; $x++){
		$data = $sheetData[$x];
		if($rowStart==-1){
			foreach($data as $data_key => $data_value) {
				if ($data_value == 'Tanggal' && $rowStart==-1){
					$rowStart = $x + 1;
					$rowTgl = $x;
					$columnTgl = $data_key;
				} elseif ($data_value == "PIN" && $columnID==""){
					$columnID = $data_key;
				} elseif ($data_value == "Scan masuk" && $columnMasuk==""){
					$columnMasuk = $data_key;
				} elseif ($data_value == "Scan pulang" && $columnPulang==""){
					$columnPulang = $data_key;
				}
			}
		} else {
			$time = strtotime($data[$columnTgl]);
			$dataTgl = date('Y-m-d',$time);
			$dataId = $data[$columnID];
			$JamMasukAsli = substr($data[$columnMasuk], 0, 5);
			$JamMasuk = str_replace(':', '', $JamMasukAsli);
			$JamKeluarAsli = substr($data[$columnPulang], 0, 5);
			$JamKeluar = str_replace(':', '', $JamKeluarAsli);
			//echo "TGL : ".$dataTgl.", ID : ".$dataId.", JAM_MASUK_ASLI : ".$JamMasukAsli.", JAM_MASUK : ".$JamMasuk.", JAM_KELUAR_ASLI : ".$JamKeluarAsli.", JAM_KELUAR : ".$JamKeluar."<BR>";
			if($dataId != ''){
				$queryID = "SELECT COUNT(-1)
				FROM APPS.PER_PEOPLE_F B 
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON B.PERSON_ID = D.PERSON_ID
				INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=D.LOCATION_ID
				LEFT JOIN MJ.MJ_M_USERAPPROVAL_SPLBETON MMUS ON MMUS.PLANT_ID=D.LOCATION_ID
				WHERE B.HONORS='$dataId'
				AND B.EFFECTIVE_END_DATE > SYSDATE 
				AND D.EFFECTIVE_END_DATE > SYSDATE";
				//echo $queryID;
				$resultID = oci_parse($con,$queryID);
				oci_execute($resultID);
				$rowID = oci_fetch_row($resultID);
				$countID = $rowID[0];
				if($countID >= 1){
					$queryPersonID = "SELECT B.PERSON_ID
					FROM APPS.PER_PEOPLE_F B 
					INNER JOIN APPS.PER_ASSIGNMENTS_F D ON B.PERSON_ID = D.PERSON_ID
					INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=D.LOCATION_ID
					LEFT JOIN MJ.MJ_M_USERAPPROVAL_SPLBETON MMUS ON MMUS.PLANT_ID=D.LOCATION_ID
					WHERE B.HONORS='$dataId'
					AND B.EFFECTIVE_END_DATE > SYSDATE 
					AND D.EFFECTIVE_END_DATE > SYSDATE";
					//echo $queryPersonID;
					$resultPersonID = oci_parse($con,$queryPersonID);
					oci_execute($resultPersonID);
					$rowPersonId = oci_fetch_row($resultPersonID);
					$dataPersonId = $rowPersonId[0];
					
					$queryDelID = "DELETE
					FROM MJ.MJ_T_TIMECARD 
					WHERE PERSON_ID=$dataPersonId
					AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') = '$dataTgl'";
					// //echo $queryDelID;
					$resultDelID = oci_parse($con,$queryDelID);
					oci_execute($resultDelID);
					InsertTimeCard($con, $dataId, $dataTgl, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, $user_id);
					//InsertTimeCard($con, $dataId, $newformat, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, $user_id);
				}
			}
		}
	}
	$data = "sukses";
}	

$result = array('success' => true,
				'results' => '',
				'rows' => $data
			);
echo json_encode($result);
?>