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
if(isset($_POST['typeform']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$namaemp=$_POST['namaemp'];
	$email='';
	$tingkat=$_POST['tingkat'] + 1;
	$area=$_POST['area'];
	$status=$_POST['status'];
	if($status=='ACTIVE'){
		$status='A';
	} else {
		$status='I';
	}
	$result = oci_parse($con, "SELECT PERSON_ID FROM APPS.PER_PEOPLE_F WHERE EFFECTIVE_END_DATE > SYSDATE AND FULL_NAME = '$namaemp'");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$emp_id_user=$row[0];
	}
}
		
//end deklarasi variable

//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert
if($typeform=="tambah"){ 
	// $result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_USERAPPROVAL MMU INNER JOIN APPS.PER_PEOPLE_F PPF ON MMU.EMP_ID=PPF.PERSON_ID WHERE MMU.EMP_ID='$emp_id_user' AND MMU.APP_ID=". APPCODE ."");
	$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_USERAPPROVAL MMU INNER JOIN APPS.PER_PEOPLE_F PPF ON MMU.EMP_ID=PPF.PERSON_ID WHERE MMU.TINGKAT = '$tingkat' AND NAMA_AREA = '$area' AND STATUS = '$status' AND MMU.APP_ID=". APPCODE ."");
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah = $rowJum[0];
	if ($jumlah>0)
	{
		$data = "User tersebut sudah pernah diinput.";
	} else {
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_USERAPPROVAL_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdUser = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_USERAPPROVAL (ID, APP_ID, EMP_ID, FULL_NAME, EMAIL, TINGKAT, NAMA_AREA, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdUser, " . APPCODE . ", $emp_id_user, '$namaemp', '$email', '$tingkat', '$area', '$status', '$emp_name', SYSDATE)";
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
	$result = oci_parse($con,"UPDATE MJ.MJ_M_USERAPPROVAL SET EMAIL='$email', TINGKAT='$tingkat', NAMA_AREA='$area', STATUS='$status', LAST_UPDATED_BY='$emp_name', LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'");
	oci_execute($result); 
	
	$data = "sukses";
}	
$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>