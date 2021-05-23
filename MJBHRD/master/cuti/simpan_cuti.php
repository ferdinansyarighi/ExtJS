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
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
if(isset($_POST['typeform']) || isset($_POST['jenisCuti'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$jenisCuti=$_POST['jenisCuti'];
	$statusCuti=$_POST['statusCuti'];
	if($statusCuti=='ACTIVE'){
		$statusCuti='A';
	} else {
		$statusCuti='I';
	}
	$tanggal=$_POST['tanggal'];
	$hari=substr($tanggal, 0, 2);
	$bulan=substr($tanggal, 3, 2);
	$tahun=substr($tanggal, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tanggal = $tahun . "-" . $bulan . "-" . $hari;
}
//end deklarasi variable

//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert
if($typeform=="tambah"){
	$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_PM_CUTI WHERE JENIS_CUTI='$jenisCuti'");
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah = $rowJum[0];
	if ($jumlah>0)
	{
		$data = "Jenis cuti tersebut sudah pernah diinput.";
	} else {
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_PM_CUTI_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdIjin = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_PM_CUTI (ID, APP_ID, JENIS_CUTI, TANGGAL_REFRESH, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdIjin, " . APPCODE . ", '$jenisCuti', TO_DATE('$tanggal', 'YYYY-MM-DD'), '$statusCuti', '$emp_name', SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	}
} else {
	//update
	$query = "UPDATE MJ.MJ_PM_CUTI SET TANGGAL_REFRESH=TO_DATE('$tanggal', 'YYYY-MM-DD'), STATUS='$statusCuti', LAST_UPDATED_BY='$emp_name', LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result); 
	
	$data = "sukses";
}	
$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>