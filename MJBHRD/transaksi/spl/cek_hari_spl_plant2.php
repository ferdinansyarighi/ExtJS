<?PHP
include '../../main/koneksi.php';
$msgError    ="";
$data        ="";
$hasil       = "";
$rangeDate   = 0;
$countHasil  = 0;
$flag	     = 0;
$countDetail = 0;
$namaHasil   = '';
$arrNama     =array();
$arrJamFrom  =array();
$arrTglTo    =array();

if(isset($_POST['typeform'])){
  	$typeform   =$_POST['typeform'];
  	$tglspl     =$_POST['tglspl'];
	$arrNama    =json_decode($_POST['arrNama']);
	$arrJamFrom =json_decode($_POST['arrJamFrom']);
	$arrJamTo   =json_decode($_POST['arrJamTo']);
	$arrTglTo   =json_decode($_POST['arrTglTo']);
	
	if ($typeform == 'tambah'){
		$countDetail = count($arrNama);
		for ($x=0; $x<$countDetail; $x++){
			$NamaKar = str_replace("'", "''", $arrNama[$x]);
			$ArrTempJamSPL = explode(":",  $arrJamFrom[$x]);
			$vTempJamSPL = $ArrTempJamSPL[0];

			/*echo " TypeForm : " . $typeform . " Arr Jam From : " . $arrJamFrom[$x] . " Arr Jam To : " . $arrJamTo[$x] . " Arr Nama : " . $arrNama[$x] . " Tgl SPL : " . $tglspl . " Temp Jam SPL : " . $vTempJamSPL;exit;*/

			//Ambil data baru
			$ArrTempJamSPL = explode(":",  $arrJamFrom[$x]);
			$vJamFrom      = $ArrTempJamSPL[0];
			$vMenitFrom    = $ArrTempJamSPL[1];
			$ArrTempJamTo  = explode(":",  $arrJamTo[$x]);
			$vJamTo        = $ArrTempJamTo[0];
			$vMenitTo      = $ArrTempJamTo[1];
			$JamMasuk      = str_replace(':', '', $arrJamFrom[$x]);
			$JamKeluar     = str_replace(':', '', $arrJamTo[$x]);
			// untuk memeriksa apakah ada SPL dengan tanggal yang sama dengan yang diajukan
			$queryCountSPLTglSama = "SELECT COUNT(-1) FROM MJ.MJ_T_SPL_DETAIL
			WHERE TO_DATE('$tglspl', 'YYYY-MM-DD') BETWEEN TGL_FROM AND TGL_TO
			AND NAMA = '$arrNama[$x]'
			ORDER BY ID DESC";
			$resultCountSPLTglSama = oci_parse($con, $queryCountSPLTglSama);
			oci_execute($resultCountSPLTglSama);
			$rowCountSPLTglSama = oci_fetch_row($resultCountSPLTglSama);
			$CountSPLTglSama=$rowCountSPLTglSama[0];

			$queryCountSPLTglToSama = "SELECT COUNT(-1) FROM MJ.MJ_T_SPL_DETAIL
			WHERE TO_DATE('$arrTglTo[$x]', 'YYYY-MM-DD') BETWEEN TGL_FROM AND TGL_TO
			AND NAMA = '$arrNama[$x]'
			ORDER BY ID DESC";
			$resultCountSPLTglToSama = oci_parse($con, $queryCountSPLTglToSama);
			oci_execute($resultCountSPLTglToSama);
			$rowCountSPLTglToSama = oci_fetch_row($resultCountSPLTglToSama);
			$CountSPLTglToSama=$rowCountSPLTglToSama[0];
			//echo $JamMasuk[$x];exit;

			if ($CountSPLTglSama >= 1)
			{
				$queryAmbilDataExisting = "SELECT SUBSTR(JAM_FROM, 0,2) JAM_FROM, SUBSTR(JAM_FROM, 4,6) MENIT_FROM, SUBSTR(JAM_TO, 0, 2) JAM_TO, SUBSTR(JAM_TO, 4, 6) MENIT_TO, REPLACE(JAM_FROM, ':', ''), REPLACE(JAM_TO, ':', '')
					FROM MJ.MJ_T_SPL_DETAIL
					WHERE TO_DATE('$tglspl', 'YYYY-MM-DD') BETWEEN TGL_FROM AND TGL_TO
					AND NAMA = '$arrNama[$x]'";
				$resultAmbilDataExisting = oci_parse($con, $queryAmbilDataExisting);
				oci_execute($resultAmbilDataExisting);

				while($rowAmbilDataExisting = oci_fetch_row($resultAmbilDataExisting))
				{
					//Ambil data existing
					$JamFromExist         = $rowAmbilDataExisting[0];
					$MenitFromExist       = $rowAmbilDataExisting[1];
					$JamToExist           = $rowAmbilDataExisting[2];
					$MenitToExist         = $rowAmbilDataExisting[3];
					$JamMasukExist 		  = $rowAmbilDataExisting[4];
					$JamKeluarExist       = $rowAmbilDataExisting[5];
					//echo $JamToExist;exit;
					if ($vJamFrom == $JamFromExist && $vJamFrom == $JamToExist){
					//echo $vMenitFrom . ">=" . $MenitFromExist ."&&". $vMenitFrom . "<=" . $MenitToExist;
						if($vMenitFrom >= $MenitFromExist && $vMenitFrom <= $MenitToExist){ 
							$count = $flag++;
						}
					}
					else if ($vJamTo == $JamFromExist && $vJamTo == $JamToExist){
						if($vMenitTo >= $MenitFromExist && $vMenitTo <= $MenitToExist){
							$flag++;
						}
					}
					else if ($vJamTo > $JamFromExist && $vJamTo < $JamToExist){
						$flag++;
					}
					else if ($vJamFrom > $JamFromExist && $vJamFrom < $JamToExist){
						$flag++;
					}
					else if ($vJamFrom < $JamFromExist && $vJamTo > $JamToExist){
						$flag++;
					}
					else {
						$flag = 0;
					}
				}
			}
			else if ($CountSPLTglToSama >= 1){
				$queryAmbilDataExisting = "SELECT SUBSTR(JAM_FROM, 0,2) JAM_FROM, SUBSTR(JAM_FROM, 4,6) MENIT_FROM, SUBSTR(JAM_TO, 0, 2) JAM_TO, SUBSTR(JAM_TO, 4, 6) MENIT_TO, REPLACE(JAM_FROM, ':', ''), REPLACE(JAM_TO, ':', '')
					FROM MJ.MJ_T_SPL_DETAIL
					WHERE TO_DATE('$arrTglTo[$x]', 'YYYY-MM-DD') BETWEEN TGL_FROM AND TGL_TO
					AND NAMA = '$arrNama[$x]'";
				$resultAmbilDataExisting = oci_parse($con, $queryAmbilDataExisting);
				oci_execute($resultAmbilDataExisting);

				while($rowAmbilDataExisting = oci_fetch_row($resultAmbilDataExisting))
				{
					//Ambil data existing
					$JamFromExist         = $rowAmbilDataExisting[0];
					$MenitFromExist       = $rowAmbilDataExisting[1];
					$JamToExist           = $rowAmbilDataExisting[2];
					$MenitToExist         = $rowAmbilDataExisting[3];
					$JamMasukExist 		  = $rowAmbilDataExisting[4];
					$JamKeluarExist       = $rowAmbilDataExisting[5];
					//echo $JamToExist;exit;
					if ($vJamTo == $JamFromExist && $vJamTo == $JamToExist){
						if($vMenitTo >= $MenitFromExist && $vMenitTo <= $MenitToExist){
							$flag++;
						}
					}
					else if ($vJamTo > $JamFromExist && $vJamTo < $JamToExist){
						$flag++;
					}
					else if ($vJamFrom < $JamFromExist && $vJamTo > $JamToExist){
						$flag++;
					}
					else if ($arrTglTo[$x] > $tglspl){
						if($vMenitTo <= $MenitToExist || $vMenitTo >= $MenitToExist){
							$flag++;
						} 
					}
					else {
						$flag = 0;
					}
				}
			}
			else {
				$flag = 0;
			}
		} 

		if($flag >= 1){
			$namaHasil .= $NamaKar ;
		} else {
			$namaHasil .= ', ' . $NamaKar ;
		}
	}
}

$result = array('success' => true,
				'results' => $flag,
				'rows'    => $namaHasil
		);
echo json_encode($result);

?>