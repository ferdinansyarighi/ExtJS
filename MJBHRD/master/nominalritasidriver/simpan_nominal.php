<?PHP
include '../../main/koneksi.php';
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
  }
  
$roleid = 0;
$emp_id_user = 0;
$data = "gagal";
$IdUser = 0;
$IdUserRule = 0;
$hdid=0;
$queryTambah="";
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$arrRitasi=array();
$arrJarakAwal=array();
$arrJarakAkhir=array();
$arrNominal=array();
if(isset($_POST['typeform']) || isset($_POST['plantArea'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$tipePlant=str_replace("'", "''", $_POST['tipePlant']);
	$plantArea=str_replace("'", "''", $_POST['plantArea']);
	$ritasiKe=$_POST['ritasiKe'];
	$jarakAwal=$_POST['jarakAwal'];
	$jarakAkhir=$_POST['jarakAkhir'];
	$nominal=$_POST['nominal'];
	$arrRitasi=json_decode($_POST['arrRitasi']);
	$arrJarakAwal=json_decode($_POST['arrJarakAwal']);
	$arrJarakAkhir=json_decode($_POST['arrJarakAkhir']);
	$arrNominal=json_decode($_POST['arrNominal']);
	$tgl_awal=$_POST['tgl_awal'];
	$hari=substr($tgl_awal, 0, 2);
	$bulan=substr($tgl_awal, 3, 2);
	$tahun=substr($tgl_awal, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tgl_awal = $tahun . "-" . $bulan . "-" . $hari;
	$tgl_akhir=$_POST['tgl_akhir'];
	if ($tgl_akhir != ''){
		$hari=substr($tgl_akhir, 0, 2);
		$bulan=substr($tgl_akhir, 3, 2);
		$tahun=substr($tgl_akhir, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tgl_akhir = $tahun . "-" . $bulan . "-" . $hari;
		$queryTambah = " OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_akhir') 
		 OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') <= '$tgl_akhir') ";
	} else {
		$tgl_akhir = '';
		$queryTambah = " OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '4712-12-31' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '4712-12-31') 
		 OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') <= '4712-12-31') ";
	}
	if($hdid == ''){
		$hdid = 0;
	} 
}
		
//end deklarasi variable

//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ_M_NOMINAL_RITASI_DRIVER 
WHERE PLANT_AREA = '$plantArea'
AND ((TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_awal') 
$queryTambah ) 
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Plant Area tersebut sudah pernah diinput.";
} else {
	$countID = count($arrNominal);
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_NOMINAL_RITASI_DRIVER_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$hdid = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_NOMINAL_RITASI_DRIVER (ID, TIPE_PLANT, PLANT_AREA, EFFECTIVE_START_DATE, EFFECTIVE_END_DATE, CREATED_BY, CREATED_DATE) 
		VALUES ( $hdid, '$tipePlant', '$plantArea', TO_DATE('$tgl_awal', 'YYYY-MM-DD'), TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), '$user_id', SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		//Insert Detail
		for ($x=0; $x<$countID; $x++){
			$xRitasi = $arrRitasi[$x];
			$xJarakAwal = $arrJarakAwal[$x];
			$xJarakAkhir = $arrJarakAkhir[$x];
			$xNominal = $arrNominal[$x];
			
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_NOMINAL_RD_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$row = oci_fetch_row($resultSeq);
			$IdDetail = $row[0];
			$sqlQuery = "INSERT INTO MJ.MJ_M_NOMINAL_RD_DETAIL (ID, MJ_M_NOMINAL_RITASI_DRIVER_ID, RITASI_KE, JARAK_FROM, JARAK_TO, NOMINAL, STATUS, CREATED_BY, CREATED_DATE) 
			VALUES ( $IdDetail, $hdid, $xRitasi, '$xJarakAwal', '$xJarakAkhir', $xNominal, 'Y', '$user_id', SYSDATE)";
			//echo $sqlQuery;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
		}
		
		$data = "sukses";
	} else {
		//update
		$result = oci_parse($con,"UPDATE MJ.MJ_M_NOMINAL_RITASI_DRIVER SET TIPE_PLANT='$tipePlant', PLANT_AREA='$plantArea', EFFECTIVE_START_DATE=TO_DATE('$tgl_awal', 'YYYY-MM-DD'), EFFECTIVE_END_DATE=TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'");
		oci_execute($result); 
		
		$sqlQueryHeader = "UPDATE MJ.MJ_M_NOMINAL_RD_DETAIL SET STATUS = 'N' WHERE MJ_M_NOMINAL_RITASI_DRIVER_ID = $hdid  ";
		//echo $sqlQuery;
		$resultHeader = oci_parse($con,$sqlQueryHeader);
		oci_execute($resultHeader);
		
		//Insert Detail
		for ($x=0; $x<$countID; $x++){
			$xRitasi = $arrRitasi[$x];
			$xJarakAwal = $arrJarakAwal[$x];
			$xJarakAkhir = $arrJarakAkhir[$x];
			$xNominal = $arrNominal[$x];
			
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_NOMINAL_RD_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$row = oci_fetch_row($resultSeq);
			$IdDetail = $row[0];
			$sqlQuery = "INSERT INTO MJ.MJ_M_NOMINAL_RD_DETAIL (ID, MJ_M_NOMINAL_RITASI_DRIVER_ID, RITASI_KE, JARAK_FROM, JARAK_TO, NOMINAL, STATUS, CREATED_BY, CREATED_DATE) 
			VALUES ( $IdDetail, $hdid, $xRitasi, '$xJarakAwal', '$xJarakAkhir', $xNominal, 'Y', '$user_id', SYSDATE)";
			//echo $sqlQuery;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
		}
		
		$data = "sukses";
	}	
}

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>