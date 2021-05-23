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
if(isset($_POST['emp_id'])){
	$formtype=$_POST['formtype'];
	$hdid=$_POST['hd_id'];
	$employ_id=$_POST['emp_id'];
	$pembuat= $emp_id;
	$alasan=$_POST['alasan'];
	$aktif=$_POST['aktif'];
	if ($aktif == 'false')
	{
		$aktif = 'N';
	}
	else
	{
		$aktif = 'Y';
	}
}

$alasan = str_replace("'", "`", $alasan);

if($formtype=='tambah')
{
	$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_GENERATENO WHERE TRANSAKSI_KODE='PPR'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumgen = $rowcount[0]; 
		
		if ($jumgen>0){
			$query = "SELECT LASTNO FROM MJ.MJ_M_GENERATENO WHERE TRANSAKSI_KODE='PPR'";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$rowGLastno = oci_fetch_row($result);
			$lastNo = $rowGLastno[0];
		} else {
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$gencountseq = $rowHSeq[0];
		
			$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, TRANSAKSI_KODE) 
			VALUES ($gencountseq, '$tahunGenNo', '0', 'PPR')";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$lastNo = 0;
		} 

	$querycount = "SELECT TAHUN FROM MJ.MJ_M_GENERATENO WHERE TRANSAKSI_KODE='PPR'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$thnGen = $rowcount[0]; 
		
		if($thnGen!=$tahunGenNo){
			$lastNo = 0;
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo', TAHUN='$tahunGenNo' WHERE TRANSAKSI_KODE='PPR'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		} else {
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo' WHERE TRANSAKSI_KODE='PPR'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		}
		
		$jumno=strlen($lastNo);
		if($jumno==1){
			$nourut = "0000".$lastNo;
		} else if ($jumno==2){
			$nourut = "000".$lastNo;
		} else if ($jumno==3){
			$nourut = "00".$lastNo;
		} else if ($jumno==4){
			$nourut = "0".$lastNo;
		} else {
			$nourut = $lastNo;
		}
		
		$noppr = "REK/HRD/" . $bulanGenNo .  "" . $tahunGenNo . "/" . $nourut;


	$resultSeq = oci_parse($con,"SELECT MJ.MJ_PERMINTAAN_REKENING_SEQ.nextval FROM DUAL");
	oci_execute($resultSeq);
	$rowHSeq = oci_fetch_row($resultSeq);
	$gencountseq = $rowHSeq[0];

	$result = oci_parse($con,"INSERT INTO MJ.MJ_PERMINTAAN_REKENING (ID, NO_REQUEST, EMP_ID, ALASAN, AKTIF, CREATED_BY, CREATED_DATE, STATUS_REQUEST) VALUES ($gencountseq, '$noppr', $employ_id,'$alasan', '$aktif', $pembuat, sysdate, '0') ");
	oci_execute($result); 

	$resultid = oci_parse($con,"SELECT id from MJ.MJ_PERMINTAAN_REKENING where no_request = '$noppr'");
	oci_execute($resultid);
	$rowHid = oci_fetch_row($resultid);
	$hdid2 = $rowHid[0];

	$data = "sukses";
}

else
{
	$result = oci_parse($con,"UPDATE MJ.MJ_PERMINTAAN_REKENING SET ALASAN = '$alasan', AKTIF = '$aktif', LAST_UPDATED_DATE = sysdate , LAST_UPDATED_BY = $pembuat WHERE ID = $hdid");
	oci_execute($result); 

	$hdid2 = $hdid;
	$data = "sukses";
}
//echo $hdid2;exit;
$result = array('success' => true,
				'results' => $user_id,
				'rows' => $data,
				'hdid2' => $hdid2,
			);
echo json_encode($result);

?>