<?php
include '../../main/koneksi.php';

$data = "gagal";
if (isset($_POST['bulan']) || isset($_POST['tahun']))
{	
	$revisi = trim($_POST['revisi']);
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	
	$queryCheckData = "SELECT COUNT(-1)
	FROM MJHR.MJ_M_GAJI WHERE REVISI=$revisi AND BULAN='$bulan' AND TAHUN='$tahun' ";
	//echo $queryJumHari;
	$resultCheckData = oci_parse($conHR,$queryCheckData);
	oci_execute($resultCheckData);
	$rowCheckData = oci_fetch_row($resultCheckData);
	$vCheckData = $rowCheckData[0]; 
	if($vCheckData >= 1){
		$data='sukses';
	} else {
		$data='gagal';
	}
}

$result = array('success' => true,
				'results' => $vCheckData,
				'rows' => $data
			);
echo json_encode($result);

?>