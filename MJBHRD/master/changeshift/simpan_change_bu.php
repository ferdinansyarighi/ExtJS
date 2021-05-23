<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database

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
$assign_id = 0;

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
// deklarasi variable dan session
if (isset($_GET['assid']))
{	
	$assid       = $_GET['assid'];
	$typeform    = $_GET['typeform'];
	$ShiftID     = $_GET['arrShiftID'];
	$id          = $_GET['arrID'];
	$Date 	     = $_GET['arrDate'];
	$Day     	 = $_GET['arrDay'];
	$Shift   	 = $_GET['arrShift'];
	$WS          = $_GET['arrWS'];
	$WH 	     = $_GET['arrWH'];
	$DateFrm     = $_GET['arrDateFrm'];
	$DateTo  	 = $_GET['arrDateTo'];
	/*$periode1 	 = $_GET['tglfrom'];
	//$periode1=substr($periode1, 0, 10);
	$hari=substr($periode1, 0, 2);
	$bulan=substr($periode1, 3, 2);
	$tahun=substr($periode1, 6, 2);
	$date_from = $hari.$bulan."20".$tahun;
	//echo $date_from;exit;

	$periode2 = $_GET['tglto'];
	//$periode2=substr($periode2, 0, 10);
	$hari=substr($periode2, 0, 2);
	$bulan=substr($periode2, 3, 2);
	$tahun=substr($periode2, 6, 2);
	$date_to = $hari.$bulan."20".$tahun;*/
}
echo &Date;exit;
$data = "gagal";
$tglskr=date('Y-m-d'); 
$tahunGenNo=substr($tglskr, 0, 4);
$bulanGenNo=substr($tglskr, 5, 2);
/*if(isset($_POST['emp_id'])){
	$formtype=$_POST['formtype'];
	$hdid=$_POST['hd_id'];
	$employ_id=$_POST['emp_id'];
	$pembuat= $emp_id;
	$alasan=$_POST['alasan'];
	$aktif=$_POST['aktif'];
	if ($aktif == 'false')
	{
		$aktif = 'N';
	}
	else
	{
		$aktif = 'Y';
	}
}*/

/*$alasan = str_replace("'", "`", $alasan);*/

if ($formtype=='tambah')
{
	$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_CHANGE_SHIFT_SEQ.nextval FROM DUAL");
	oci_execute($resultSeq);
	$rowHSeq = oci_fetch_row($resultSeq);
	$gencountseq = $rowHSeq[0];
	
	$result = oci_parse($con,"INSERT INTO MJ.MJ_M_CHANGE_SHIFT(ID, ASSIGNMENT_ID, MJ_M_SHIFT_ID, DATE_FROM, DATE_TO, DATE_DETAIL, STATUS, CREATED_BY, CREATED_DATE) VALUES ($gencountseq, $assid, $ShiftID,'$DateFrm', '$DateTo', '$Date', '1',  $emp_id, sysdate) ");
	oci_execute($result); 
	$data = "sukses";

} else {
	//update header MPD
	$result = oci_parse($con,"UPDATE MJ.MJ_M_CHANGE_SHIFT SET MJ_M_SHIFT_ID = $ShiftID, LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = sysdate WHERE ASSIGNMENT_ID = $assid AND DATE_DETAIL = '$Date'");
	oci_execute($result); 
	$data = "sukses";
}

//echo $hdid2;exit;
$result = array('success' => true,
				'results' => $user_id,
				'rows' => $data,
				'hdid2' => $hdid2,
			);
echo json_encode($result);

?>