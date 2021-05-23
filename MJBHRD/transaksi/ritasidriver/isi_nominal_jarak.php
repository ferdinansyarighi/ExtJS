<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$nominal = "";
$nominalID = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['jarak'])){
	$jarak=$_POST['jarak'];
	$group=$_POST['group'];
	$tglSj=$_POST['tglSj'];
	
	$query = "SELECT MNRDD.ID, MNRDD.NOMINAL
	FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER MNRD
	INNER JOIN MJ.MJ_M_PLANT_AREA_DRIVER  MPAD ON MNRD.ID = MPAD.MJ_M_NOMINAL_RITASI_DRIVER_ID
	INNER JOIN MJ.MJ_M_NOMINAL_RD_DETAIL MNRDD ON MNRDD.MJ_M_NOMINAL_RITASI_DRIVER_ID =  MNRD.ID
	WHERE MPAD.ID=$group
	AND $jarak BETWEEN MNRDD.JARAK_FROM AND MNRDD.JARAK_TO";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		
		$queryHariIni = "SELECT TO_CHAR(TO_DATE('$tglSj', 'DD-MON-YYYY'), 'DAY') FROM DUAL";
		$resultHariIni = oci_parse($con,$queryHariIni);
		oci_execute($resultHariIni);
		$rowHariIni = oci_fetch_row($resultHariIni);
		$HariIni = $rowHariIni[0]; 
		$HariIni = trim($HariIni);

		$queryHariLibur = "SELECT COUNT(-1)
		FROM APPS.HXT_HOLIDAY_DAYS A
		, APPS.HXT_HOLIDAY_CALENDARS B
		WHERE A.HCL_ID=B.ID 
		AND B.EFFECTIVE_END_DATE>SYSDATE 
		AND TO_CHAR(A.HOLIDAY_DATE, 'DD-MON-YYYY')='$tglSj'";
		$resultHariLibur = oci_parse($con,$queryHariLibur);
		oci_execute($resultHariLibur);
		$rowHariLibur = oci_fetch_row($resultHariLibur);
		$HariLibur = $rowHariLibur[0]; 
		
		if(($HariIni == 'SUNDAY' || $HariLibur != 0)){
			$nominalID=$row[0];
			$nominal=round(($row[1] * 1.5), 2);
		} else {
			$nominalID = $row[0];
			$nominal = $row[1];
		}
	}
}

$result = array('success' => true,
			'results' => $nominalID,
			'rows' => $nominal
		);
echo json_encode($result);

?>