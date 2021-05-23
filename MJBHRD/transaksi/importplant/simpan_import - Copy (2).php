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
 if(isset($_SESSION['user_id']))
  {
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['username'];
	$emp_id = $_SESSION['emp_id'];
	$emp_name = $_SESSION['emp_name'];
	$io_id = $_SESSION['io_id'];
	$io_name = $_SESSION['io_name'];
	$loc_id = $_SESSION['loc_id'];
	$loc_name = $_SESSION['loc_name'];
	$org_id = $_SESSION['org_id'];
	$org_name = $_SESSION['org_name'];
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
$dataTgl='';
// if(isset($_POST['namaFile']))
// {
	// $namaFile=$_POST['namaFile']; 
	$namaFile='excel finger manyar.xls';
	
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
	$countRow = 9;//count($sheetData);
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
			$queryID = "SELECT COUNT(-1)
			FROM APPS.PER_PEOPLE_F B 
			INNER JOIN APPS.PER_ASSIGNMENTS_F D ON B.PERSON_ID = D.PERSON_ID
			INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=D.LOCATION_ID
			LEFT JOIN MJ.MJ_M_USERAPPROVAL_SPLBETON MMUS ON MMUS.PLANT_ID=D.LOCATION_ID
			WHERE B.HONORS=$dataId
			AND B.EFFECTIVE_END_DATE > SYSDATE 
			AND D.EFFECTIVE_END_DATE > SYSDATE";
			//echo $queryID;
			$resultID = oci_parse($con,$queryID);
			oci_execute($resultID);
			$rowID = oci_fetch_row($resultID);
			$countID = $rowID[0];
			if($countID >= 0){
				$queryID = "SELECT B.PERSON_ID
				, D.ORGANIZATION_ID
				, D.LOCATION_ID
				, HL.LOCATION_CODE
				, B.FULL_NAME
				, CASE MMUS.SPV_ID WHEN 0 THEN '' 
				ELSE (SELECT PPFS.FULL_NAME FROM APPS.PER_PEOPLE_F PPFS WHERE PPFS.PERSON_ID=MMUS.SPV_ID AND PPFS.EFFECTIVE_END_DATE > SYSDATE) END AS SPV
				, CASE MMUS.SPV_ID WHEN 0 THEN '' 
				ELSE (SELECT PPFS.EMAIL_ADDRESS FROM APPS.PER_PEOPLE_F PPFS WHERE PPFS.PERSON_ID=MMUS.SPV_ID AND PPFS.EFFECTIVE_END_DATE > SYSDATE) END AS EMAIL_SPV
				, CASE MMUS.MANAGER_ID WHEN 0 THEN '' 
				ELSE (SELECT PPFM.FULL_NAME FROM APPS.PER_PEOPLE_F PPFM WHERE PPFM.PERSON_ID=MMUS.MANAGER_ID AND PPFM.EFFECTIVE_END_DATE > SYSDATE) END AS MANAGER 
				, CASE MMUS.MANAGER_ID WHEN 0 THEN '' 
				ELSE (SELECT PPFM.EMAIL_ADDRESS FROM APPS.PER_PEOPLE_F PPFM WHERE PPFM.PERSON_ID=MMUS.MANAGER_ID AND PPFM.EFFECTIVE_END_DATE > SYSDATE) END AS EMAIL_MANAGER 
				FROM APPS.PER_PEOPLE_F B 
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON B.PERSON_ID = D.PERSON_ID
				INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=D.LOCATION_ID
				LEFT JOIN MJ.MJ_M_USERAPPROVAL_SPLBETON MMUS ON MMUS.PLANT_ID=D.LOCATION_ID
				WHERE B.HONORS=$dataId
				AND B.EFFECTIVE_END_DATE > SYSDATE 
				AND D.EFFECTIVE_END_DATE > SYSDATE";
				//echo $queryID;
				$resultID = oci_parse($con,$queryID);
				oci_execute($resultID);
				$rowID = oci_fetch_row($resultID);
				$vID = $rowID[0];
				$vORG_ID = $rowID[1];
				$vLOCATION_ID = $rowID[2];
				$vLOCATION_CODE = $rowID[3];
				$vFULL_NAME = $rowID[4];
				$vSPV = $rowID[5];
				$vEMAIL_SPV = $rowID[6];
				$vMANAGER = $rowID[7];
				$vEMAIL_MANAGER = $rowID[8];
				$dataId = $vID;
				$countData = 1;
				
				foreach($data as $data_key => $data_value) {
					if ($countData > $columnStart){
						if($countData%2 == 0){
							$Selisih=0;
							$JamKeluar = str_replace(':', '', $data_value);
							$JamKeluarAsli = $data_value;
							$queryHariIni = "SELECT TO_CHAR(TO_DATE('$newformat', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
							$resultHariIni = oci_parse($con,$queryHariIni);
							oci_execute($resultHariIni);
							$rowHariIni = oci_fetch_row($resultHariIni);
							$HariIni = $rowHariIni[0]; 
							
							$queryHariLibur = "SELECT COUNT(-1)
							FROM APPS.HXT_HOLIDAY_DAYS A
							, APPS.HXT_HOLIDAY_CALENDARS B
							WHERE A.HCL_ID=B.ID 
							AND B.EFFECTIVE_END_DATE>SYSDATE 
							AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD')='$newformat'";
							$resultHariLibur = oci_parse($con,$queryHariLibur);
							oci_execute($resultHariLibur);
							$rowHariLibur = oci_fetch_row($resultHariLibur);
							$HariLibur = $rowHariLibur[0]; 
							$HariIni = trim($HariIni);
							
							$queryCountMasuk = "SELECT COUNT(-1)
							FROM APPS.PER_PEOPLE_F A
							, APPS.PER_ALL_ASSIGNMENTS_F B
							, APPS.HXT_ADD_ASSIGN_INFO_F C
							, APPS.HXT_ROTATION_SCHEDULES D
							, APPS.HXT_WORK_SHIFTS_FMV E
							, APPS.HXT_SHIFTS F
							WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE>SYSDATE
							AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE>SYSDATE
							AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. EFFECTIVE_END_DATE>SYSDATE
							AND C.ROTATION_PLAN=D.RTP_ID AND D.START_DATE<SYSDATE 
							AND D.START_DATE=(SELECT MAX(START_DATE) FROM APPS.HXT_ROTATION_SCHEDULES WHERE START_DATE<SYSDATE)
							AND D.TWS_ID=E.TWS_ID AND E.MEANING='$HariIni'-- Hari absen
							AND E.SHT_ID=F.ID";
							$resultCountMasuk = oci_parse($con,$queryCountMasuk);
							oci_execute($resultCountMasuk);
							$rowCountMasuk = oci_fetch_row($resultCountMasuk);
							$CountMasuk = $rowCountMasuk[0]; 
							
							if($CountMasuk >= 1){
								$queryMasuk = "SELECT F.STANDARD_START + 5
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, APPS.HXT_ADD_ASSIGN_INFO_F C
								, APPS.HXT_ROTATION_SCHEDULES D
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE>SYSDATE
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE>SYSDATE
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. EFFECTIVE_END_DATE>SYSDATE
								AND C.ROTATION_PLAN=D.RTP_ID AND D.START_DATE<SYSDATE 
								AND D.START_DATE=(SELECT MAX(START_DATE) FROM APPS.HXT_ROTATION_SCHEDULES WHERE START_DATE<SYSDATE)
								AND D.TWS_ID=E.TWS_ID AND E.MEANING='$HariIni'-- Hari absen
								AND E.SHT_ID=F.ID";
								$resultMasuk = oci_parse($con,$queryMasuk);
								oci_execute($resultMasuk);
								$rowMasuk = oci_fetch_row($resultMasuk);
								$Masuk = $rowMasuk[0]; 
								$Keluar = $rowMasuk[1]; 
							} else {
								$Masuk = ''; 
								$Keluar = ''; 
							}
									
							if($HariIni != 'SUNDAY' && $HariLibur == 0){
								if ($JamKeluarAsli=='' || $JamMasukAsli==''){
									$queryCountKategori = "SELECT COUNT(-1)
									FROM MJ.MJ_T_SIK 
									WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
									AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$newformat'
									AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$newformat'
									AND STATUS_DOK = 'Approved'";
									//echo $queryCountKategori;
									$resultCountKategori = oci_parse($con,$queryCountKategori);
									oci_execute($resultCountKategori);
									$rowCountKategori = oci_fetch_row($resultCountKategori);
									$CountKategori = $rowCountKategori[0]; 
									
									if($CountKategori >= 1){
										$queryKategori = "SELECT KATEGORI
										FROM MJ.MJ_T_SIK 
										WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
										AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$newformat'
										AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$newformat'
										AND STATUS_DOK = 'Approved'";
										$resultKategori = oci_parse($con,$queryKategori);
										oci_execute($resultKategori);
										$rowKategori = oci_fetch_row($resultKategori);
										$Kategori = $rowKategori[0]; 
										
										if($Kategori == 'Cuti'){
											$queryCutiTahunan = "SELECT (CUTI_TAHUNAN) AS  FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$dataId";
											$resultCutiTahunan = oci_parse($con,$queryCutiTahunan);
											oci_execute($resultCutiTahunan);
											$rowCutiTahunan = oci_fetch_row($resultCutiTahunan);
											$CutiTahunan = $rowCutiTahunan[0]; 
											
											if($CutiTahunan == 0){
												$Status=477; //Ijin
											} else {
												$Status=480; //Cuti
												$CutiTahunan = $CutiTahunan - 1;
												$query = "UPDATE MJ.MJ_T_CUTI SET CUTI_TAHUNAN = $CutiTahunan WHERE PERSON_ID=$dataId";
												$result = oci_parse($con,$query);
												oci_execute($result);
											}
										} elseif ($Kategori == 'Sakit'){
											$queryCutiSakit = "SELECT (CUTI_SAKIT) AS  FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$dataId";
											$resultCutiSakit = oci_parse($con,$queryCutiSakit);
											oci_execute($resultCutiSakit);
											$rowCutiSakit = oci_fetch_row($resultCutiSakit);
											$CutiSakit = $rowCutiSakit[0]; 
											
											if($CutiSakit == 0){
												$Status=477; //Ijin
											} else {
												$Status=478; //Sakit
												$CutiSakit = $CutiSakit - 1;
												$query = "UPDATE MJ.MJ_T_CUTI SET CUTI_TAHUNAN = $CutiSakit WHERE PERSON_ID=$dataId";
												$result = oci_parse($con,$query);
												oci_execute($result);
											}
										} else {
											$Status=477; //Ijin
										} 
									} else {
										$Status=476; //Alpha
									}
								} else {								
									$ArrMasuk = explode(":", $JamMasukAsli);
									$vJamMasuk = $ArrMasuk[0];
									$vMenitMasuk = $ArrMasuk[1];
									$ArrKeluar = explode(":", $JamKeluarAsli);
									$vJamKeluar = $ArrKeluar[0];
									$vMenitKeluar = $ArrKeluar[1];
									$tempMenit = $vMenitKeluar - $vMenitMasuk;
									if ($tempMenit < 0){
										$tempMenit = (60 + $vMenitKeluar) - $vMenitMasuk;
										$tempJam = ($vJamKeluar - 1) - $vJamMasuk;
										$vTotalJam = $tempJam . ":" . $tempMenit;
									} else {
										$tempJam = $vJamKeluar - $vJamMasuk;
										$vTotalJam = $tempJam . ":" . $tempMenit;
									}
									
									if($CountMasuk >= 1){
										if($JamMasuk > $Masuk){
											$Status=479; //Terlambat
										} else {
											$Status=360; //Masuk
											if($vORG_ID == 81){
												if($tempJam > 8){
													$Status=536; //Lembur
													$vTotalLembur = ($tempJam - 8) . ":" . $tempMenit;
													SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $Keluar, $JamKeluarAsli, $vTotalLembur);
												}
											}
										}
									} else {
										$Status=360; //Masuk
										if($vORG_ID == 81){
											if($tempJam > 8){
												$Status=536;
												$vTotalLembur = ($tempJam - 8) . ":" . $tempMenit;
												SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, '', $JamKeluarAsli, $vTotalLembur);
											}
										}
									}
								}							
								echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . $Status . ", Selisih=" . $Selisih;
								echo "<br>";
								
								$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
								oci_execute($resultSeq);
								$rowHSeq = oci_fetch_row($resultSeq);
								$IdTimecard = $rowHSeq[0];
								
								$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE)";
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
							} else {
								if ($JamKeluar!='' && $JamMasuk!=''){
									$ArrMasuk = explode(":", $JamMasukAsli);
									$vJamMasuk = $ArrMasuk[0];
									$vMenitMasuk = $ArrMasuk[1];
									$ArrKeluar = explode(":", $JamKeluarAsli);
									$vJamKeluar = $ArrKeluar[0];
									$vMenitKeluar = $ArrKeluar[1];
									$tempMenit = $vMenitKeluar - $vMenitMasuk;
									if ($tempMenit < 0){
										$tempMenit = (60 + $vMenitKeluar) - $vMenitMasuk;
										$tempJam = ($vJamKeluar - 1) - $vJamMasuk;
										$vTotalJam = $tempJam . ":" . $tempMenit;
									} else {
										$tempJam = $vJamKeluar - $vJamMasuk;
										$vTotalJam = $tempJam . ":" . $tempMenit;
									}
									$Status=536; // Lembur
									if($CountMasuk >= 1){
										if($vORG_ID == 81){
											$vTotalLembur = $tempJam . ":" . $tempMenit;
											SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $JamMasukAsli, $JamKeluarAsli, $vTotalLembur);
										} else {
											$queryCountSPL = "SELECT COUNT(-1)
											FROM MJ.MJ_T_SPL MTS
											INNER JOIN MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
											WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE)
											AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD') >= '$newformat'
											AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD') <= '$newformat'
											AND MTSD.STATUS_DOK = 'Approved'";
											//echo $queryCountSPL;
											$resultCountSPL = oci_parse($con,$queryCountSPL);
											oci_execute($resultCountSPL);
											$rowCountSPL = oci_fetch_row($resultCountSPL);
											$CountSPL = $rowCountSPL[0];
											
											if($CountSPL >= 1){
												$Status=360; //Masuk
											} else {
												$Status=476; //Alpha
											}
										}
									} else {
										if($vORG_ID == 81){
											$vTotalLembur = $tempJam . ":" . $tempMenit;
											SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, '', $JamKeluarAsli, $vTotalLembur);
										} else {
											$queryCountSPL = "SELECT COUNT(-1)
											FROM MJ.MJ_T_SPL MTS
											INNER JOIN MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
											WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE)
											AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD') >= '$newformat'
											AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD') <= '$newformat'
											AND MTSD.STATUS_DOK = 'Approved'";
											//echo $queryCountSPL;
											$resultCountSPL = oci_parse($con,$queryCountSPL);
											oci_execute($resultCountSPL);
											$rowCountSPL = oci_fetch_row($resultCountSPL);
											$CountSPL = $rowCountSPL[0];
											
											if($CountSPL >= 1){
												$Status=360; //Masuk
											} else {
												$Status=476; //Alpha
											}
										}
									}
									echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . $Status . ", Selisih=" . $Selisih;
									echo "<br>";
									// $queryProcedure = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($dataId, '$newformat $JamMasukAsli:00', '$newformat $JamKeluarAsli:00', '$Status')";
									// echo $queryProcedure;
									// $resultProcedure = oci_parse($con,$queryProcedure);
									// oci_execute($resultProcedure);
									
									$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
									oci_execute($resultSeq);
									$rowHSeq = oci_fetch_row($resultSeq);
									$IdTimecard = $rowHSeq[0];
									
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE)";
									$result = oci_parse($con,$queryTimeCard);
									oci_execute($result);
								}
							}
						} else {
							$time = strtotime($dataTgl[$data_key]);
							$newformat = date('Y-m-d',$time);
							$JamMasuk = str_replace(':', '', $data_value);
							$JamMasukAsli = $data_value;
						}
					}
					$countData++;
				}
			}
		}
	}
	
	function SimpanSPL($con, $emp_id, $tglspl, $plant, $emp_name, $spv, $email_spv, $manager, $email_man, $jam_keluar_sys, $jam_keluar, $jam_lembur){
		$tglskr=date('Y-m-d'); 
		$tahunGenNo=substr($tglskr, 0, 4);
		$data="gagal";
		
		$query2 = "SELECT PAF.JOB_ID, PAF.POSITION_ID
        ,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1) PERUSAHAAN
        ,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPT
        ,CASE WHEN REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)='0' THEN 'MGR'
        ELSE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)
        END AS DIV
		FROM APPS.PER_ASSIGNMENTS_F PAF
			,APPS.PER_JOBS PJ
			,APPS.PER_POSITIONS PP
		WHERE PAF.PERSON_ID=$emp_id
		AND PAF.JOB_ID=PJ.JOB_ID
		AND PAF.POSITION_ID=PP.POSITION_ID
			AND PAF.EFFECTIVE_END_DATE > SYSDATE";
		$result2 = oci_parse($con,$query2);
		oci_execute($result2);
		$rowOU = oci_fetch_row($result2);
		$namePer = $rowOU[2];
		$nameDept = $rowOU[3];
		$nameDiv = $rowOU[4];
		
		$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumgen = $rowcount[0]; 
		
		if ($jumgen>0){
			$query = "SELECT LASTNO FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$rowGLastno = oci_fetch_row($result);
			$lastNo = $rowGLastno[0];
		} else {
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$gencountseq = $rowHSeq[0];
		
			$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE) VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '$namePer', '$nameDept', '$nameDiv', 'SPL')";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$lastNo = 0;
		} 
		
		$querycount = "SELECT TAHUN FROM MJ.MJ_M_GENERATENO WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$thnGen = $rowcount[0]; 
		
		if($thnGen!=$tahunGenNo){
			$lastNo = 0;
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo', TAHUN='$tahunGenNo' WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		} else {
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo' WHERE TEMP1='$namePer' AND TEMP2='$nameDept' AND TEMP3='$nameDiv' AND APPCODE='" . APPCODE . "' AND TRANSAKSI_KODE='SPL'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		}
		
		$jumno=strlen($lastNo);
		if($jumno==1){
			$nourut = "00000".$lastNo;
		} else if ($jumno==2){
			$nourut = "0000".$lastNo;
		} else if ($jumno==3){
			$nourut = "000".$lastNo;
		} else if ($jumno==4){
			$nourut = "00".$lastNo;
		} else if ($jumno==5){
			$nourut = "0".$lastNo;
		} else {
			$nourut = $lastNo;
		}
		
		$noSpl = "SPL/" . $namePer . "/" . $nameDept . "/" . $nameDiv . "/" . $tahunGenNo . "/" . $nourut; 
		
		if($spv=='' || $spv == '- Pilih -'){
			$tingkat=1;
		} else {
			$tingkat=0;
		}
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_SPL_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$hdid = $rowHSeq[0];
		
		$query = "INSERT INTO MJ.MJ_T_SPL (ID, NOMOR_SPL, TANGGAL_SPL, PLANT, PEMBUAT, SPV, EMAIL_SPV, MANAGER, EMAIL_MANAGER, STATUS, CREATED_BY, CREATED_DATE) VALUES ($hdid, '$noSpl', TO_DATE('$tglspl', 'YYYY-MM-DD'), '$plant', '$emp_name', '$spv', '$email_spv', '$manager', '$email_man', 1, '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		//end insert header SPL
		//insert detail SPL
		$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_T_SPL_DETAIL_SEQ.nextval FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		
		$query = "INSERT INTO MJ.MJ_T_SPL_DETAIL (ID, MJ_T_SPL_ID, NAMA, JAM_FROM, JAM_TO, TOTAL_JAM, PEKERJAAN, STATUS_DOK, TINGKAT, CREATED_BY, CREATED_DATE) VALUES ($seqD, $hdid, '$emp_name', '$jam_keluar_sys', '$jam_keluar', '$jam_lembur', 'Lembur Karyawan MJB', 'In process', $tingkat, '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);		
	}
?>