<?php
include '../../main/koneksi.php';

$data = "gagal";
$message = "sukses";
if (isset($_POST['bulan']) || isset($_POST['tahun']))
{	
	$revisi = trim($_POST['revisi']);
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	
	$queryCheckData = "SELECT COUNT(-1)
	FROM MJHR.MJ_M_GAJI WHERE REVISI=0 AND BULAN='$bulan' AND TAHUN='$tahun' ";
	//echo $queryJumHari;
	$resultCheckData = oci_parse($conHR,$queryCheckData);
	oci_execute($resultCheckData);
	$rowCheckData = oci_fetch_row($resultCheckData);
	$vCheckData = $rowCheckData[0]; 
	if($vCheckData >= 1){
		$data='sukses';
	} else {
		$data='gagal';
		$message = 'Data revisi 0 belum ada.';
	}
	$queryCheckData = "SELECT COUNT(-1)
	FROM MJHR.MJ_M_GAJI WHERE REVISI=1 AND BULAN='$bulan' AND TAHUN='$tahun' ";
	//echo $queryJumHari;
	$resultCheckData = oci_parse($conHR,$queryCheckData);
	oci_execute($resultCheckData);
	$rowCheckData = oci_fetch_row($resultCheckData);
	$vCheckData = $rowCheckData[0]; 
	if($vCheckData >= 1){
		$data='sukses';
	} else {
		$data='gagal';
		$message .= ' Data revisi 1 belum ada.';
	}
}

$result = array('success' => true,
				'results' => $message,
				'rows' => $data
			);
echo json_encode($result);

?>