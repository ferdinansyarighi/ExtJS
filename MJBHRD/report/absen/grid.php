<?php include '../../main/koneksi.php';
session_start();

$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
// $pos_name = "";

$queryFilter ="";
$data = '';
$plant ='';

if(isset($_SESSION[APP]['user_id']))
{
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
	// $pos_name = $_SESSION[APP]['pos_name'];
}
// echo $emp_name;exit;

//cek parse data
if(isset($_GET['nama'])){
	$nama 		= $_GET['nama'];
	$plant 		= $_GET['plant'];
	$tgldari 	= $_GET['tgldari'];
	$tglke 		= $_GET['tglke'];

	// echo $plant;exit;

	//ambil tgl
	if($tgldari != '' or $tglke != ''){
		if($tgldari == ''){
			$tgldari = "'2015-3-23'";
		}else{
			$tgldari = "'$tgldari'";
		}
		
		if($tglke==''){
			$tglke = "to_char(sysdate, 'YYYY-MM-DD')";
		}else{
			$tglke = "'$tglke'";
		}
		//$queryFilter .= "and to_char(MBA.created_date, 'YYYY-MM-DD') >= $tgldari and to_char(MBA.created_date, 'YYYY-MM-DD') <= $tglke";
	}

	//ambil plant
	if ($plant != "" && $plant != 'null'){
		$queryFilter .= " AND MD.DEPARTCODE='$plant' ";
	}

	if ($nama != "" && $nama != 'null'){
		$queryFilter .= " AND MK.KaryaCode='$nama' ";
	}
	// echo $queryFilter;exit;
}

$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
if (!$conn) {
	echo "Connection MSSQL Black failed.";
	
	exit;
}

if ($nama != '') {
	$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
	FROM MastKarya MK, MastDepart MD WHERE MK.KaryaCode=$nama";
	$resultHeader = odbc_exec($conn, $queryHeader);
}else {
	$queryHeader = "SELECT DISTINCT MD.DEPARTCODE, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
	FROM MastKarya MK, MastDepart MD WHERE MD.DepartCode='$plant'";
	$resultHeader = odbc_exec($conn, $queryHeader);
}

while (odbc_fetch_row($resultHeader)) {
	$dataId = odbc_result($resultHeader, 1);
	$TglHeader = substr(odbc_result($resultHeader, 2), 0, 2);

	/*if ($nama != '' && $plant != '') { //jika data terisi normal
		$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND CIO.KaryaCode = $dataId AND CIO.KaryaCode = MK.KaryaCode $queryFilter";
	}else if ($plant == '') { //jika data plant tidak ada parameter

		$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND CIO.KaryaCode = $dataId AND CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode";
	}else { //jika data nama tidak ada parameter

		$query = "
		SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName 
		FROM CardInOut CIO, MastKarya MK, MastDepart MD 
		WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND mk.KaryaCode = cio.KaryaCode and mk.departcode = md.departcode $queryFilter";
	}*/

	// echo "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName 
	// 	FROM CardInOut CIO, MastKarya MK, MastDepart MD 
	// 	WHERE CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode AND CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke $queryFilter";exit;
	// echo "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName 
	// 	FROM CardInOut CIO, MastKarya MK, MastDepart MD 
	// 	WHERE CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode AND CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke $queryFilter";exit;
	$query = "
		SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName 
		FROM CardInOut CIO, MastKarya MK, MastDepart MD 
		WHERE CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode AND CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke $queryFilter";
	$result = odbc_exec($conn, $query);

	while ($tes = odbc_fetch_array($result)) {

		if ($tes['StateCode'] == 1) {
			$tes['StateCode'] = "OUT";
		}else if($tes['StateCode'] == 0){
			$tes['StateCode'] = "IN";
		}else if($tes['StateCode'] == 2){
			$tes['StateCode'] = "Break-Out";
		}else if($tes['StateCode'] == 3){
			$tes['StateCode'] = "Break-In";
		}else if($tes['StateCode'] == 4){
			$tes['StateCode'] = "OverTime-In";
		}else if($tes['StateCode'] == 5){
			$tes['StateCode'] = "OverTime-Out";
		}
		// echo odbc_result($result, 1);exit;
		$record=array();
		$record['HD_ID']=$tes['KaryaCode'];
		$record['DATA_NAMA']=$tes["KaryaName"];
		$record['DATA_PLANT']=$tes["DepartName"];
		$record['DATA_ID_FINGER']=$tes["KaryaCode"];
		$record['DATA_CHECKLOCK']=substr($tes["TranDate"],0,19);
		$record['DATA_IN_OUT']=$tes["StateCode"];
		$record['DATA_IMPORT']=substr($tes["UserDate"],0,19);
		// $record['HD_ID']=odbc_result($result, "KaryaCode");
		// $record['DATA_NAMA']=odbc_result($result, "KaryaCode");
		// $record['DATA_PLANT']=odbc_result($result, "KaryaCode");
		// $record['DATA_ID_FINGER']=odbc_result($result, "KaryaCode");
		// $record['DATA_CHECKLOCK']=odbc_result($result, "TranDate");
		// $record['DATA_IN_OUT']=odbc_result($result, "StateCode");
		// $record['DATA_IMPORT']=odbc_result($result, "UserDate");
		$data[]=$record;
	}
	// print_r($row[0]);exit;
	
}

echo json_encode($data);
?>