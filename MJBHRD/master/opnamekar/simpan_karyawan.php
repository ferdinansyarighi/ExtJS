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
$SeqId="";
$hdid="";

$data = "gagal";
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

$tglskr=date('Y-m-d'); 

if (isset($_POST['hdid'])) {
	$hdid=$_POST['hdid'];	
}

if(isset($_POST['typeform'])){	
	$typeform=$_POST['typeform'];
	$karyawan_id=$_POST['karyawan'];	
	$plant_id=$_POST['plant'];		
	$status=$_POST['status'];	
}

// echo $karyawan.'--'.$plant;exit;
//cek pada db mysql kalau data role sudah ada maka update jika belum ada maka insert
if($typeform=="tambah"){

	$per_id = oci_parse($con,"SELECT DISTINCT PPF.FULL_NAME  
		 FROM PER_PEOPLE_F PPF
		 WHERE 	PPF.PERSON_ID = '$karyawan_id'");
	oci_execute($per_id);
	$id_per = oci_fetch_row($per_id);
	$nama = $id_per[0];	
	
	// echo "";exit;
	$cekdata = oci_parse($con,"SELECT COUNT(-1) FROM MJ.MJ_MASTER_OPNAME WHERE NAMA = '$nama' AND PLANT = $plant_id");
	oci_execute($cekdata);
	$rowjum = oci_fetch_row($cekdata);
	$jumlah = $rowjum[0];
	if ($jumlah > 0) {
		$data = "karyawan tersebut sudah pernah di-inputkan";
		// echo "masuk";exit;
	}else { 
	//insert
	$resultSeq = oci_parse($con,"SELECT MJ.MJ_MASTER_OPNAME_SEQ.nextval FROM dual");
	oci_execute($resultSeq);
	$row = oci_fetch_row($resultSeq);
	$SeqId = $row[0];	
	
	$sqlQuery = "INSERT INTO MJ.MJ_MASTER_OPNAME (ID, PERSON_ID,NAMA, PLANT, STATUS, CREATED_BY, CREATED_DATE) 
	VALUES ( $SeqId, '$karyawan_id','$nama', '$plant_id', '$status', $emp_id, SYSDATE)";
	//echo $sqlQuery;
	$result = oci_parse($con,$sqlQuery);
	oci_execute($result);
	
		$data = "sukses";
	}

} else {
	//update
	// echo "else";exit;
	$per_id = oci_parse($con,"SELECT DISTINCT PPF.FULL_NAME  
		 FROM PER_PEOPLE_F PPF
		 WHERE 	PPF.PERSON_ID = '$karyawan_id'");
	oci_execute($per_id);
	$id_per = oci_fetch_row($per_id);
	$nama = $id_per[0];	

	if($status=='ACTIVE'){
		$status='A';
	} else {
		$status='N';
	}
// echo $personid;exit;	
	$SeqId = $hdid;
	$query = "UPDATE MJ.MJ_MASTER_OPNAME SET PERSON_ID='$karyawan_id',NAMA='$nama',PLANT='$plant_id', STATUS='$status', LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'";
// 	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result); 
	
	$data = "sukses";
}
$result = array('success' => true,
				'results' => $SeqId,
				'rows' => $data
			);

echo json_encode($result); 

?>