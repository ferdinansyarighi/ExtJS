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
if(isset($_POST['typeform']) || isset($_POST['plantArea'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$plantArea=str_replace("'", "''", $_POST['plantArea']);
	$jamKerja=$_POST['jamKerja'];
	$nominal=$_POST['nominal'];
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
FROM MJ.MJ_M_UANG_MAKAN_DRIVER 
WHERE MJ_M_NOMINAL_RITASI_DRIVER_ID = '$plantArea' AND MIN_JAM_KERJA = '$jamKerja'
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
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_UANG_MAKAN_DRIVER_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdIjin = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_UANG_MAKAN_DRIVER (ID, MJ_M_NOMINAL_RITASI_DRIVER_ID, MIN_JAM_KERJA, NOMINAL, EFFECTIVE_START_DATE, EFFECTIVE_END_DATE, CREATED_BY, CREATED_DATE) 
		VALUES ( $IdIjin, '$plantArea', '$jamKerja', '$nominal', TO_DATE('$tgl_awal', 'YYYY-MM-DD'), TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), '$user_id', SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	} else {
		//update
		$result = oci_parse($con,"UPDATE MJ.MJ_M_UANG_MAKAN_DRIVER SET MJ_M_NOMINAL_RITASI_DRIVER_ID='$plantArea', MIN_JAM_KERJA='$jamKerja', NOMINAL='$nominal', EFFECTIVE_START_DATE=TO_DATE('$tgl_awal', 'YYYY-MM-DD'), EFFECTIVE_END_DATE=TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'");
		oci_execute($result); 
		
		$data = "sukses";
	}	
}

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>