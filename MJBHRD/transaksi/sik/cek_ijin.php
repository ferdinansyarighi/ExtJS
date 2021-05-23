<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_ijin'])){
	$nama_ijin=$_POST['nama_ijin'];

	if($nama_ijin!=''){
		$query = "SELECT MMI.JUMLAH_HARI 
		FROM MJ.MJ_M_IJIN MMI
		WHERE MMI.STATUS='A' AND MMI.JENIS_IJIN LIKE '$nama_ijin'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$hasil=$row[0];
		}
	} else {
		$hasil=0;
	}	
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>