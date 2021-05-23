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
  
$data = "gagal";
$hdid=0;
$queryTambah="";
$tglskr=date('Y-m-d'); 
if(isset($_POST['shiftid'])){ 
	$shiftid       = $_POST['shiftid'];
	$shiftname     = $_POST['shiftname'];
	$formtype      = $_POST['formtype'];
	$earlyin       = $_POST['earlyin'];
	$earlyout      = $_POST['earlyout'];
	$latein        = $_POST['latein'];
	$lateout       = $_POST['lateout'];
	$status 	   = $_POST['status'];
	$id     	   = $_POST['id'];
	
	if($status == 'false'){
		$status = 'N';
	}else{
		$status = 'Y';
	}
}

if ($formtype == "tambah")
{
	$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_RANGE_SHIFT WHERE SHIFT_ID='$shiftid' AND AKTIF = 'Y'";
	$resultcount = oci_parse($con,$querycount);
	oci_execute($resultcount);
	$rowcount = oci_fetch_row($resultcount);
	$jumlah = $rowcount[0];	
}
else if ($formtype == "edit")
{
	$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_RANGE_SHIFT WHERE SHIFT_ID='$shiftid' AND AKTIF = 'Y' AND ID != $id";
	$resultcount = oci_parse($con,$querycount);
	oci_execute($resultcount);
	$rowcount = oci_fetch_row($resultcount);
	$jumlah = $rowcount[0];	
}

if($jumlah > 0 && $status == 'N')
{
	if($formtype=="tambah")
	{
		$sqlQuery = "INSERT INTO MJ.MJ_M_RANGE_SHIFT (ID, SHIFT_ID, SHIFT_NAME, RANGE_EARLY_IN, RANGE_LATE_IN, RANGE_EARLY_OUT, RANGE_LATE_OUT, AKTIF, CREATED_BY, CREATED_DATE) 
		VALUES ( MJ.MJ_M_RANGE_SHIFT_SEQ.nextval, $shiftid, '$shiftname', $earlyin, $latein, $earlyout, $lateout, '$status', $emp_id, SYSDATE)";
		
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	} 
	else if($formtype=="edit")
	{
		$editbos = oci_parse($con,"UPDATE MJ.MJ_M_RANGE_SHIFT SET SHIFT_ID = $shiftid, SHIFT_NAME = '$shiftname', RANGE_EARLY_IN = $earlyin, RANGE_LATE_IN = $latein, RANGE_EARLY_OUT = $earlyout, RANGE_LATE_OUT = $lateout, AKTIF = '$status', LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = SYSDATE WHERE ID = $id");
		oci_execute($editbos);

		$data = "sukses";
	}
}
else if ($jumlah == 0){
	if($formtype=="tambah")
	{
		$sqlQuery = "INSERT INTO MJ.MJ_M_RANGE_SHIFT (ID, SHIFT_ID, SHIFT_NAME, RANGE_EARLY_IN, RANGE_LATE_IN, RANGE_EARLY_OUT, RANGE_LATE_OUT, AKTIF, CREATED_BY, CREATED_DATE) 
		VALUES ( MJ.MJ_M_RANGE_SHIFT_SEQ.nextval, $shiftid, '$shiftname', $earlyin, $latein, $earlyout, $lateout, '$status', $emp_id, SYSDATE)";
		
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	} 
	else if($formtype=="edit")
	{
		$editbos = oci_parse($con,"UPDATE MJ.MJ_M_RANGE_SHIFT SET SHIFT_ID = $shiftid, SHIFT_NAME = '$shiftname', RANGE_EARLY_IN = $earlyin, RANGE_LATE_IN = $latein, RANGE_EARLY_OUT = $earlyout, RANGE_LATE_OUT = $lateout, AKTIF = '$status', LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = SYSDATE WHERE ID = $id");
		oci_execute($editbos);

		$data = "sukses";
	}
}
else {
	if($formtype=="edit" && $status == 'N' && $jumlah > 0)
	{
		$editbos = oci_parse($con,"UPDATE MJ.MJ_M_RANGE_SHIFT SET SHIFT_ID = $shiftid, SHIFT_NAME = '$shiftname', RANGE_EARLY_IN = $earlyin, RANGE_LATE_IN = $latein, RANGE_EARLY_OUT = $earlyout, RANGE_LATE_OUT = $lateout, AKTIF = '$status', LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = SYSDATE WHERE ID = $id");
		oci_execute($editbos);

		$data = "sukses";
	}
	else{
		$data = "gagal";
	}
}

$result = array('success' => true,
				'rows'	  => $data
			);
echo json_encode($result);

?>