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
$pos_name = "";

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
	$pos_name = $_SESSION[APP]['pos_name'];
}
// echo $emp_name;exit;

//cek parse data
if(isset($_GET['nama'])){
	$nama 		= $_GET['nama'];
	$plant 		= $_GET['plant'];
	$periode 	= $_GET['periode'];

	$periodedar = substr($periode,0,10);
	$periodeke  = substr($periode,15,10);
	//echo $periodeke;exit;
	//ambil plant
	if ($plant != "" && $plant != 'null'){
		$queryFilter .= " AND MD.DEPARTCODE='$plant' ";
	}

	if ($nama != "" && $nama != 'null'){
		$queryFilter .= " AND MK.KaryaCode='$nama' ";
	}
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
	$query = "SELECT CIO.KaryaCode,MK.KaryaName,MD.DepartName as DepartName,MIN(CIO.TranDate) AS Date, 'IN' Status
			  FROM CardInOut CIO , MastKarya MK, MastDepart MD
			  WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN '$periodedar' AND '$periodeke'
			    --AND CONVERT(VARCHAR(10), CIO.TranDate,120) <= '$periodeke'
			    AND CIO.StateCode = 0
			    AND CIO.KaryaCode = MK.KaryaCode
			    AND MK.DepartCode = MD.DepartCode $queryFilter
			  GROUP BY MK.KaryaName, CIO.KaryaCode, CONVERT(VARCHAR(10), CIO.TranDate,120), MD.DepartName
			  UNION
			  SELECT CIO.KaryaCode,MK.KaryaName,MD.DepartName as DepartName,MAX(CIO.TranDate) AS Date, 'OUT' Status
			  FROM CardInOut CIO  , MastKarya MK, MastDepart MD
			  WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN '$periodedar' AND '$periodeke'
			   -- AND CONVERT(VARCHAR(10), CIO.TranDate,120) <= '$periodeke'
			    AND CIO.StateCode = 1
			    AND CIO.KaryaCode = MK.KaryaCode
			    AND MK.DepartCode = MD.DepartCode $queryFilter
			  GROUP BY MK.KaryaName, CIO.KaryaCode, CONVERT(VARCHAR(10), CIO.TranDate,120), MD.DepartName
			";
	$result = odbc_exec($conn, $query);

	while ($tes = odbc_fetch_array($result)) {
		$record=array();
		$record['ID_BIOFINGER']=$tes['KaryaCode'];
		$record['DATA_NAMA']=$tes["KaryaName"];
		$record['DATA_PLANT']=$tes["DepartName"];
		$record['DATA_TANGGAL_ABSEN']=substr($tes["Date"],0,10);
		$record['DATA_JAM_ABSEN']=substr($tes["Date"],11,8);
		$record['DATA_STATUS']=$tes["Status"];
		$data[]=$record;
	}
}

echo json_encode($data);
?>