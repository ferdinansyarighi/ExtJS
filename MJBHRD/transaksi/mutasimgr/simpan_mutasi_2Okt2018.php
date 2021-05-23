<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 
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
$tahunGenNo=substr($tglskr, 0, 4);
$bulanGenNo=substr($tglskr, 6, 2);
$data="gagal";
$hdid="";
$typeform="";
$tingkat=0;
$vPersonID=0;
$noReq="";
$Keputusan = "";

if(isset($_POST['hdid']))
{
  	$hdid=$_POST['hdid'];
  	$setuju=$_POST['setuju'];
  	$note=$_POST['note'];
	
	if ($setuju=='Setuju') {
		$Keputusan = 'Approved';
	} else {
		$Keputusan = 'Disapproved';
	}	
}

	$queryTingkat = "SELECT MTM.TINGKAT, MTM.CREATED_BY
	FROM MJ.MJ_T_MUTASI MTM
	WHERE ID=$hdid";
	// echo $queryTingkat;
	$resultTingkat = oci_parse($con,$queryTingkat);
	oci_execute($resultTingkat);
	while($rowTingkat = oci_fetch_row($resultTingkat))
	{
		$tingkat=$rowTingkat[0];
		$pembuat=$rowTingkat[1];
			
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$hdidApp = $rowHSeq[0];
		
		$tingkat++;
		
		$query = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
		VALUES ($hdidApp, " . APPCODE . ", '$emp_id', $hdid, 'MPD', $tingkat, '$Keputusan', '$note', '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
	}

	//update header MPD
	if ($setuju=='Setuju') {
		$queryUpdate = "UPDATE MJ.MJ_T_MUTASI SET NOTE_PERSETUJUAN = '$note', TINGKAT=$tingkat, LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
	} else {
		$queryPosisi = "SELECT COUNT(-1)
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
		AND (PP.NAME LIKE '%MGR%')
		AND PPF.PERSON_ID=$pembuat";
		$resultPosisi = oci_parse($con,$queryPosisi);
		oci_execute($resultPosisi);
		$rowPosisi = oci_fetch_row($resultPosisi);
		$jumPosisi = $rowPosisi[0]; 
		
		if ($jumPosisi > 0){
			$tingkat = 1;
		} else {
			$tingkat = 0;
		}
		
		$queryUpdate = "UPDATE MJ.MJ_T_MUTASI SET STATUS_DOK = 'Disapproved', NOTE_PERSETUJUAN = '$note', TINGKAT=$tingkat, LAST_UPDATED_BY='$emp_id', LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid";
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
	}
	//end update header MPD
	
	$data="sukses";

$querydata = "SELECT ID || '|' || NO_REQUEST FROM MJ.MJ_T_MUTASI WHERE ID=$hdid ";
$resultdata = oci_parse($con,$querydata);
oci_execute($resultdata);
$rowdata = oci_fetch_row($resultdata);
$dataNoSIK = $rowdata[0]; 


$result = array('success' => true,
				'results' => $dataNoSIK,
				'rows' => $data
			);
echo json_encode($result);







?>