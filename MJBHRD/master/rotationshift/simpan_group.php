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
$tglskr=date('Y-m-d'); 
if(isset($_POST['nama'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama=$_POST['nama'];
	$arrShiftID=json_decode($_POST['arrShiftID']);
	$arrDateFrom=json_decode($_POST['arrDateFrom']);
	$arrDateTo=json_decode($_POST['arrDateTo']);
	if($hdid == ''){
		$hdid = 0;
	} 
}
// $result2 = oci_parse($con, "select assignment_id from per_assignments_f WHERE PERSON_ID=$nama");
// oci_execute($result2);
// $rowAssign = oci_fetch_row($result2);
// $assign_id = $rowAssign[0];

$countID = count($arrShiftID);
//end deklarasi variable
$date_from = $arrDateFrom[0];
$date_to = $arrDateTo[0];
if($date_to!=''){
	$date_from = str_replace("T"," ",$date_from);
}
echo $arrDateFrom[0]."->".$date_from;exit;
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.MJ_M_SHIFT 
WHERE ASSIGNMENT_ID=$nama
AND STATUS = 'Y'
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Data sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert
		for ($x=0; $x<$countID; $x++){
			$date_from = $arrDateFrom[$x];
			if($date_from!=''){
				$date_from = str_replace("T"," ",$date_from);
				$date_from = "trunc(to_date($date_from, 'YYYY-MM-DD HH:mi:ss'))";
			}
			
			$date_to = $arrDateTo[$x];
			if($date_to!=''){
				$date_to = str_replace("T"," ",$date_to);
				$date_to = "trunc(to_date($date_to, 'YYYY-MM-DD HH:mi:ss'))";
			}else{
				$date_to = '';
			}
			
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_SHIFT_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$row = oci_fetch_row($resultSeq);
			$idTabel = $row[0];
			$sqlQuery = "INSERT INTO MJ.MJ_M_SHIFT (ID, ASSIGNMENT_ID, SHIFT_ID, DATE_FROM, DATE_TO, CREATED_BY, CREATED_DATE) 
			VALUES ( $idTabel, $nama, $arrTransID[$x], $date_from, $date_to, $user_id, SYSDATE)";
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