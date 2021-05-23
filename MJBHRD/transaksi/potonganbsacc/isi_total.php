<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$total="0";
$totalrp="0";

if(isset($_POST['nama_pem'])){
	$nama_pem=$_POST['nama_pem'];

	$query = "SELECT NVL(SUM(OUTSTANDING), 0) FROM (
		SELECT (MTP.NOMINAL*MTP.JUMLAH_CICILAN) - (SELECT SUM(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL
			WHERE MJ_T_PINJAMAN_ID=MTP.ID
			AND TAHUN <= '2016'
			AND BULAN <= '11') OUTSTANDING
		FROM MJ_T_PINJAMAN MTP
		WHERE 1=1
		AND MTP.STATUS_DOKUMEN = 'Validasi'
		AND MTP.PERSON_ID=$nama_pem
		ORDER BY MTP.ID
	) A";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$total=$row[0];
		$totalrp=number_format($row[0], 2, ',', '.');
	}
	
}

$result = array('success' => true,
			'results' => $total,
			'rows' => $totalrp
		);
echo json_encode($result);

?>