<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$total="0";
$totalrp="0";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunSkr=substr($tglskr, 0, 4);
$bulanSkr=substr($tglskr, 5, 2);
$hariSkr=substr($tglskr, 8, 2);

if(isset($_POST['nama_pem'])){
	$nama_pem=$_POST['nama_pem'];
	$bulanSkr = str_replace("0", "", $bulanSkr);

	$query = "SELECT (SELECT NVL(SUM(OUTSTANDING), 0) FROM (
		SELECT (MTP.NOMINAL*MTP.JUMLAH_CICILAN) - NVL((SELECT SUM(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL
        WHERE MJ_T_PINJAMAN_ID=MTP.ID
        AND TAHUN <= '$tahunSkr'
        AND BULAN <= '$bulanSkr'), 0) OUTSTANDING
		FROM MJ_T_PINJAMAN MTP
		WHERE 1=1
		--AND MTP.STATUS_DOKUMEN = 'Approved'
		AND MTP.PERSON_ID=$nama_pem
		ORDER BY MTP.ID
	) A) TOTAL, 
	(SELECT NVL(SUM(OUTSTANDING), 0) FROM (
		SELECT (MTP.NOMINAL*MTP.JUMLAH_CICILAN) - NVL((SELECT SUM(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL
        WHERE MJ_T_PINJAMAN_ID=MTP.ID
        AND TAHUN <= '$tahunSkr'
        AND BULAN <= '$bulanSkr'), 0) OUTSTANDING
		FROM MJ_T_PINJAMAN MTP
		WHERE 1=1
		-- AND MTP.STATUS_DOKUMEN = 'Approved'
		AND ( MTP.STATUS_DOKUMEN = 'Approved' OR MTP.STATUS_DOKUMEN = 'Validate' )
		AND MTP.PERSON_ID=$nama_pem
		AND MTP.TIPE != 'PINJAMAN PENGGANTI INVENTARIS'
		ORDER BY MTP.ID
	) B) TOTAL_APPROVE FROM DUAL";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$total=$row[1];
		$totalrp=number_format($row[0], 2, ',', '.');
	}
	
}

$result = array('success' => true,
			'results' => $total,
			'rows' => $totalrp
		);
echo json_encode($result);

?>