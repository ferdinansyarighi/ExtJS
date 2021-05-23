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
$kumpulanElement = "";
$hdid=0;
$queryTambah="";
$tglskr=date('Y-m-d'); 
$kumpulanID = "";
$nama_company = "";
$nama_plant = "";
$nama_grade = "";
$nama_periode = "";
if(isset($_POST['typeform']) || isset($_POST['status'])){
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	//$nama=str_replace("'", "''", $_POST['nama']);
	$group=$_POST['group'];
	
	$company=$_POST['company'];
	if($company == 'All'){
		$company = 'null';
		$nama_company = 'ALL';
	}else{
		$queryCompany = oci_parse($con, "SELECT MP.ORGANIZATION_CODE
			FROM APPS.HR_ORGANIZATION_UNITS HOU
				INNER JOIN APPS.MTL_PARAMETERS MP ON HOU.ORGANIZATION_ID = MP.ORGANIZATION_ID
			WHERE (NAME LIKE 'PT%' or NAME LIKE '%Group%' or name like '%Jaya Transport%') 
			and hou.organization_id = $company
		");
		oci_execute($queryCompany);
		$rowCom = oci_fetch_row($queryCompany);
		$nama_company = $rowCom[0];
	}
	
	$plant=$_POST['plant'];
	if($plant == 'All'){
		$plant = 'null';
		$nama_plant = 'ALL';
	}else{
		$queryPlant = oci_parse($con, "select address_line_3 from hr_locations
			where location_id = $plant
		");
		oci_execute($queryPlant);
		$rowPl = oci_fetch_row($queryPlant);
		$nama_plant = $rowPl[0];
	}
	
	$grade=$_POST['grade'];
	if($grade == 'All'){
		$grade = 'null';
		$nama_grade = 'ALL';
	}else{
		$queryGrade = oci_parse($con, "SELECT DISTINCT REGEXP_SUBSTR(NAME, '[^.]+', 1, 1) GRADE_NAME 
			FROM APPS.PER_GRADES PG 
			WHERE SYSDATE BETWEEN PG.DATE_FROM AND NVL(PG.DATE_TO, SYSDATE)
			and PG.GRADE_ID = $grade
		");
		oci_execute($queryGrade);
		$rowGrd = oci_fetch_row($queryGrade);
		$nama_grade = $rowGrd[0];
	}
	
	
	$periode=$_POST['periode'];
	$nama_periode = substr($periode, 0, 1);
	
	$status=$_POST['status'];
	if($status == 'false'){
		$status = 'N';
	}else{
		$status = 'Y';
	}
	$arrTransID=json_decode($_POST['arrTransID']);
	$arrDefault=json_decode($_POST['arrDefault']);
	$arrSatuan=json_decode($_POST['arrSatuan']);
	if($hdid == ''){
		$hdid = 0;
	} 
	
	$tipe_lembur=$_POST['tipe_lembur'];
	if($tipe_lembur == 'All'){
		$nama_tipe_lembur = 'ALL';
	}else if($tipe_lembur == 'OT HIDUP'){
		$nama_tipe_lembur = 'OTH';
	}else{
		$nama_tipe_lembur = 'OTM';
	}
	
	$nama=$group.".".$nama_company.".".$nama_plant.".".$nama_grade.".".$nama_periode.".".$nama_tipe_lembur;
}

//end deklarasi variable
$countID = count($arrTransID);
//echo $countID;exit;
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.MJ_M_GROUP_ELEMENT 
WHERE NAMA_GROUP='$nama'
AND STATUS = 'Y'
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0];
if ($jumlah>0)
{
	$data = "Group element tersebut sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GROUP_ELEMENT_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$idTabel = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_M_GROUP_ELEMENT (ID, NAMA_GROUP, GRUP, COMPANY, PLANT, GRADE, PERIODE_GAJI, TIPE_LEMBUR, STATUS, CREATED_BY, CREATED_DATE) 
		VALUES ( $idTabel,'$nama', '$group', $company, $plant, $grade, '$periode', '$tipe_lembur', '$status', $user_id, SYSDATE)";
		//echo $sqlQuery;exit;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		for ($x=0; $x<$countID; $x++){
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GROUP_ELEMENT_DETAIL_SEQ.nextval FROM dual");
			oci_execute($resultSeq);
			$row = oci_fetch_row($resultSeq);
			$idTabel_detail = $row[0];
			$sqlQuery = "INSERT INTO MJ.MJ_M_GROUP_ELEMENT_DETAIL (ID, ID_GROUP, ID_ELEMENT, DEFAULT_VALUE, SATUAN, CREATED_BY, CREATED_DATE) 
			VALUES ( $idTabel_detail, $idTabel, $arrTransID[$x], $arrDefault[$x], '$arrSatuan[$x]', $user_id, SYSDATE)";
			//echo $sqlQuery;exit;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
		}
		$data = "sukses";
	} else {
		for ($x=0; $x<$countID; $x++){
			if($x == 0){
				$kumpulanID = $arrTransID[$x];
			}else{
				$kumpulanID = $kumpulanID.", ".$arrTransID[$x];
			}
		}
		
		$y = 0;
		$z = 0;
		$kumpulanIdDel = '';
		$kumpulanIdTidakBisaDel = '';
		$sqlQuery = "SELECT ID FROM MJ.MJ_M_GROUP_ELEMENT_DETAIL WHERE ID_GROUP = $hdid AND ID_ELEMENT NOT IN ($kumpulanID) ";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result); 
		while($row = oci_fetch_row($result))
		{
			$id_element = $row[0];
			$resultCek = oci_parse($con,"SELECT COUNT(-1) FROM MJ.MJ_M_LINK_GROUP_DETAIL WHERE ID_GROUP_DETAIL = '$id_element' AND VALUE != 0");
			oci_execute($resultCek);
			$rowCek = oci_fetch_row($resultCek);
			$ada_linkgroup = $rowCek[0];
			
			if($ada_linkgroup == 0){
				if($y == 0){
					$kumpulanIdDel = $id_element;
				}else{
					$kumpulanIdDel = $kumpulanIdDel.", ".$id_element;
				}
			}else{
				if($z == 0){
					$kumpulanIdTidakBisaDel = $id_element;
				}else{
					$kumpulanIdTidakBisaDel = $kumpulanIdTidakBisaDel.", ".$id_element;
				}
			}
		} 
		
		$w = 0;
		if($kumpulanIdTidakBisaDel != ''){
			$data = "tidakBisaDel";
			$sqlQuery = "SELECT EGM.NAMA_ELEMENT FROM MJ.MJ_M_GROUP_ELEMENT_DETAIL MGED
				INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MGED.ID_ELEMENT = EGM.ID 
				where MGED.ID in($kumpulanIdTidakBisaDel)";
			//echo $sqlQuery;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result); 
			while($row = oci_fetch_row($result))
			{
				if($w == 0){
					$kumpulanElement = $row[0];
				}else{
					$kumpulanElement = $kumpulanElement.", ".$row[0];
				}
			}
		}else if($kumpulanIdDel != ''){
			$sqlQuery = "DELETE FROM MJ.MJ_M_LINK_GROUP_DETAIL WHERE ID_GROUP_DETAIL IN ($kumpulanIdDel) AND VALUE = 0";
			//echo $sqlQuery;exit;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
		}
		
		if($data != "tidakBisaDel"){
			//update
			$sqlQuery = "UPDATE MJ.MJ_M_GROUP_ELEMENT SET NAMA_GROUP='$nama', GRUP='$group', COMPANY=$company, PLANT=$plant, GRADE=$grade, PERIODE_GAJI='$periode', TIPE_LEMBUR='$tipe_lembur', STATUS='$status', LAST_UPDATED_BY=$user_id, LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
			
			for ($x=0; $x<$countID; $x++){	
				$result = oci_parse($con, "SELECT COUNT(-1) 
				FROM MJ.MJ_M_GROUP_ELEMENT_DETAIL 
				WHERE ID_GROUP = $hdid
				AND ID_ELEMENT = $arrTransID[$x]
				");
				oci_execute($result);
				$rowJum = oci_fetch_row($result);
				$jumlah = $rowJum[0]; 
				
				if ($jumlah > 0){
					$sqlQuery = "UPDATE MJ.MJ_M_GROUP_ELEMENT_DETAIL SET DEFAULT_VALUE = $arrDefault[$x], SATUAN = '$arrSatuan[$x]', LAST_UPDATED_BY = $user_id, LAST_UPDATED_DATE = SYSDATE WHERE ID_GROUP = $hdid AND ID_ELEMENT = $arrTransID[$x]";
					//echo $sqlQuery;exit;
					$result = oci_parse($con,$sqlQuery);
					oci_execute($result);
				}else{					
					$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GROUP_ELEMENT_DETAIL_SEQ.nextval FROM dual");
					oci_execute($resultSeq);
					$row = oci_fetch_row($resultSeq);
					$idTabel_detail = $row[0];
					
					$sqlQuery = "INSERT INTO MJ.MJ_M_GROUP_ELEMENT_DETAIL (ID, ID_GROUP, ID_ELEMENT, DEFAULT_VALUE, SATUAN, CREATED_BY, CREATED_DATE) 
					VALUES ( $idTabel_detail, $hdid, $arrTransID[$x], $arrDefault[$x], '$arrSatuan[$x]', $user_id, SYSDATE)";
					//echo $sqlQuery;exit;
					$result = oci_parse($con,$sqlQuery);
					oci_execute($result);
				}
				
				if($x == 0){
					$kumpulanID = $arrTransID[$x];
				}else{
					$kumpulanID = $kumpulanID.", ".$arrTransID[$x];
				}
			}
			//echo $kumpulanID;exit;
			$sqlQuery = "DELETE FROM MJ.MJ_M_GROUP_ELEMENT_DETAIL WHERE ID_GROUP = $hdid AND ID_ELEMENT NOT IN ($kumpulanID) ";
			//echo $sqlQuery;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
			
			$data = "sukses";
		}
	}	
}

$result = array('success' => true,
				'results' => $hdid,
				'element' => $kumpulanElement,
				'rows' => $data
			);
echo json_encode($result);

?>