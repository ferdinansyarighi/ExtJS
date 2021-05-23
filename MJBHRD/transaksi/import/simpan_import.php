<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 include 'fungsiTimecardimport.php';
 
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
  
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$namaFile=""; 
$data="gagal";
$data_value = '';
$HariLibur=0;
$HariIni='';
$Status='';
$DataTgl='';
$JamMasuk=0;
$JamKeluar=0;
$dataId =0;
$dataPersonId =0;
$dataTgl='';
if(isset($_POST['namaFile']))
{
	$namaFile=$_POST['namaFile']; 
	//$namaFile='excel finger manyar.xls';
	
	error_reporting(E_ALL);
	include 'PHPExcel/IOFactory.php';
	$objReader = new PHPExcel_Reader_Excel5();
	$objPHPExcel = $objReader->load($namaFile);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$countValue = 0;
	$rowStart = -1;
	$rowTgl = 0;
	$columnID = "";
	$columnName = "";
	$countRow = count($sheetData);
	$dataConvertColumn = array("A", "B", "C", "D", "E");
	//echo $countRow;
	for($x=1; $x<=$countRow; $x++){
		$countColumn = 0;
		$tempDate = "";
		$data = $sheetData[$x];
		foreach($data as $data_key => $data_value) {
			if ($data_value == 'No.' && $rowStart==-1){
				$rowStart = $x + 2;
				$rowTgl = $x;
				$columnStart = $countColumn + 4;
			} elseif ($data_value == "Fingerprint ID" && $columnID==""){
				$columnID = $data_key;
			} elseif ($data_value == "Employee Name" && $columnName==""){
				$columnName = $data_key;
			}
			$countColumn++;
		}
		if($x >= $rowStart && $rowStart!=-1){
			$dataTgl = $sheetData[$rowTgl];
			$dataId = $data[$columnID];
			//echo $dataId;
			if($dataId != ''){
				$queryID = "SELECT COUNT(-1)
				FROM APPS.PER_PEOPLE_F B 
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON B.PERSON_ID = D.PERSON_ID
				INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=D.LOCATION_ID
				LEFT JOIN MJ.MJ_M_USERAPPROVAL_SPLBETON MMUS ON MMUS.PLANT_ID=D.LOCATION_ID
				WHERE B.HONORS='$dataId'
				AND B.EFFECTIVE_END_DATE > SYSDATE 
				AND D.EFFECTIVE_END_DATE > SYSDATE";
				//echo $queryID;
				$resultID = oci_parse($con,$queryID);
				oci_execute($resultID);
				$rowID = oci_fetch_row($resultID);
				$countID = $rowID[0];
				if($countID >= 1){
					$queryPersonID = "SELECT B.PERSON_ID
					FROM APPS.PER_PEOPLE_F B 
					INNER JOIN APPS.PER_ASSIGNMENTS_F D ON B.PERSON_ID = D.PERSON_ID
					INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=D.LOCATION_ID
					LEFT JOIN MJ.MJ_M_USERAPPROVAL_SPLBETON MMUS ON MMUS.PLANT_ID=D.LOCATION_ID
					WHERE B.HONORS='$dataId'
					AND B.EFFECTIVE_END_DATE > SYSDATE 
					AND D.EFFECTIVE_END_DATE > SYSDATE";
					// echo $queryPersonID;exit;
					$resultPersonID = oci_parse($con,$queryPersonID);
					oci_execute($resultPersonID);
					$rowPersonId = oci_fetch_row($resultPersonID);
					$dataPersonId = $rowPersonId[0];
					
					$countData = 1;
					//echo 
					foreach($data as $data_key => $data_value) {
						if ($countData > $columnStart){
							if($countData%2 == 0){
								$JamKeluar = str_replace(':', '', $data_value);
								$JamKeluarAsli = $data_value;
								$queryDelID = "DELETE
								FROM MJ.MJ_T_TIMECARD 
								WHERE PERSON_ID=$dataPersonId
								AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') = '$newformat'";
								//echo $queryDelID;
								$resultDelID = oci_parse($con,$queryDelID);
								oci_execute($resultDelID); 
								InsertTimeCard($con, $dataId, $newformat, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, $user_id);
							} else {
								$time = strtotime($dataTgl[$data_key]);
								//echo $dataTgl[$data_key];exit;
								$newformat = date('Y-m-d',$time);
								//echo $newformat;exit;
								$JamMasuk = str_replace(':', '', $data_value);
								$JamMasukAsli = $data_value;
							}
						}
						$countData++;
					}
				}
			}
		}
	}
}	
	// function SimpanSPL($con, $emp_id, $tglspl, $plant, $emp_name, $spv, $email_spv, $manager, $email_man, $jam_keluar_sys, $jam_keluar, $jam_lembur){
		// $tglskr=date('Y-m-d'); 
		// $tahunGenNo=substr($tglskr, 0, 4);
		// $data="gagal";
		
		// $query2 = "SELECT PAF.JOB_ID, PAF.POSITION_ID
        // ,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1) PERUSAHAAN
        // ,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPT
        // ,CASE WHEN REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)='0' THEN 'MGR'
        // ELSE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)
        // END AS DIV
		// FROM APPS.PER_ASSIGNMENTS_F PAF
			// ,APPS.PER_JOBS PJ
			// ,APPS.PER_POSITIONS PP
		// WHERE PAF.PERSON_ID=$emp_id
		// AND PAF.JOB_ID=PJ.JOB_ID
		// AND PAF.POSITION_ID=PP.POSITION_ID
			// AND PAF.EFFECTIVE_END_DATE > SYSDATE";
		// $result2 = oci_parse($con,$query2);
		// oci_execute($result2);
		// $rowOU = oci_fetch_row($result2);
		// $namePer = $rowOU[2];
		// $nameDept = $rowOU[3];
		// $nameDiv = $rowOU[4];
		
		// $queryDepartment = "SELECT REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3)
		// FROM APPS.PER_PEOPLE_F PPF
		// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		// INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
		// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$emp_name'";
		// $resultDepartment = oci_parse($con,$queryDepartment);
		// oci_execute($resultDepartment);
		// $rowDepartment = oci_fetch_row($resultDepartment);
		// $vDepartment = $rowDepartment[0]; 
		
		// $querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
		// $resultcount = oci_parse($con,$querycount);
		// oci_execute($resultcount);
		// $rowcount = oci_fetch_row($resultcount);
		// $jumgen = $rowcount[0]; 
		
		// if ($jumgen>0){
			// $query = "SELECT LASTNO FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
			// $result = oci_parse($con,$query);
			// oci_execute($result);
			// $rowGLastno = oci_fetch_row($result);
			// $lastNo = $rowGLastno[0];
		// } else {
			// $resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			// oci_execute($resultSeq);
			// $rowHSeq = oci_fetch_row($resultSeq);
			// $gencountseq = $rowHSeq[0];
		
			// $query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE) VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '$namePer', '$nameDept', '$nameDiv', 'SPL')";
			// $result = oci_parse($con,$query);
			// oci_execute($result);
			// $lastNo = 0;
		// } 
		
		// $querycount = "SELECT TAHUN FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
		// $resultcount = oci_parse($con,$querycount);
		// oci_execute($resultcount);
		// $rowcount = oci_fetch_row($resultcount);
		// $thnGen = $rowcount[0]; 
		
		// if($thnGen!=$tahunGenNo){
			// $lastNo = 0;
			// $lastNo=$lastNo+1;
			// $queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo', TAHUN='$tahunGenNo' WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
			// $resultLast = oci_parse($con,$queryLast);
			// oci_execute($resultLast);
		// } else {
			// $lastNo=$lastNo+1;
			// $queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo' WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
			// $resultLast = oci_parse($con,$queryLast);
			// oci_execute($resultLast);
		// }
		
		// $jumno=strlen($lastNo);
		// if($jumno==1){
			// $nourut = "00000".$lastNo;
		// } else if ($jumno==2){
			// $nourut = "0000".$lastNo;
		// } else if ($jumno==3){
			// $nourut = "000".$lastNo;
		// } else if ($jumno==4){
			// $nourut = "00".$lastNo;
		// } else if ($jumno==5){
			// $nourut = "0".$lastNo;
		// } else {
			// $nourut = $lastNo;
		// }
		
		// $noSpl = "SPL/" . $namePer . "/" . $nameDept . "/" . $nameDiv . "/" . $tahunGenNo . "/" . $nourut; 
		
		// if($spv=='' || $spv == '- Pilih -'){
			// $tingkat=1;
		// } else {
			// $tingkat=0;
		// }
		// $resultSeq = oci_parse($con,"SELECT MJ.MJ_T_SPL_SEQ.nextval FROM DUAL");
		// oci_execute($resultSeq);
		// $rowHSeq = oci_fetch_row($resultSeq);
		// $hdid = $rowHSeq[0];
		
		// $query = "INSERT INTO MJ.MJ_T_SPL (ID, NOMOR_SPL, TANGGAL_SPL, PLANT, PEMBUAT, SPV, EMAIL_SPV, MANAGER, EMAIL_MANAGER, STATUS, CREATED_BY, CREATED_DATE) VALUES ($hdid, '$noSpl', TO_DATE('$tglspl', 'YYYY-MM-DD'), '$plant', '$emp_name', '$spv', '$email_spv', '$manager', '$email_man', 1, '$emp_name', SYSDATE)";
		// //echo $query;
		// $result = oci_parse($con,$query);
		// oci_execute($result);
		// //end insert header SPL
		// //insert detail SPL
		// $resultDetSeq = oci_parse($con,"SELECT MJ.MJ_T_SPL_DETAIL_SEQ.nextval FROM DUAL");
		// oci_execute($resultDetSeq);
		// $rowDSeq = oci_fetch_row($resultDetSeq);
		// $seqD = $rowDSeq[0];
		
		// $query = "INSERT INTO MJ.MJ_T_SPL_DETAIL (ID, MJ_T_SPL_ID, NAMA, DEPARTEMEN, JAM_FROM, JAM_TO, TOTAL_JAM, PEKERJAAN, STATUS_DOK, TINGKAT, CREATED_BY, CREATED_DATE) VALUES ($seqD, $hdid, '$emp_name', '$vDepartment', '$jam_keluar_sys', '$jam_keluar', '$jam_lembur', 'Lembur Karyawan MJB', 'In process', $tingkat, '$emp_name', SYSDATE)";
		// //echo $query;
		// $result = oci_parse($con,$query);
		// oci_execute($result);		
	// }

?>