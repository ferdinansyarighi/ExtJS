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
if(isset($_POST['assid'])){
	$assid       = $_POST['assid'];
	$typeform    = $_POST['typeform'];
	$ShiftID     = $_POST['shiftid'];
	$tanggal     = $_POST['tanggal'];
/*	if($status == 'false'){
		$status = 'N';
	}else{
		$status = 'Y';
	}
	
	if($hdid == ''){
		$hdid = 0;
	} */
}

$sqlQuery = "select ID
    from mj.mj_m_change_shift where assignment_id = $assid and date_detail = '$tanggal'";
$result = oci_parse($con,$sqlQuery);
oci_execute($result);
$rowCek = oci_fetch_row($result);
$change_shift_id = $rowCek[0];
if($change_shift_id != '')
{
	$typeform = 'edit';
}
else{
	$typeform = 'tambah';
}

$sqlCekTimeCard = "select count(-1)
    from mj.mj_t_timecard mtt,apps.per_assignments_f paf where PAF.PERSON_ID =  mtt.person_id and to_date(tanggal, 'DD-MON-YY') = '$tanggal'
    and paf.assignment_id = $assid";
//echo sqlCekTimeCard; exit;
$result = oci_parse($con,$sqlCekTimeCard);
oci_execute($result);
$rowCek = oci_fetch_row($result);
$cek_time = $rowCek[0];
if($cek_time != 0)
{
	$typeform = 'tolak';
}
//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert

if($typeform=="tambah"){
	//insert
	/*$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_CHANGE_SHIFT_SEQ.nextval FROM dual");
	oci_execute($resultSeq);
	$row = oci_fetch_row($resultSeq);
	$idTabel = $row[0];*/
	$sqlQuery = "INSERT INTO MJ.MJ_M_CHANGE_SHIFT (ID, ASSIGNMENT_ID, SHIFT_ID, DATE_DETAIL, STATUS, CREATED_BY, CREATED_DATE) 
	VALUES ( MJ.MJ_M_CHANGE_SHIFT_SEQ.nextval, $assid, '$ShiftID', to_date('$tanggal', 'DD-MON-YY'), 'Y', $user_id, SYSDATE)";
	//echo $sqlQuery;exit;
	$result = oci_parse($con,$sqlQuery);
	oci_execute($result);
	
	$data = "sukses";
} 
else if($typeform=="edit"){
	//update header MPD
	$result = oci_parse($con,"UPDATE MJ.MJ_M_CHANGE_SHIFT SET SHIFT_ID = $ShiftID, LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = sysdate WHERE ASSIGNMENT_ID = $assid AND DATE_DETAIL = '$tanggal'");
	oci_execute($result); 
	$data = "sukses";
}
else if($typeform=="tolak"){
	$data = "Kesalahan, Absen Telah Masuk di Oracle";
}	

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>