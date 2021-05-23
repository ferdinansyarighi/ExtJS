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
  
$data = "gagal";
$hdid=0;
$queryTambah="";
$tglskr=date('Y-m-d'); 
if(isset($_POST['typeform']) || isset($_POST['nama']) || isset($_POST['group'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama=$_POST['nama'];
	$company=$_POST['company'];
	if($company == 'All'){
		$company = 'null';
	}
	$periode=$_POST['periode'];
	$group=$_POST['group'];
	$arrTransID=json_decode($_POST['arrTransID']);
	$arrDefault=json_decode($_POST['arrDefault']);
	$arrIdGroupDetail=json_decode($_POST['arrIdGroupDetail']);
	if($hdid == ''){
		$hdid = 0;
	}
	$status=$_POST['status'];
	if ($status == 'false')
	{
		$status = 'N';
	}
	else
	{
		$status = 'Y';
	} 
}
$result2 = oci_parse($con, "select assignment_id from per_assignments_f WHERE PERSON_ID=$nama and primary_flag = 'Y'");
oci_execute($result2);
$rowAssign = oci_fetch_row($result2);
$assign_id = $rowAssign[0];

$countID = count($arrTransID);
//end deklarasi variable

//ECHO $arrTransID[0];exit;
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.MJ_M_LINK_GROUP 
WHERE PERSON_ID=$nama AND STATUS = 'Y'
--AND ID_GROUP = $group
AND ASSIGNMENT_ID = $assign_id
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Link group sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_LINK_GROUP_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$idTabel = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_LINK_GROUP (ID, COMPANY, PERSON_ID, PERIODE_GAJI, ID_GROUP, CREATED_BY, CREATED_DATE, ASSIGNMENT_ID, STATUS) 
		VALUES ( $idTabel, $company, $nama, '$periode', $group, $user_id, SYSDATE, $assign_id, '$status')";
		//echo $sqlQuery;exit;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		for ($x=0; $x<$countID; $x++){
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_LINK_GROUP_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$row = oci_fetch_row($resultSeq);
			$idTabel_detail = $row[0];
			$sqlQuery = "INSERT INTO MJ.MJ_M_LINK_GROUP_DETAIL (ID, ID_LINK_GROUP, ID_ELEMENT, VALUE, CREATED_BY, CREATED_DATE, ID_GROUP_DETAIL) 
			VALUES ( $idTabel_detail, $idTabel, $arrTransID[$x], $arrDefault[$x], $user_id, SYSDATE, $arrIdGroupDetail[$x])";
			//echo $sqlQuery;exit;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
		}
		$data = "sukses";
	} else {
		//update
		$sqlQuery = "UPDATE MJ.MJ_M_LINK_GROUP SET COMPANY=$company, PERSON_ID=$nama, PERIODE_GAJI='$periode', ID_GROUP=$group, LAST_UPDATED_BY=$user_id, LAST_UPDATED_DATE=SYSDATE, ASSIGNMENT_ID = $assign_id, STATUS = '$status' WHERE ID=$hdid";
		//echo $sqlQuery;exit;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		$sqlQuery = "DELETE MJ.MJ_M_LINK_GROUP_DETAIL WHERE ID_LINK_GROUP = $hdid";
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		for ($x=0; $x<$countID; $x++){
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_LINK_GROUP_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$row = oci_fetch_row($resultSeq);
			$idTabel_detail = $row[0];
			$sqlQuery = "INSERT INTO MJ.MJ_M_LINK_GROUP_DETAIL (ID, ID_LINK_GROUP, ID_ELEMENT, VALUE, CREATED_BY, CREATED_DATE, ID_GROUP_DETAIL) 
			VALUES ( $idTabel_detail, $hdid, $arrTransID[$x], $arrDefault[$x], $user_id, SYSDATE, $arrIdGroupDetail[$x])";
			//echo $sqlQuery;exit;
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