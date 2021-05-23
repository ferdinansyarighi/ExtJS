<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$rangeDate = 0;
$countHasil = 0;
$countDetail = 0;
$namaHasil = '';
$arrNama=array();
$arrJamFrom=array();

if(isset($_POST['typeform'])){
  	$typeform=$_POST['typeform'];
  	$tglspl=$_POST['tglspl'];
	$arrNama=json_decode($_POST['arrNama']);
	$arrJamFrom=json_decode($_POST['arrJamFrom']);
	//$arrJamTo=json_decode($_POST['arrJamTo']);
	
	if ($typeform == 'tambah'){
		$countDetail = count($arrNama);
		for ($x=0; $x<$countDetail; $x++){
			$NamaKar = str_replace("'", "''", $arrNama[$x]);
			$ArrTempJamSPL = explode(":",  $arrJamFrom[$x]);
			$vTempJamSPL = $ArrTempJamSPL[0];
			/*echo " TypeForm : " . $typeform . " Arr Jam From : " . $arrJamFrom[$x] . " Arr Jam To : " . $arrJamTo[$x] . " Arr Nama : " . $arrNama[$x] . " Tgl SPL : " . $tglspl . " Temp Jam SPL : " . $vTempJamSPL;exit;*/
			if($vTempJamSPL >= 6){
				$queryCountData = "SELECT COUNT(-1)
				FROM MJ.MJ_T_SPL MTS
				INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
				WHERE TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD')='$tglspl'
				AND MTSD.NAMA = '$NamaKar'
				AND MTSD.STATUS_DOK IN ('Approved', 'In process')
				AND MTS.STATUS=1
				AND REGEXP_SUBSTR(MTSD.JAM_FROM, '[^:]+', 1, 1) >= 6";
			} else {
				$queryCountData = "SELECT COUNT(-1)
				FROM MJ.MJ_T_SPL MTS
				INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
				WHERE ((TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD')='$tglspl' AND REGEXP_SUBSTR(JAM_FROM, '[^:]+', 1, 1) <= 8) 
				OR (TO_CHAR(NVL(MTSD.TGL_TO, TO_DATE('1990-01-01', 'YYYY-MM-DD')), 'YYYY-MM-DD')='$tglspl'
				AND REGEXP_SUBSTR(JAM_TO, '[^:]+', 1, 1) >= 24))
				AND MTSD.NAMA = '$NamaKar'
				AND MTSD.STATUS_DOK IN ('Approved', 'In process')
				AND MTS.STATUS=1";
			}
			$resultCountData = oci_parse($con,$queryCountData);
			oci_execute($resultCountData);
			$rowCountData = oci_fetch_row($resultCountData);
			$CountData = $rowCountData[0];
			if($CountData >= 1){
				$countHasil++;
				if ($countHasil >= 1){
					$namaHasil .= $NamaKar ;
				} else {
					$namaHasil .= ', ' . $NamaKar ;
				}
			}
		}
	}
}

$result = array('success' => true,
			'results' => $countHasil,
			'rows' => $namaHasil
		);
echo json_encode($result);

?>