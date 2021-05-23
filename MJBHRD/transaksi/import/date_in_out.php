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

	$query = "SELECT ID,TANGGAL,JAM_MASUK,JAM_KELUAR,DATE_IN,DATE_OUT FROM MJ.MJ_T_TIMECARD";
	//echo $query;
	$result = oci_parse($con, $query);
	oci_execute($result);
	$queryUpload = '';
	while($row = oci_fetch_row($result))
	{
		$id_timecard =$row[0];
		$tgl_timecard=$row[1];
		$in_timecard =$row[2];
		$out_timecard=$row[3];
		$flag_in	 =$row[3];
		$flag_out	 =$row[3];
		
		$Jam_Keluar = str_replace(':', '', $out_timecard);
		$Jam_Masuk  = str_replace(':', '', $in_timecard);
		//echo "SELECT SUBSTR('$tgl_timecard', 0,2) || '-' || SUBSTR('$tgl_timecard', 4,3) || '-' || 20 || SUBSTR('$tgl_timecard', 8,2) FROM DUAL";exit;
		$resultDetSeq = oci_parse($con,"SELECT SUBSTR('$tgl_timecard', 0,2) || '-' || SUBSTR('$tgl_timecard', 4,3) || '-' || 20 || SUBSTR('$tgl_timecard', 8,2) FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		//echo $seqD;exit;
		/*echo "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
		VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";exit;*/
		if($Jam_Keluar < $Jam_Masuk)
		{
			$queryUpload = "UPDATE MJ.MJ_T_TIMECARD SET DATE_IN = TO_DATE('$seqD','DD-MON-YYYY'), DATE_OUT = TO_DATE('$seqD', 'DD-MON-YYYY') + 1 WHERE ID = $id_timecard AND DATE_OUT != TO_DATE('$tgl_timecard','DD-MON-YYYY') + 1";
			//echo $queryUpload;exit;
		}
		
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);
	}
	
?>