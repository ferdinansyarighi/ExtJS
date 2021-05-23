<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_pem'])){
	$nama_pem=str_replace("'", "''", $_POST['nama_pem']);

	$query = "SELECT PG.NAME FROM PER_GRADES PG, PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF
	WHERE PPF.PERSON_ID = PAF.PERSON_ID AND PAF.GRADE_ID = PG.GRADE_ID AND PPF.FULL_NAME LIKE '$nama_pem'";
	//echo $query;exit;
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