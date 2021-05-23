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
if(isset($_POST['typeform']) || isset($_POST['nama']) || isset($_POST['periodegaji'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama=$_POST['nama'];
	$periodegaji=$_POST['periodegaji'];
	$satuan=$_POST['satuan'];
	$nominal=$_POST['nominal'];
	$periode=$_POST['periode'];	
		$periode=substr($periode, 0, 10);
	$periode2=$_POST['periode2'];
		$periode2=substr($periode2, 0, 10);
	$keterangan=$_POST['keterangan'];
	
	if($hdid == ''){
		$hdid = 0;
	} 
}
$result2 = oci_parse($con, "select assignment_id from per_assignments_f WHERE PERSON_ID=$nama and primary_flag = 'Y'");
oci_execute($result2);
$rowAssign = oci_fetch_row($result2);
$assign_id = $rowAssign[0];
//echo $assign_id;exit;
//end deklarasi variable
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.mj_t_pending_gaji 
WHERE ASSIGNMENT_ID = $assign_id
AND STATUS = 'WAITING'
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Pending gaji karyawan masih dalam status WAITING.";
} else {
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.mj_t_pending_gaji_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$idTabel = $row[0];
		$sqlQuery = "INSERT INTO MJ.mj_t_pending_gaji (ID, ASSIGNMENT_ID, PERIODE_GAJI, SATUAN, NOMINAL, PERIODE_AWAL, PERIODE_AKHIR, KETERANGAN, STATUS, CREATED_BY, CREATED_DATE) 
		VALUES ( $idTabel, $assign_id, '$periodegaji', '$satuan', $nominal, to_date('$periode', 'YYYY-MM-DD'), to_date('$periode2', 'YYYY-MM-DD'), '$keterangan', 'WAITING', $user_id, SYSDATE)";
		//echo $sqlQuery;exit;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		$data = "sukses";
	} else {
		//update
		$sqlQuery = "UPDATE MJ.mj_t_pending_gaji SET PERIODE_GAJI='$periodegaji', SATUAN='$satuan', NOMINAL=$nominal, PERIODE_AWAL=to_date('$periode', 'YYYY-MM-DD'), PERIODE_AKHIR=to_date('$periode2', 'YYYY-MM-DD'), KETERANGAN='$keterangan', LAST_UPDATED_BY=$user_id, LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
		$result = oci_parse($con,$sqlQuery);
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