<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['tanggalmasuk'])){
	$tglresign   =$_POST['tanggalresign'];
	$tglmasuk    =$_POST['tanggalmasuk'];
	$masuk		 =substr($tglmasuk, 0, 10);
	$resign		 =substr($tglresign, 0, 10);
	$tglresigns  = new DateTime("$resign");
	$tglmasuks   = new DateTime("$masuk");
	$hasil	     =date_diff($tglmasuks, $tglresigns);
}

$result = array('success' => true,
			    'results' => $hasil->format("%Y Tahun %M Bulan %D Hari"),
			    'rows' => ''
		);
echo json_encode($result);

?>