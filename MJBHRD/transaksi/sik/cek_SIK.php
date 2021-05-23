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

if(isset($_POST['tglFrom'])){
  	$pemohon=str_replace("'", "''", $_POST['pemohon']);
	$tglFrom=$_POST['tglFrom'];
	$typeform=$_POST['typeform'];
	$kategoriForm=$_POST['kategoriForm'];
	$hari=substr($tglFrom, 0, 2);
	$bulan=substr($tglFrom, 3, 2);
	$tahun=substr($tglFrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglFrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglTo=$_POST['tglTo'];
	$hari=substr($tglTo, 0, 2);
	$bulan=substr($tglTo, 3, 2);
	$tahun=substr($tglTo, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglTo = $tahun . "-" . $bulan . "-" . $hari;

	$query = "SELECT COUNT(-1) 
	FROM (
		SELECT LEVEL AS DNUM FROM DUAL
		CONNECT BY (TO_DATE('$tglTo', 'YYYY-MM-DD') - TO_DATE('$tglFrom', 'YYYY-MM-DD')) - LEVEL >= 0
	)";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$rangeDate=$row[0];
		
	}
	//echo $countHasil;
	if ($typeform == 'tambah'){
		if($kategoriForm == 'Terlambat'){
			for ($x = 0; $x < $rangeDate; $x++){
				$queryCount = "SELECT *
				FROM MJ.MJ_T_SIK
				WHERE PEMOHON = '$pemohon'
				AND TO_DATE('$tglFrom', 'YYYY-MM-DD')+$x BETWEEN TANGGAL_FROM AND TANGGAL_TO
				AND STATUS=1 AND STATUS_DOK IN ('Approved', 'In process')
				AND KATEGORI = 'Terlambat'";
				//echo $queryCount;
				$resultCount = oci_parse($con, $queryCount);
				oci_execute($resultCount);
				while($rowCount = oci_fetch_row($resultCount))
				{
					$countHasil++;
				}
			}
		} else {
			for ($x = 0; $x < $rangeDate; $x++){
				$queryCount = "SELECT *
				FROM MJ.MJ_T_SIK
				WHERE PEMOHON = '$pemohon'
				AND TO_DATE('$tglFrom', 'YYYY-MM-DD')+$x BETWEEN TANGGAL_FROM AND TANGGAL_TO
				AND STATUS=1 AND STATUS_DOK IN ('Approved', 'In process')
				AND KATEGORI <> 'Terlambat'";
				//echo $queryCount;
				$resultCount = oci_parse($con, $queryCount);
				oci_execute($resultCount);
				while($rowCount = oci_fetch_row($resultCount))
				{
					$countHasil++;
				}
			}
		}
	}
	//echo $countHasil;
}

$result = array('success' => true,
			'results' => $countHasil,
			'rows' => ''
		);
echo json_encode($result);

?>