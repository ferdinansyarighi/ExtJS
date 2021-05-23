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
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$queryTambah="";
$tahunbaru=substr($tglskr, 0, 2);
if(isset($_POST['typeform']) || isset($_POST['location']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$location=str_replace("'", "''", $_POST['location']);
	$plant=$_POST['plant'];
	$tgl_awal=$_POST['tgl_awal'];
	$hari=substr($tgl_awal, 0, 2);
	$bulan=substr($tgl_awal, 3, 2);
	$tahun=substr($tgl_awal, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tgl_awal = $tahun . "-" . $bulan . "-" . $hari;
	$tgl_akhir=$_POST['tgl_akhir'];
	if ($tgl_akhir != ''){
		$hari=substr($tgl_akhir, 0, 2);
		$bulan=substr($tgl_akhir, 3, 2);
		$tahun=substr($tgl_akhir, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tgl_akhir = $tahun . "-" . $bulan . "-" . $hari;
		$queryTambah = " OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_akhir')
		 OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') <= '$tgl_akhir')  ";
	} else {
		$tgl_akhir = '';
		$queryTambah = " OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '4712-12-31' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '4712-12-31') 
		 OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') <= '4712-12-31')  ";
	}
	
	if($hdid == ''){
		$hdid = 0;
	} 
}
		
//end deklarasi variable

//cek pada db mysql kalau data arinvoice sudah ada maka update jika belum ada maka insert
$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_GROUP_PLANT 
WHERE PLANT_ID='$plant' 
AND ((TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_awal') 
$queryTambah )
AND ID <> $hdid");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Plant tersebut sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GROUP_PLANT_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdIjin = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_GROUP_PLANT (ID, LOCATION_GROUP_ID, PLANT_ID, EFFECTIVE_START_DATE, EFFECTIVE_END_DATE, CREATED_BY, CREATED_DATE) 
		VALUES ( $IdIjin, '$location', '$plant', TO_DATE('$tgl_awal', 'YYYY-MM-DD'), TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), '$user_id', SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	} else {
		//update
		$result = oci_parse($con,"UPDATE MJ.MJ_M_GROUP_PLANT SET LOCATION_GROUP_ID='$location', PLANT_ID='$plant', EFFECTIVE_START_DATE=TO_DATE('$tgl_awal', 'YYYY-MM-DD'), EFFECTIVE_END_DATE=TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE WHERE ID='$hdid'");
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