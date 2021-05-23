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
$jumlah = 0;
$queryTambah="";
$tglskr=date('Y-m-d'); 
$beririsan=0;
$del=0;
$where_del="";
if(isset($_POST['nama'])){
	//$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$kumpulanID=$_POST['kumpulanID'];
	$nama=$_POST['nama'];
	$arrID=json_decode($_POST['arrID']);
	$arrShiftID=json_decode($_POST['arrShiftID']);
	$arrDateFrom=json_decode($_POST['arrDateFrom']);
	$arrDateTo=json_decode($_POST['arrDateTo']);
}
// $result2 = oci_parse($con, "select assignment_id from per_assignments_f WHERE PERSON_ID=$nama");
// oci_execute($result2);
// $rowAssign = oci_fetch_row($result2);
// $assign_id = $rowAssign[0];

$countID = count($arrShiftID);
//end deklarasi variable
for($x=0; $x<$countID; $x++){
	for($y=0; $y<$countID; $y++){
		if($x!=$y){
			$date_from = $arrDateFrom[$x];
			if($date_from!=''){
				$date_from=substr($date_from, 0, 10);
				$date_from = str_replace("-","",$date_from);
			}
			$date_to = $arrDateTo[$x];
			$date_to=substr($date_to, 0, 10);
			$date_to = str_replace("-","",$date_to);
			if($date_to==''){
				$date_to = '47121231';
			}
			
			$date_from2 = $arrDateFrom[$y];
			if($date_from2!=''){
				$date_from2=substr($date_from2, 0, 10);
				$date_from2 = str_replace("-","",$date_from2);
			}
			$date_to2 = $arrDateTo[$y];
			$date_to2=substr($date_to2, 0, 10);
			$date_to2 = str_replace("-","",$date_to2);
			if($date_to2==''){
				$date_to2 = '47121231';
			}
			
			$max_from = max($date_from, $date_from2);
			$min_from = min($date_from, $date_from2);
			$max_to = max($date_to, $date_to2);
			$min_to = min($date_to, $date_to2);
			/* echo 'date_from: '.$date_from.'
';
			echo 'date_from2: '.$date_from2.'
';
			echo 'date_to: '.$date_to.'
';
			echo 'date_to2: '.$date_to2.'
';
			echo $max_from.'>'.$min_to.'&&'.$min_from.'<'.$max_to.'||
'; */
			if(($max_from > $min_to && $min_from < $max_to) == false){
				$beririsan++;
			}
//			echo $beririsan.'
//';
		}
	}
}

/* $y=1;
$minv_from=0;
$maxv_from=0;
$minv_to=0;
$maxv_to=0;
for ($x=0; $x<$countID-1; $x++){
	$date_from = $arrDateFrom[$x];
	if($date_from!=''){
		$date_from=substr($date_from, 0, 10);
		$date_from = str_replace("-","",$date_from);
	}
	$date_to = $arrDateTo[$x];
	$date_to=substr($date_to, 0, 10);
	$date_to = str_replace("-","",$date_to);
	if($date_to==''){
		$date_to = '47121231';
	}
	
	$date_from2 = $arrDateFrom[$y];
	if($date_from2!=''){
		$date_from2=substr($date_from2, 0, 10);
		$date_from2 = str_replace("-","",$date_from2);
	}
	$date_to2 = $arrDateTo[$y];
	$date_to2=substr($date_to2, 0, 10);
	$date_to2 = str_replace("-","",$date_to2);
	if($date_to2==''){
		$date_to2 = '47121231';
	}
	
	if($minv_from < $date_from){
		$minv_from = $date_from;
	}
	if($date_from > $maxv_from){
		$maxv_from = $date_from;
	}
	if($minv_to < $date_to){
		$minv_to = $date_to;
	}
	if($date_to > $maxv_to){
		$maxv_to = $date_to;
	}
	
	$max_from = max($date_from, $date_from2);
	$min_from = min($date_from, $date_from2);
	$max_to = max($date_to, $date_to2);
	$min_to = min($date_to, $date_to2);
	// echo 'date_from: '.$date_from.'#';
	// echo 'date_from2: '.$date_from2.'#';
	// echo 'date_to: '.$date_to.'#';
	// echo 'date_to2: '.$date_to2.'#';
	// echo $max_from.'>'.$min_to.'&&'.$min_from.'<'.$max_to.'||';
	if(($max_from > $min_to && $min_from < $max_to) == false){
		$beririsan++;
	}
	
	
	
	$y++;
} */

/* for ($x=0; $x<$countID; $x++){
	$date_from = $arrDateFrom[$x];
	if($date_from!=''){
		$date_from=substr($date_from, 0, 10);
	}
	
	$date_to = $arrDateTo[$x];
	if($date_to!=''){
		$date_to=substr($date_to, 0, 10);
		$queryTambah = " OR (TO_CHAR(DATE_FROM, 'YYYY-MM-DD') <= '$date_to' AND NVL(TO_CHAR(DATE_TO, 'YYYY-MM-DD'), '4712-12-31') >= '$date_to') 
		 OR (TO_CHAR(DATE_FROM, 'YYYY-MM-DD') >= '$date_from' AND NVL(TO_CHAR(DATE_TO, 'YYYY-MM-DD'), '4712-12-31') <= '$date_to') ";
	}else{
		$date_to = '4712-12-31';
		$queryTambah = " OR (TO_CHAR(DATE_FROM, 'YYYY-MM-DD') <= '4712-12-31' AND NVL(TO_CHAR(DATE_TO, 'YYYY-MM-DD'), '4712-12-31') >= '4712-12-31') 
		 OR (TO_CHAR(DATE_FROM, 'YYYY-MM-DD') >= '$date_from' AND NVL(TO_CHAR(DATE_TO, 'YYYY-MM-DD'), '4712-12-31') <= '4712-12-31') ";
	}
	
	$result = oci_parse($con, "SELECT COUNT(-1) 
	FROM MJ.MJ_M_SHIFT 
	WHERE ASSIGNMENT_ID=$nama
	AND ((TO_CHAR(DATE_FROM, 'YYYY-MM-DD') <= '$date_from' AND NVL(TO_CHAR(DATE_TO, 'YYYY-MM-DD'), '4712-12-31') >= '$date_from') 
	$queryTambah )
	AND STATUS = 'Y'
	AND ID <> $hdid
	");
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah2 = $rowJum[0];
	
	if($jumlah2 > 0){
		$jumlah++;
	}
} */
//echo $beririsan;exit;
if ($beririsan>0)
{
	$data = "Kesalahan pada tgl shift.";
} else {
	if($typeform=="tambah"){
		//insert
		for ($x=0; $x<$countID; $x++){
			$hdid = $arrID[$x];
			$date_from = $arrDateFrom[$x];
			$date_to = $arrDateTo[$x];
			
			if($hdid == ''){
				$hdid = 0;
			}
			
			if($date_from!='null'){
				$date_from=substr($date_from, 0, 10);
				$date_fromNum = str_replace("-"," ",$date_from);
				$date_from2 = "to_date('$date_from', 'YYYY-MM-DD')";
			}
			
			if($date_to!='null' || $date_to!=''){
				$date_to=substr($date_to, 0, 10);
				// $date_to = str_replace("T"," ",$date_to);
				$date_to2 = "to_date('$date_to', 'YYYY-MM-DD')";
			}else{
				$date_to2 = '';
			}
			
			$result = oci_parse($con, "SELECT COUNT(-1) 
				FROM MJ.MJ_M_SHIFT 
				WHERE ASSIGNMENT_ID=$nama
				AND ID=$hdid
				AND STATUS = 'Y'
			");
			oci_execute($result);
			$rowJum = oci_fetch_row($result);
			$jumlah = $rowJum[0];
			
			if($jumlah>0){
				// $result2 = oci_parse($con, "select max(mtt.tanggal) from mj.mj_t_timecard mtt
					// inner join per_assignments_f paf on mtt.person_id = paf.person_id
					// where paf.assignment_id = $nama
					// group by mtt.person_id
				// ");
				// oci_execute($result2);
				// $rowMax = oci_fetch_row($result2);
				// $maxDate = $rowMax[0];
				// if($date_fromNum > $maxDate){
					$sqlQuery = "UPDATE MJ.MJ_M_SHIFT SET SHIFT_ID = $arrShiftID[$x], DATE_FROM = $date_from2, DATE_TO = $date_to2, 
						LAST_UPDATED_BY = $user_id, LAST_UPDATED_DATE = SYSDATE 
						WHERE ASSIGNMENT_ID = $nama AND ID = $hdid";
					//echo $sqlQuery;exit;
					$result = oci_parse($con,$sqlQuery);
					oci_execute($result);
				//}
			}else{
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_SHIFT_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$idTabel = $row[0];
				$sqlQuery = "INSERT INTO MJ.MJ_M_SHIFT (ID, ASSIGNMENT_ID, SHIFT_ID, DATE_FROM, DATE_TO, CREATED_BY, CREATED_DATE) 
				VALUES ( $idTabel, $nama, $arrShiftID[$x], $date_from2, $date_to2, $user_id, SYSDATE)";
				//echo $sqlQuery;exit;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
				if($del == 0){
					$id_del = $idTabel;
				}else{
					$id_del = $id_del + ", " + $idTabel;
				}
				$del++;
			}
		}
		if($del != 0){
			$where_del = "and id not in($id_del)";
		}
		$sqlQuery = "DELETE FROM MJ.MJ_M_SHIFT WHERE ASSIGNMENT_ID = $nama AND ID NOT IN ($kumpulanID) $where_del";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		$data = "sukses";
	}
}

$result = array('success' => true,
				'results' => $jumlah,
				'rows' => $data
			);
echo json_encode($result);

?>