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
 if(isset($_SESSION['user_id']))
  {
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['username'];
	$emp_id = $_SESSION['emp_id'];
	$emp_name = $_SESSION['emp_name'];
	$io_id = $_SESSION['io_id'];
	$io_name = $_SESSION['io_name'];
	$loc_id = $_SESSION['loc_id'];
	$loc_name = $_SESSION['loc_name'];
	$org_id = $_SESSION['org_id'];
	$org_name = $_SESSION['org_name'];
  }
  
$roleid = 0;
$emp_id_user = 0;
$data = "gagal";
$IdUser = 0;
$IdUserRule = 0;
$hdid=0;
$tglskr=date('Y-m-d'); 
if(isset($_POST['typeform']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$plant=$_POST['plant'];
	$posisi_id=$_POST['posisi'];
	if($posisi_id==''){
		$posisi_id=0;
	}
	$jumShift=$_POST['jumShift'];
	$jumLembur=$_POST['jumLembur'];
	$status=$_POST['status'];
	if($status=='ACTIVE'){
		$status='A';
	} else {
		$status='I';
	}
}
//end deklarasi variable

//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert
if($typeform=="tambah"){
	$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_JAMLEMBUR WHERE PLANT_ID='$plant' AND POSITION_ID=$posisi_id ");
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah = $rowJum[0];
	if ($jumlah>0)
	{
		$data = "Plant dan Posisi tersebut sudah pernah diinput.";
	} else {
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_JAMLEMBUR_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdUser = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_JAMLEMBUR (ID, APP_ID, PLANT_ID, POSITION_ID, JUMLAH_SHIFT, JUMLAH_LEMBUR, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdUser, " . APPCODE . ", $plant, $posisi_id, $jumShift, $jumShift, '$status', $emp_id, SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	}
} else {
	//update
	/* $resultSeq = oci_parse($con,"SELECT  FROM MJ.MJ_SYS_USER_RULE WHERE ");
	oci_execute($resultSeq);
	$row = oci_fetch_row($resultSeq);
	$IdUser = $row[0]; */
	$result = oci_parse($con,"UPDATE MJ.MJ_M_JAMLEMBUR SET JUMLAH_SHIFT=$jumShift, JUMLAH_LEMBUR=$jumLembur, STATUS='$status', LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'");
	oci_execute($result); 
	
	$data = "sukses";
}	
$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>