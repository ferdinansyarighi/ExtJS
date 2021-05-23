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

$tglskr=date('Y-m-d'); 
if(isset($_POST['typeform']) || isset($_POST['namaarea']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$namaarea=$_POST['namaarea'];
	$hdidplant=$_POST['hdidplant'];
	$status=$_POST['status'];
	if($status=='ACTIVE'){
		$status='A';
	} else {
		$status='I';
	}
}
//end deklarasi variable

//cek pada db mysql kalau data role sudah ada maka update jika belum ada maka insert
$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_AREA WHERE APP_ID=" . APPCODE . " AND NAMA_AREA='$namaarea'");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{	
	if($hdid!=''){
		$result = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_AREA WHERE APP_ID=" . APPCODE . " AND NAMA_AREA='$namaarea' AND ID NOT IN ($hdid)");
		oci_execute($result);
		$rowJum = oci_fetch_row($result);
		$jumlah = $rowJum[0];
		if ($jumlah>0)
		{	
			$data=array('success'=>false);
			echo json_encode($data);
		} else {
			//update ke tabel Role
			$terQuery = "UPDATE MJ.MJ_M_AREA SET NAMA_AREA='$namaarea', STATUS='$status', LAST_UPDATED_BY='$username', LAST_UPDATED_DATE=SYSDATE WHERE id='$hdid'";
			//echo $terQuery;
			$result = oci_parse($con, $terQuery);
			oci_execute($result);
			//Delete ke tabel Roledtl
			$resultDtl = oci_parse($con,"DELETE FROM MJ.MJ_M_AREA_DETAIL WHERE AREA_ID='$hdid'");
			oci_execute($resultDtl);
			
			$resultMenu = oci_parse($con, "SELECT LOCATION_ID FROM APPS.HR_LOCATIONS WHERE LOCATION_ID in ($hdidplant)");
			oci_execute($resultMenu);
			while($row = oci_fetch_row($resultMenu))
			{
				//Insert ke tabel Roledtl
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_AREA_DETAIL_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$rowSeq = oci_fetch_row($resultSeq);
				$seq = $rowSeq[0];
				
				$query = "INSERT INTO MJ.MJ_M_AREA_DETAIL (ID, AREA_ID, LOCATION_ID) VALUES ($seq, $hdid, $row[0])";
				$resultDtl = oci_parse($con,$query);
				oci_execute($resultDtl);
			}
		}
	}
	
} else {
	
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_AREA_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$seqH = $rowHSeq[0];
		
		$query = "INSERT INTO MJ.MJ_M_AREA (ID, APP_ID, NAMA_AREA, STATUS, CREATED_BY, CREATED_DATE) VALUES ($seqH, " . APPCODE . ", '$namaarea', '$status', '$username', SYSDATE)";
		$result = oci_parse($con,$query);
		oci_execute($result);
		
		$result = oci_parse($con, "SELECT LOCATION_ID FROM APPS.HR_LOCATIONS WHERE LOCATION_ID in ($hdidplant)");
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			//Insert ke tabel Roledtl
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_AREA_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$rowSeq = oci_fetch_row($resultSeq);
			$seq = $rowSeq[0];
			
			$query = "INSERT INTO MJ.MJ_M_AREA_DETAIL (ID, AREA_ID, LOCATION_ID) VALUES ($seq, $seqH, $row[0])";
			$resultDtl = oci_parse($con,$query);
			oci_execute($resultDtl);
		}
		
		//end insert
	} else {
		//update ke tabel Role
		$result = oci_parse($con,"UPDATE MJ.MJ_M_AREA SET NAMA_AREA='$namaarea', status='$status', LAST_UPDATED_BY='$username', LAST_UPDATED_DATE=SYSDATE WHERE id='$hdid'");
		oci_execute($result);
		//Delete ke tabel Roledtl
		$resultDtl = oci_parse($con,"DELETE FROM MJ.MJ_M_AREA_DETAIL WHERE AREA_ID='$hdid'");
		oci_execute($resultDtl);
		
		$result = oci_parse($con, "SELECT LOCATION_ID FROM APPS.HR_LOCATIONS WHERE LOCATION_ID IN ($hdidplant)");
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			//Insert ke tabel Roledtl
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_AREA_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$rowSeq = oci_fetch_row($resultSeq);
			$seq = $rowSeq[0];
			
			$query = "INSERT INTO MJ.MJ_M_AREA_DETAIL (ID, AREA_ID, LOCATION_ID) VALUES ($seq, $hdid, $row[0])";
			$resultDtl = oci_parse($con,$query);
			oci_execute($resultDtl);
		}
	}	
} 

?>