<?PHP
include '../../main/koneksi.php';
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
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$msgError="";
$data="Pada tanggal ";
$hasil = "";
$rangeDate = 0;
$countHasil = 0;
$satuan = "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$arrRitasi=array();
$arrTglSJ=array();



$resultRole = oci_parse($con, "SELECT COUNT(-1)
FROM MJ.MJ_M_USER MMU 
INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND APP_ID = 1
INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
WHERE USERNAME='$username' AND (NAMA_RULE LIKE 'Administrator' OR NAMA_RULE LIKE '%HRD%' OR NAMA_RULE LIKE '%Admin Plant%')
");
oci_execute($resultRole);
$rowJumRole = oci_fetch_row($resultRole);
$jumlahRole = $rowJumRole[0]; 

if($jumlahRole >= 1){
	$data = "sukses";
} else {
	$data .= "Anda tidak punya hak akses untuk melakukan submit.";
}

$result = array('success' => true,
			'results' => $countHasil,
			'rows' => $data
		);
echo json_encode($result);

?>