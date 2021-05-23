<?php
include '../../main/koneksi.php';

$data = "gagal";
$queryfilter = '';
$message = "";
if (isset($_POST['bulan']) || isset($_POST['tahun']))
{	
	$periodegaji = $_POST['periodegaji'];
	$periode_start = $_POST['periode_start'];
	$periode_end = $_POST['periode_end'];
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	$company = $_POST['company'];
	$plant = $_POST['plant'];
	
	if($company != 'All'){
		$queryfilter .= " AND COMPANY_ID = $company";
	}
	if($plant != ''){
		$queryfilter .= " AND PLANT_ID = $plant";
	}
	
		
	if($periodegaji == 'BULANAN'){
		$queryCheckData = "SELECT COUNT(-1)
		FROM MJHR.MJ_M_GAJI_PLANT WHERE REVISI=0 AND BULAN='$bulan' AND TAHUN='$tahun' 
		$queryfilter";
	}else{
		$queryCheckData = "SELECT COUNT(-1)
		FROM MJHR.MJ_M_GAJI_PLANT WHERE REVISI=0 AND PERIODE_START=to_date('$periode_start', 'YYYY-MM-DD') AND PERIODE_END=to_date('$periode_end', 'YYYY-MM-DD') 
		$queryfilter";
	}
	
	// $queryCheckData = "SELECT COUNT(-1)
	// FROM MJHR.MJ_M_GAJI WHERE REVISI=0 AND BULAN='$bulan' AND TAHUN='$tahun' ";
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
	
	if($periodegaji == 'BULANAN'){
		$queryCheckData = "SELECT COUNT(-1)
		FROM MJHR.MJ_M_GAJI_PLANT WHERE REVISI=1 AND BULAN='$bulan' AND TAHUN='$tahun' 
		$queryfilter";
	}else{
		$queryCheckData = "SELECT COUNT(-1)
		FROM MJHR.MJ_M_GAJI_PLANT WHERE REVISI=1 AND PERIODE_START=to_date('$periode_start', 'YYYY-MM-DD') AND PERIODE_END=to_date('$periode_end', 'YYYY-MM-DD') 
		$queryfilter";
	}
	// $queryCheckData = "SELECT COUNT(-1)
	// FROM MJHR.MJ_M_GAJI WHERE REVISI=1 AND BULAN='$bulan' AND TAHUN='$tahun' ";
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