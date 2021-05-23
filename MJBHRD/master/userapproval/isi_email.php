<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_man'])){
	$nama_man=$_POST['nama_man'];

	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$nama_man'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$hasil=$row[0];
	}
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>