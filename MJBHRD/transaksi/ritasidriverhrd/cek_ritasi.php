<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="Pada tanggal ";
$hasil = "";
$rangeDate = 0;
$countHasil = 0;
$satuan = "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$arrRitasi=array();
$arrTglSJ=array();

if(isset($_POST['nama'])){
  	$nama=str_replace("'", "''", $_POST['nama']);
	$arrRitasi=json_decode($_POST['arrRitasi']);
	$arrTglSJ=json_decode($_POST['arrTglSJ']);
	
	$countID = count($arrRitasi);
	
	for ($x=0; $x<$countID; $x++){
		$ritKe = $arrRitasi[$x];
		$tglSJ = $arrTglSJ[$x];
		
		$queryCount = "SELECT COUNT(-1) 
		FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER
		WHERE TO_CHAR(TGL, 'DD-MON-YYYY') = '$tglSJ'
		AND PERSON_ID = $nama
		AND RITASI_KE = '$ritKe'
		AND STATUS = 'Y'";
		//echo $queryCount;
		$resultCount = oci_parse($con, $queryCount);
		oci_execute($resultCount);
		$rowJum = oci_fetch_row($resultCount);
		$jumlah = $rowJum[0]; 
		
		if($jumlah != 0){
			if ($countHasil == 0){
				$data .= $tglSJ;
			} else {
				$data .= ", " . $tglSJ;
			}
			
			$countHasil++;
		}
	}
	if($countHasil == 0){
		$data = "sukses";
	} else {
		$data .= " tidak bisa diinput karena rit ke sudah pernah diinput pada tgl tersebut.";
	}
}

$result = array('success' => true,
			'results' => $countHasil,
			'rows' => $data
		);
echo json_encode($result);

?>