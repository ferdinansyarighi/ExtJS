<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$rangeDate = 0;
$countHasil = 0;
$satuan = "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 

if(isset($_POST['file'])){
	$file=$_POST['file'];
	copy($file, 'a.xls');
}

$result = array('success' => true,
			'results' => $countHasil,
			'rows' => ''
		);
echo json_encode($result);

?>