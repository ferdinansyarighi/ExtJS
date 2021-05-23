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
$tglskr=date('Y-m-d'); 
$tahunGenNo=substr($tglskr, 0, 4);
$bulanGenNo=substr($tglskr, 5, 2);
if(isset($_POST['emp_id']))
{
	$hdid=$_POST['hd_id'];
	$employ_id=$_POST['emp_id'];
	$pembuat= $emp_id;
	$bank= $_POST['bank'];
	$cabangbank= $_POST['cabangbank'];
	$norek= $_POST['norek'];
	$nama_rek= $_POST['namarek'];
}

$cabangbank = str_replace("'", "`", $cabangbank);
$norek = str_replace("'", "`", $norek);
$nama_rek = str_replace("'", "`", $nama_rek);

$result = oci_parse($con,"UPDATE MJ.MJ_PERMINTAAN_REKENING SET BANK = '$bank', CABANG_BANK = '$cabangbank', NOREK = '$norek' , NAMAREK = '$nama_rek' , STATUS_REQUEST = '1', VALIDATE_DATE = sysdate , VALIDATE_BY = $pembuat WHERE ID = $hdid AND STATUS_REQUEST = '0'");
oci_execute($result); 

//$hdid2 = $hdid;
$data = "sukses";

//echo $hdid2;exit;
$result = array('success' => true,
				'results' => $user_id,
				'rows' => $data,
				//'hdid2' => $hdid2,
			);
echo json_encode($result);

?>