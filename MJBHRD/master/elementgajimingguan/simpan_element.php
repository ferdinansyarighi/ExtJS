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
if(isset($_POST['typeform']) || isset($_POST['nama']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama=str_replace("'", "''", $_POST['nama']);
	$komponen=$_POST['komponen'];
	$type=$_POST['type'];
	$status=$_POST['status'];
	if($status == 'false'){
		$status = 'N';
	}else{
		$status = 'Y';
	}
	
	if($hdid == ''){
		$hdid = 0;
	} 
}

//end deklarasi variable

//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.MJ_M_ELEMENT_GAJI_MINGGUAN 
WHERE NAMA_ELEMENT='$nama'
AND STATUS = 'Y'
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Element tersebut sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_ELEMENT_GAJI_MINGGUAN_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$idTabel = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_ELEMENT_GAJI_MINGGUAN (ID, NAMA_ELEMENT, KOMPONEN, TYPE_ELEMENT, STATUS, CREATED_BY, CREATED_DATE) 
		VALUES ( $idTabel, '$nama', '$komponen', '$type', '$status', $user_id, SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	} else {
		//update
		$result = oci_parse($con,"UPDATE MJ.MJ_M_ELEMENT_GAJI_MINGGUAN SET NAMA_ELEMENT='$nama', KOMPONEN='$komponen', TYPE_ELEMENT='$type', STATUS='$status', LAST_UPDATED_BY=$user_id, LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid");
		oci_execute($result); 
		
		$data = "sukses";
	}	
}

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>