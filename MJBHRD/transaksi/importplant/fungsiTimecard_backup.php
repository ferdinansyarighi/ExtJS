<?PHP
function InsertTimeCard($con, $dataId, $newformat, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, $user_id){
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
	
	$Selisih=0;
	$queryHariIni = "SELECT TO_CHAR(TO_DATE('$newformat', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
	$resultHariIni = oci_parse($con,$queryHariIni);
	oci_execute($resultHariIni);
	$rowHariIni = oci_fetch_row($resultHariIni);
	$HariIni = $rowHariIni[0]; 
	$HariIni = trim($HariIni);
	
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
	AND D.TWS_ID=E.TWS_ID AND UPPER(E.MEANING)='$HariIni'-- Hari absen
	AND E.SHT_ID=F.ID";
	$resultCountMasuk = oci_parse($con,$queryCountMasuk);
	oci_execute($resultCountMasuk);
	$rowCountMasuk = oci_fetch_row($resultCountMasuk);
	$CountMasuk = $rowCountMasuk[0]; 
	
	if($CountMasuk >= 1){
		$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
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
		AND D.TWS_ID=E.TWS_ID AND UPPER(E.MEANING)='$HariIni'-- Hari absen
		AND E.SHT_ID=F.ID";
		$resultMasuk = oci_parse($con,$queryMasuk);
		oci_execute($resultMasuk);
		$rowMasuk = oci_fetch_row($resultMasuk);
		$Terlambat = $rowMasuk[0]; 
		$Masuk = $rowMasuk[1]; 
		$Keluar = $rowMasuk[2]; 
		$Hours = $rowMasuk[3]; 
	} else {
		$Terlambat = ''; 
		$Masuk = ''; 
		$Keluar = ''; 
	}
	
	if($HariIni != 'SUNDAY' && $HariLibur == 0){
		if ($JamKeluarAsli=='' || $JamMasukAsli==''){
			if($CountMasuk >= 1){
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
					$queryKategori = "SELECT ID, KATEGORI, IJIN_KHUSUS
					FROM MJ.MJ_T_SIK 
					WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
					AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$newformat'
					AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$newformat'
					AND STATUS_DOK = 'Approved'";
					$resultKategori = oci_parse($con,$queryKategori);
					oci_execute($resultKategori);
					$rowKategori = oci_fetch_row($resultKategori);
					$IdSIK = $rowKategori[0]; 
					$Kategori = $rowKategori[1]; 
					$IjinKhusus = $rowKategori[2]; 
					
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
					} elseif ($Kategori == 'Terlambat'){
						$Status=360; //Masuk
						$xStatus=479; //Terlambat
						if ($JamMasukAsli==''){
							$SubMasukJam = substr($Terlambat, 0, -2);
							$SubMasukMenit = substr($Terlambat, -2);
							
							if(strlen($SubMasukJam) == 1){
								$SubMasukJam = "0".$SubMasukJam;
							}
							$xJamMasukAsli = $SubMasukJam . ":" . $SubMasukMenit;
						
							$querySIK = "SELECT JAM_TO MJ.MJ_T_SIK WHERE ID=$IdSIK";
							$resultSIK = oci_parse($con,$querySIK);
							oci_execute($resultSIK);
							$rowSIK = oci_fetch_row($resultSIK);
							$TempJamKeluarAsli = $rowSIK[0]; 
							$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
							$vTempJamKeluar = $ArrTempKeluar[0];
							$vTempMenitKeluar = $ArrTempKeluar[1];
							
							if(strlen($vTempJamKeluar) == 1){
								$vTempJamKeluar = "0".$vTempJamKeluar;
							}
							if(strlen($vTempMenitKeluar) == 1){
								$vTempMenitKeluar = "0".$vTempMenitKeluar;
							}
							$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
							
							$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
							oci_execute($resultSeq);
							$rowHSeq = oci_fetch_row($resultSeq);
							$IdTimecard = $rowHSeq[0];
							
							$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
							$result = oci_parse($con,$queryTimeCard);
							oci_execute($result);
							
							$JamMasukAsli = $xJamKeluarAsli;
						}
					} elseif ($Kategori == 'Ijin'){
						if($IjinKhusus != ''){
							$Status = 556; //Ijin Khusus
						} else {
							$Status = 477; //Ijin
						}
						
						if($IjinKhusus == 'LUPA ABSEN DATANG'){
							if($JamMasuk == ''){
								$querySIK = "SELECT JAM_FROM FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
								$resultSIK = oci_parse($con,$querySIK);
								oci_execute($resultSIK);
								$rowSIK = oci_fetch_row($resultSIK);
								$TempJamMasukAsli = $rowSIK[0]; 
								$ArrTempMasuk = explode(":", $TempJamMasukAsli);
								$vTempJamMasuk = $ArrTempMasuk[0];
								$vTempMenitMasuk = $ArrTempMasuk[1];
								$TempMasukLength = strlen($vTempMenitMasuk);
								
								if(strlen($vTempJamMasuk) == 1){
									$vTempJamMasuk = "0".$vTempJamMasuk;
								}
								if(strlen($vTempMenitMasuk) == 1){
									$vTempMenitMasuk = "0".$vTempMenitMasuk;
								}
								$JamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
							}
							$Status=360; //Masuk
						}
						if($IjinKhusus == 'LUPA ABSEN PULANG'){
							if($JamKeluar == ''){
								$querySIK = "SELECT JAM_TO FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
								$resultSIK = oci_parse($con,$querySIK);
								oci_execute($resultSIK);
								$rowSIK = oci_fetch_row($resultSIK);
								$TempJamKeluarAsli = $rowSIK[0]; 
								$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
								$vTempJamKeluar = $ArrTempKeluar[0];
								$vTempMenitKeluar = $ArrTempKeluar[1];
								
								if(strlen($vTempJamKeluar) == 1){
									$vTempJamKeluar = "0".$vTempJamKeluar;
								}
								if(strlen($vTempMenitKeluar) == 1){
									$vTempMenitKeluar = "0".$vTempMenitKeluar;
								}
								$JamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
							}
							$Status=360; //Masuk
						}
						if($IjinKhusus == 'TIDAK ABSEN KARENA URUSAN KANTOR'){
							if($JamMasuk == ''){
								$querySIK = "SELECT JAM_FROM FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
								$resultSIK = oci_parse($con,$querySIK);
								oci_execute($resultSIK);
								$rowSIK = oci_fetch_row($resultSIK);
								$TempJamMasukAsli = $rowSIK[0]; 
								$ArrTempMasuk = explode(":", $TempJamMasukAsli);
								$vTempJamMasuk = $ArrTempMasuk[0];
								$vTempMenitMasuk = $ArrTempMasuk[1];
								$TempMasukLength = strlen($vTempMenitMasuk);
								
								if(strlen($vTempJamMasuk) == 1){
									$vTempJamMasuk = "0".$vTempJamMasuk;
								}
								if(strlen($vTempMenitMasuk) == 1){
									$vTempMenitMasuk = "0".$vTempMenitMasuk;
								}
								$JamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
							}
							if($JamKeluar == ''){
								$querySIK = "SELECT JAM_TO FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
								$resultSIK = oci_parse($con,$querySIK);
								oci_execute($resultSIK);
								$rowSIK = oci_fetch_row($resultSIK);
								$TempJamKeluarAsli = $rowSIK[0]; 
								$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
								$vTempJamKeluar = $ArrTempKeluar[0];
								$vTempMenitKeluar = $ArrTempKeluar[1];
								
								if(strlen($vTempJamKeluar) == 1){
									$vTempJamKeluar = "0".$vTempJamKeluar;
								}
								if(strlen($vTempMenitKeluar) == 1){
									$vTempMenitKeluar = "0".$vTempMenitKeluar;
								}
								$JamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
							}
							$Status=360; //Masuk
						}
						if($IjinKhusus == 'SETENGAH HARI'){
							$xStatus = 556; //Ijin Khusus
							$Status=360; //Masuk
							$querySIK = "SELECT JAM_FROM, JAM_TO MJ.MJ_T_SIK WHERE ID=$IdSIK";
							$resultSIK = oci_parse($con,$querySIK);
							oci_execute($resultSIK);
							$rowSIK = oci_fetch_row($resultSIK);
							$TempJamMasukAsli = $rowSIK[0]; 
							$TempJamKeluarAsli = $rowSIK[1]; 
							$ArrTempMasuk = explode(":", $TempJamMasukAsli);
							$ArrTempKeluar = explode(":", $TempJamMasukAsli);
							$vTempJamMasuk = $ArrTempMasuk[0];
							$vTempMenitMasuk = $ArrTempMasuk[1];
							$vTempJamKeluar = $ArrTempKeluar[0];
							$vTempMenitKeluar = $ArrTempKeluar[1];
							if(strlen($vTempJamMasuk) == 1){
								$vTempJamMasuk = "0".$vTempJamMasuk;
							}
							if(strlen($vTempMenitMasuk) == 1){
								$vTempMenitMasuk = "0".$vTempMenitMasuk;
							}
							$xJamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
							if(strlen($vTempJamKeluar) == 1){
								$vTempJamKeluar = "0".$vTempJamKeluar;
							}
							if(strlen($vTempMenitKeluar) == 1){
								$vTempMenitKeluar = "0".$vTempMenitKeluar;
							}
							$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
							if ($JamMasukAsli==''){
								$zStatus=479; //Terlambat
								
								$SubMasukJam = substr($Terlambat, 0, -2);
								$SubMasukMenit = substr($Terlambat, -2);
								
								if(strlen($SubMasukJam) == 1){
									$SubMasukJam = "0".$SubMasukJam;
								}
								$zJamMasukAsli = $SubMasukJam . ":" . $SubMasukMenit;
								
								$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
								oci_execute($resultSeq);
								$rowHSeq = oci_fetch_row($resultSeq);
								$IdTimecard = $rowHSeq[0];
								
								$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$zJamMasukAsli', '$xJamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
							}
							
							$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
							oci_execute($resultSeq);
							$rowHSeq = oci_fetch_row($resultSeq);
							$IdTimecard = $rowHSeq[0];
							
							$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
							$result = oci_parse($con,$queryTimeCard);
							oci_execute($result);
							if ($JamMasukAsli==''){
								$JamMasukAsli = $xJamKeluarAsli;
							} else {
								$JamKeluarAsli = $xJamMasukAsli;
							}
						}
					} else {
						$Status=476; //Alpha
					}
				} else {
					$Status=476; //Alpha
				}
				if ($Masuk != '') {
					if ($JamMasukAsli==''){
						$SubMasukJam = substr($Masuk, 0, -2);
						$SubMasukMenit = substr($Masuk, -2);
						
						if(strlen($SubMasukJam) == 1){
							$SubMasukJam = "0".$SubMasukJam;
						}
						$JamMasukAsli = $SubMasukJam . ":" . $SubMasukMenit;
					}
					if ($JamKeluarAsli==''){
						$SubKeluarJam = substr($Keluar, 0, -2);
						$SubKeluarMenit = substr($Keluar, -2);
						
						if(strlen($SubKeluarJam) == 1){
							$SubKeluarJam = "0".$SubKeluarJam;
						}
						$JamKeluarAsli = $SubKeluarJam . ":" . $SubKeluarMenit;
					}
					
					$JamKeluar = str_replace(':', '', $JamKeluarAsli);
					
					if($JamKeluar > $Keluar){
						$xStatus=536; //Lembur
						$xSubKeluarJam = substr($Keluar, 0, -2);
						$xSubKeluarMenit = substr($Keluar, -2);
						
						if(strlen($xSubKeluarJam) == 1){
							$xSubKeluarJam = "0".$xSubKeluarJam;
						}
						$xJamKeluarAsli = $xSubKeluarJam . ":" . $xSubKeluarMenit;
						
						$ArrMasuk = explode(":", $xJamKeluarAsli);
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
						if($tempJam >= 1){
							if($vORG_ID == 81){
								SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamKeluarAsli, $JamKeluarAsli, $vTotalJam);
							}
							$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
							oci_execute($resultSeq);
							$rowHSeq = oci_fetch_row($resultSeq);
							$IdTimecard = $rowHSeq[0];
							
							$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamKeluarAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
							$result = oci_parse($con,$queryTimeCard);
							oci_execute($result);
						}
					}
				} else {
					if ($JamMasukAsli==''){
						$JamMasukAsli = "08:00";
					}
					if ($JamKeluarAsli==''){
						$JamKeluarAsli = "16:00";
					}
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
					$xJamMasukAsli = ($vJamMasuk + $Hours) . ":" . $vMenitMasuk;
					$cekLembur = $Hours + 1;
					if($tempJam >= $cekLembur){
						$xStatus=536; //Lembur
						if($vORG_ID == 81){
							SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamMasukAsli, $JamKeluarAsli, $vTotalJam);
						}
						$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
						oci_execute($resultSeq);
						$rowHSeq = oci_fetch_row($resultSeq);
						$IdTimecard = $rowHSeq[0];
						
						$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
						$result = oci_parse($con,$queryTimeCard);
						oci_execute($result);
					}
				}
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
				if ($Masuk != '') {
					if($JamMasuk > $Terlambat){
						$xStatus=479; //Terlambat
						$Status=360; //Masuk
						
						$SubMasukJam = substr($Terlambat, 0, -2);
						$SubMasukMenit = substr($Terlambat, -2);
						
						if(strlen($SubMasukJam) == 1){
							$SubMasukJam = "0".$SubMasukJam;
						}
						$xJamMasukAsli = $SubMasukJam . ":" . $SubMasukMenit;
						
						$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
						oci_execute($resultSeq);
						$rowHSeq = oci_fetch_row($resultSeq);
						$IdTimecard = $rowHSeq[0];
						
						$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamMasukAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
						$result = oci_parse($con,$queryTimeCard);
						oci_execute($result);
					} else {
						$Status=360; //Masuk
					}
					$JamKeluar = str_replace(':', '', $JamKeluarAsli);
					
					if($JamKeluar > $Keluar){
						$xStatus=536; //Lembur
						$xSubKeluarJam = substr($Keluar, 0, -2);
						$xSubKeluarMenit = substr($Keluar, -2);
						
						if(strlen($xSubKeluarJam) == 1){
							$xSubKeluarJam = "0".$xSubKeluarJam;
						}
						$xJamKeluarAsli = $xSubKeluarJam . ":" . $xSubKeluarMenit;
						$ArrMasuk = explode(":", $xJamKeluarAsli);
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
						
						if($tempJam >= 1){
							if($vORG_ID == 81){
								SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamKeluarAsli, $JamKeluarAsli, $vTotalJam);
							}
							$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
							oci_execute($resultSeq);
							$rowHSeq = oci_fetch_row($resultSeq);
							$IdTimecard = $rowHSeq[0];
							
							$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamKeluarAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
							$result = oci_parse($con,$queryTimeCard);
							oci_execute($result);
						
						}
					}
				} else {
					$Status=360; //Masuk
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
					$xJamMasukAsli = ($vJamMasuk + $Hours) . ":" . $vMenitMasuk;
					$cekLembur = $Hours + 1;
					if($tempJam >= $cekLembur){
						$xStatus=536; //Lembur
						if($vORG_ID == 81){
							SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamMasukAsli, $JamKeluarAsli, $vTotalJam);
						}
						$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
						oci_execute($resultSeq);
						$rowHSeq = oci_fetch_row($resultSeq);
						$IdTimecard = $rowHSeq[0];
						
						$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
						$result = oci_parse($con,$queryTimeCard);
						oci_execute($result);
					}
				}
				// echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . $Status;
				// echo "<br>";
				
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
				oci_execute($resultSeq);
				$rowHSeq = oci_fetch_row($resultSeq);
				$IdTimecard = $rowHSeq[0];
				
				$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE)";
				$result = oci_parse($con,$queryTimeCard);
				oci_execute($result);
			} else {
				// $Status=360; //Masuk
				// if($vORG_ID == 81){
					// if($tempJam > 8){
						// $Status=536;
						// $vTotalLembur = ($tempJam - 8) . ":" . $tempMenit;
						// SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, '', $JamKeluarAsli, $vTotalLembur);
					// }
				// }
			}
		}	
	} else {
		$Status=536; // Lembur
		if($JamMasuk != '' && $JamKeluar != ''){
			if($CountMasuk >= 1){
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
				if($tempJam >= 1){
					if($vORG_ID == 81){
						SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $JamMasukAsli, $JamKeluarAsli, $vTotalJam);
					} 
					// echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . $Status . ", Selisih=" . $Selisih;
					// echo "<br>";
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
			$queryCountKategori = "SELECT COUNT(-1)
			FROM MJ.MJ_T_SIK 
			WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
			AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$newformat'
			AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$newformat'
			AND STATUS_DOK = 'Approved' AND IJIN_KHUSUS IN ('LUPA ABSEN DATANG', 'LUPA ABSEN PULANG')";
			//echo $queryCountKategori;
			$resultCountKategori = oci_parse($con,$queryCountKategori);
			oci_execute($resultCountKategori);
			$rowCountKategori = oci_fetch_row($resultCountKategori);
			$CountKategori = $rowCountKategori[0]; 
			
			if($CountKategori >= 1){
				$queryKategori = "SELECT ID, IJIN_KHUSUS
				FROM MJ.MJ_T_SIK 
				WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
				AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$newformat'
				AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$newformat'
				AND STATUS_DOK = 'Approved' AND IJIN_KHUSUS IN ('LUPA ABSEN DATANG', 'LUPA ABSEN PULANG')";
				$resultKategori = oci_parse($con,$queryKategori);
				oci_execute($resultKategori);
				$rowKategori = oci_fetch_row($resultKategori);
				$IdSIK = $rowKategori[0]; 
				$IjinKhusus = $rowKategori[1];
				if($IjinKhusus == 'LUPA ABSEN DATANG'){
					if($JamMasuk == ''){
						$querySIK = "SELECT JAM_FROM FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
						$resultSIK = oci_parse($con,$querySIK);
						oci_execute($resultSIK);
						$rowSIK = oci_fetch_row($resultSIK);
						$TempJamMasukAsli = $rowSIK[0]; 
						$ArrTempMasuk = explode(":", $TempJamMasukAsli);
						$vTempJamMasuk = $ArrTempMasuk[0];
						$vTempMenitMasuk = $ArrTempMasuk[1];
						$TempMasukLength = strlen($vTempMenitMasuk);
						
						if(strlen($vTempJamMasuk) == 1){
							$vTempJamMasuk = "0".$vTempJamMasuk;
						}
						if(strlen($vTempMenitMasuk) == 1){
							$vTempMenitMasuk = "0".$vTempMenitMasuk;
						}
						$JamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
					}
				}
				if($IjinKhusus == 'LUPA ABSEN PULANG'){
					if($JamKeluar == ''){
						$querySIK = "SELECT JAM_TO FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
						$resultSIK = oci_parse($con,$querySIK);
						oci_execute($resultSIK);
						$rowSIK = oci_fetch_row($resultSIK);
						$TempJamKeluarAsli = $rowSIK[0]; 
						$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
						$vTempJamKeluar = $ArrTempKeluar[0];
						$vTempMenitKeluar = $ArrTempKeluar[1];
						
						if(strlen($vTempJamKeluar) == 1){
							$vTempJamKeluar = "0".$vTempJamKeluar;
						}
						if(strlen($vTempMenitKeluar) == 1){
							$vTempMenitKeluar = "0".$vTempMenitKeluar;
						}
						$JamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
					}
				}
			}
			if($JamMasukAsli != '' && $JamKeluarAsli != ''){
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
				if($tempJam >= 1){
					if($vORG_ID == 81){
						SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $JamMasukAsli, $JamKeluarAsli, $vTotalJam);
					} 
					// echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . $Status . ", Selisih=" . $Selisih;
					// echo "<br>";
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
		}
	}
}
?>