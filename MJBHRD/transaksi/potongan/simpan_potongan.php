<?PHP
//require('smtpemailattachment.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
// deklarasi variable dan session
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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$data="gagal";
 if(isset($_POST['nama_user']))
  {
	$nama_user=str_replace("'", "''", $_POST['nama_user']);
	$arrPinjamanP=$_POST['arrPinjamanP'];
	$arrCicilanP=$_POST['arrCicilanP'];
	$arrJumCicilanP=$_POST['arrJumCicilanP'];
	$arrOutstandingP=$_POST['arrOutstandingP'];
	$arrPinjamanB=$_POST['arrPinjamanB'];
	$arrCicilanB=$_POST['arrCicilanB'];
	$arrJumCicilanB=$_POST['arrJumCicilanB'];
	$arrOutstandingB=$_POST['arrOutstandingB'];
	
	$query = "SELECT PERSON_ID
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$nama_user' AND CURRENT_EMPLOYEE_FLAG='Y'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$person_id=$row[0];
	
	$resultCount = oci_parse($con, "SELECT MIN(ID)
	FROM MJ.MJ_T_POTONGAN 
	WHERE APP_ID=" . APPCODE . " 
	AND STATUS='A'
	AND PERSON_ID=$person_id");
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$userCount = $rowCount[0];
	
	// if ($userCount==0){
	$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_POTONGAN_SEQ.nextval FROM dual"); 
	oci_execute($resultSeq);
	$row = oci_fetch_row($resultSeq);
	$IdUser = $row[0];
	
	$sqlQuery = "INSERT INTO MJ.MJ_T_POTONGAN (ID, APP_ID, PERSON_ID, PINJAMAN_P, PINJAMAN_B, CICILAN_P, CICILAN_B, HARGA_CICILAN_P, HARGA_CICILAN_B, OUTSTANDING_P, OUTSTANDING_B, TOTAL_OUTSTANDING_P, TOTAL_OUTSTANDING_B, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdUser, " . APPCODE . ", $person_id, $arrPinjamanP, $arrPinjamanB, $arrCicilanP, $arrCicilanB, $arrJumCicilanP, $arrJumCicilanB, $arrOutstandingP, $arrOutstandingB, $arrOutstandingP, $arrOutstandingB, 'A', $emp_id, SYSDATE)";
	$result = oci_parse($con,$sqlQuery);
	oci_execute($result);
		
	$data="sukses";
	// } else {
	if($userCount!=''){
		$sqlQuery = "UPDATE MJ.MJ_T_POTONGAN SET STATUS='I', LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE WHERE APP_ID=" . APPCODE . " AND ID=$userCount ";
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
	}
			
		// $data="sukses";
	// }	
	
	$result = array('success' => true,
					'results' => $nama_user,
					'rows' => $data
				);
	echo json_encode($result);
  }


?>