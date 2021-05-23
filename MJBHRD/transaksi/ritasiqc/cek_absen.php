<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="No SJ ";
$hasil = "";
$rangeDate = 0;
$countHasil = 0;
$satuan = "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$arrID=array();
$arrSJ=array();
$arrTglSJ=array();

if(isset($_POST['nama'])){
  	$nama=str_replace("'", "''", $_POST['nama']);
	$tgl_awal=$_POST['tgl_awal'];
	$tgl_akhir=$_POST['tgl_akhir'];
	$arrID=json_decode($_POST['arrID']);
	$arrSJ=json_decode($_POST['arrSJ']);
	$arrTglSJ=json_decode($_POST['arrTglSJ']);
	
	$countID = count($arrID);
	
	for ($x=0; $x<$countID; $x++){
		$noSJ = $arrSJ[$x];
		$tglSJ = $arrTglSJ[$x];
		
		$queryCount = "SELECT COUNT(-1)
		FROM MJ.MJ_T_TIMECARD
		WHERE PERSON_ID=$nama
		AND TO_CHAR(TANGGAL, 'DD-MON-YYYY') = '$tglSJ' ";
		//echo $queryCount;
		$resultCount = oci_parse($con, $queryCount);
		oci_execute($resultCount);
		$rowJum = oci_fetch_row($resultCount);
		$jumlah = $rowJum[0]; 
		
		if($jumlah == 0){
			if ($countHasil == 0){
				$data .= $noSJ;
			} else {
				$data .= ", " . $noSJ;
			}
			
			$countHasil++;
		}
	}
	if($countHasil == 0){
		$data = "sukses";
	} else {
		$data .= " tidak bisa diinput karena tidak ada absensi pada tgl tersebut.";
	}
}

$result = array('success' => true,
			'results' => $countHasil,
			'rows' => $data
		);
echo json_encode($result);

?>