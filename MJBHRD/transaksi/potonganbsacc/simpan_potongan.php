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

$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunSkr=substr($tglskr, 0, 4);
$bulanSkr=substr($tglskr, 5, 2);
$hariSkr=substr($tglskr, 8, 2);
$data="gagal";
 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
  	$tgl=$_POST['tgl'];
  	$jml=$_POST['jml'];
  	$nominal=$_POST['nominal']/$jml;
  	$ket=$_POST['ket'];
	
	
	$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
	oci_execute($resultSeqAPP);
	$rowApp = oci_fetch_row($resultSeqAPP);
	$IdTransAPP = $rowApp[0];
	
	$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
	VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'BS', 1, '$typeform', '', $emp_id, SYSDATE)";
	$resultApp = oci_parse($con,$sqlQueryApp);
	oci_execute($resultApp);
	
	$sqlQueryA = "UPDATE MJ.MJ_T_PINJAMAN SET TANGGAL_PINJAMAN = TO_DATE('$tgl', 'YYYY-MM-DD'), NOMINAL = $nominal, JUMLAH_CICILAN = $jml, KETERANGAN_ACC = '$ket', STATUS_DOKUMEN = '$typeform', TINGKAT = 1 WHERE ID = $hdid";
	$resultA = oci_parse($con,$sqlQueryA);
	oci_execute($resultA);
	
	$query = "SELECT PERSON_ID, (NOMINAL * JUMLAH_CICILAN) TOTAL, NOMINAL, JUMLAH_CICILAN
	FROM MJ.MJ_T_PINJAMAN 
	WHERE APP_ID=" . APPCODE . " 
	AND ID = '$hdid'";
	//echo $query;
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($rowX = oci_fetch_row($result))
	{
		$vPersonID=$rowX[0];
		$vTotal=$rowX[1];
		$vNominal=$rowX[2];
		$vJumCil=$rowX[3];
		
		if($hariSkr >= 21){
			$bulanSkr = $bulanSkr + 1;
		} else {
			$bulanSkr = $bulanSkr;
		}
		if($bulanSkr == 13){
			$bulanSkr = 1;
			$tahunSkr = $tahunSkr + 1;
		}
		for($x = 0; $x < $vJumCil; $x++){			
			$resultSeqDet = oci_parse($con,"SELECT MJ.MJ_T_PINJAMAN_DETAIL_SEQ.nextval FROM dual"); 
			oci_execute($resultSeqDet);
			$rowSeqDet = oci_fetch_row($resultSeqDet);
			$IdUserDet = $rowSeqDet[0];
			
			$bulanSkr++;
			if($bulanSkr == 13){
				$bulanSkr = 1;
				$tahunSkr = $tahunSkr + 1;
			}
			
			$sqlQuery = "INSERT INTO MJ.MJ_T_PINJAMAN_DETAIL (ID, MJ_T_PINJAMAN_ID, BULAN, TAHUN, NOMINAL, STATUS, CREATED_BY, CREATED_DATE) 
			VALUES ( $IdUserDet, $hdid, '$bulanSkr', '$tahunSkr', $vNominal, 1, $emp_id, SYSDATE)";
			$resultQuery = oci_parse($con,$sqlQuery);
			oci_execute($resultQuery);
		}
		
		$data="sukses";
	}
	
	$data="sukses";
	
	$resultNo = oci_parse($con,"SELECT NOMOR_PINJAMAN FROM MJ.MJ_T_PINJAMAN WHERE ID = $hdid");
	oci_execute($resultNo);
	$rowNo = oci_fetch_row($resultNo);
	$noPinjaman = $rowNo[0];
	
	$result = array('success' => true,
					'results' => $hdid .'|'. $noPinjaman,
					'rows' => $data
				);
	echo json_encode($result);
  }


?>