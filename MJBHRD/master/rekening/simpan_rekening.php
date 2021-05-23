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
$tglskr=date('Y-m-d'); 
if(isset($_POST['typeform']) || isset($_POST['namaBank']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$namaBank=$_POST['namaBank'];
	$atasNama=$_POST['atasNama'];
	$noRekening=$_POST['noRekening'];
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
	$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_REKENING WHERE NAMA_BANK='$namaBank' AND ATAS_NAMA='$atasNama' AND NO_REKENING='$noRekening'");
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah = $rowJum[0];
	if ($jumlah>0)
	{
		$data = "Data tersebut sudah pernah diinput.";
	} else {
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_REKENING_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdRekening = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_REKENING (ID, APP_ID, NAMA_BANK, ATAS_NAMA, NO_REKENING, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdRekening, " . APPCODE . ", '$namaBank', '$atasNama', '$noRekening', '$status', $emp_id, SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	}
} else {
	//update
	$result = oci_parse($con,"UPDATE MJ.MJ_M_REKENING SET NAMA_BANK='$namaBank', ATAS_NAMA='$atasNama', NO_REKENING='$noRekening', STATUS='$status', LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'");
	oci_execute($result); 
	
	$data = "sukses";
}	
$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>