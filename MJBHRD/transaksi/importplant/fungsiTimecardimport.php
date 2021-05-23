<?PHP
function InsertTimeCard($con, $dataId, $newformat, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, $user_id, $pengurang, $dateout){ 
	//echo $dataId. "-" . $newformat. "-" . $JamMasukAsli. "-" . $JamMasuk. "-" . $JamKeluarAsli. "-" . $JamKeluar;exit;
/*	$dataId = 19886;
	$newformat = "";
	$JamMasukAsli = "";
	$JamMasuk = "";
	$JamKeluarAsli = "";
	$JamKeluar = "7:19";
	$user_id = 11111;*/
	// echo "InsertTimeCard";
	//echo $JamKeluarAsli.'ASD';EXIT;
	//GET DATA KARYAWAN
	//echo substr($besok,11,5);exit;
	$vID = 0;
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
	WHERE B.HONORS='$dataId'
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
	$countData = 0;
	$CountKategori = 0;
	/*$queryDate = "SELECT SYSDATE-29 FROM DUAL";
	$resultDate = oci_parse($con,$queryDate);
	oci_execute($resultDate);
	$rowDate = oci_fetch_row($resultDate);
	$vSdate = $rowDate[0];*/

	//Fungsi Hapus absen double
	$queryCekAbsen = "SELECT COUNT(-1) FROM MJ.MJ_T_TIMECARD WHERE PERSON_ID = $vID AND TANGGAL = TO_DATE('$newformat', 'YYYY-MM-DD') AND CREATED_BY = 23";
	$resultCekAbsen = oci_parse($con,$queryCekAbsen);
	oci_execute($resultCekAbsen);
	$rowAbsen = oci_fetch_row($resultCekAbsen);
	$countAbsen = $rowAbsen[0]; 
	//echo $countData;exit;
	//echo $newformat; exit;
	if($countAbsen >= 1){
		$queryTimeCard = "DELETE MJ.MJ_T_TIMECARD WHERE PERSON_ID = $vID AND TANGGAL = TO_DATE('$newformat', 'YYYY-MM-DD') AND CREATED_BY = 23";
		$result = oci_parse($con,$queryTimeCard);
		oci_execute($result);
	}
	
	//CEK HONORS
	if($vID >= 1){
		$queryDataId = "SELECT COUNT(-1) FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE-$pengurang AND NVL(DATE_EMPLOYEE_DATA_VERIFIED, TO_DATE('12/31/4712', 'MM/DD/YYYY')) > TO_DATE('$newformat', 'YYYY-MM-DD')- $pengurang";
		$resultDataId = oci_parse($con,$queryDataId);
		oci_execute($resultDataId);
		$rowDataId = oci_fetch_row($resultDataId);
		$countData = $rowDataId[0]; 
		//echo $countData;
		
		//CEK KARYAWAN AKTIF
		if($countData >= 1){
			$queryDataId = "SELECT COUNT(-1) FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE 
			AND NVL(ORIGINAL_DATE_OF_HIRE, TO_DATE('12/31/4712', 'MM/DD/YYYY')) < TO_DATE('$newformat', 'YYYY-MM-DD')";
			$resultDataId = oci_parse($con,$queryDataId);
			oci_execute($resultDataId);
			$rowDataId = oci_fetch_row($resultDataId);
			$countData = $rowDataId[0]; 
			//echo $countData;
			//CEK APAKAH HARI INI MASIH AKTIF
			if($countData >= 1){
				$Selisih=0;
				$queryHariIni = "SELECT TO_CHAR(TO_DATE('$newformat', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
				$resultHariIni = oci_parse($con,$queryHariIni);
				oci_execute($resultHariIni);
				$rowHariIni = oci_fetch_row($resultHariIni);
				$HariIni = $rowHariIni[0]; 
				$HariIni = trim($HariIni);
				
				//CEK APAKAH HARI INI LIBUR
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

				//CEK APAKAH SHIFT KARYAWAN SUDAH DIGANTI
				$cekShiftGanti = "SELECT COUNT(-1) 
						FROM PER_ASSIGNMENTS_F PAF,MJ.MJ_M_CHANGE_SHIFT MCS
					    WHERE PAF.PERSON_ID = $dataId 
					    AND PAF.ASSIGNMENT_ID = MCS.ASSIGNMENT_ID 
					    AND MCS.DATE_DETAIL = TO_DATE('$newformat', 'YYYY-MM-DD')";
				$resultCekGanti = oci_parse($con,$cekShiftGanti);
				oci_execute($resultCekGanti);
				$rowCekGanti = oci_fetch_row($resultCekGanti);
				$CekGanti = $rowCekGanti[0];
				//echo $CekGanti;exit;
				$CekOfff  = '';
				//JIKA DIGANTI, CEK SHIFT MASUK & KELUAR DI TABEL MJ.MJ_M_CHANGE_SHIFT
				if($CekGanti >= 1)
				{
					$queryCountMasuk = "SELECT COUNT(-1)
						    FROM APPS.PER_PEOPLE_F A
						    , APPS.PER_ALL_ASSIGNMENTS_F B
						    , MJ.MJ_M_CHANGE_SHIFT C
						    , APPS.HXT_WORK_SHIFTS_FMV E
						    , APPS.HXT_SHIFTS F
						    WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
						    AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
						    AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. DATE_DETAIL = TO_DATE('$newformat', 'YYYY-MM-DD')
						    AND C.SHIFT_ID = E.TWS_ID
							AND UPPER(E.MEANING)='$HariIni' -- Hari absen
						    AND E.SHT_ID=F.ID";
						//echo $queryCountMasuk; exit;
						$resultCountMasuk = oci_parse($con,$queryCountMasuk);
						oci_execute($resultCountMasuk);
						$rowCountMasuk = oci_fetch_row($resultCountMasuk);
						$CountMasuk = $rowCountMasuk[0]; 
						//echo $CountMasuk;
						if($CountMasuk >= 1){
							$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_CHANGE_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. DATE_DETAIL = TO_DATE('$newformat', 'YYYY-MM-DD')
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariIni' -- Hari absen
								AND E.SHT_ID=F.ID";
							//echo $queryMasuk;exit;
							$resultMasuk = oci_parse($con,$queryMasuk);
							oci_execute($resultMasuk);
							$rowMasuk = oci_fetch_row($resultMasuk);
							$Terlambat = $rowMasuk[0]; 
							$Masuk = $rowMasuk[1]; 
							$Keluar = $rowMasuk[2]; 
							$Hours = $rowMasuk[3]; 

					$CekOff = "SELECT COUNT(-1)
						    FROM APPS.PER_PEOPLE_F A
						    , APPS.PER_ALL_ASSIGNMENTS_F B
						    , MJ.MJ_M_CHANGE_SHIFT C
						    , APPS.HXT_WORK_SHIFTS_FMV E
						    , APPS.HXT_SHIFTS F
						    WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
						    AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
						    AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C.DATE_DETAIL = TO_DATE('$newformat', 'YYYY-MM-DD')
						    AND C.SHIFT_ID = E.TWS_ID
							AND UPPER(E.MEANING)='$HariIni' -- Hari absen
						    AND E.SHT_ID=F.ID
						    AND F.NAME = 'Off'";
						//echo $queryCountMasuk; exit;
						$resultCekOff = oci_parse($con,$CekOff);
						oci_execute($resultCekOff);
						$rowCekOff = oci_fetch_row($resultCekOff);
						$CekOfff = $rowCekOff[0];
						} else {
							$Terlambat = ''; 
							$Masuk = ''; 
							$Keluar = ''; 
						}
				}
				//JIKA TIDAK DIGANTI, CEK SHIFT MASUK & KELUAR DI TABEL MJ.MJ_M_SHIFT
				else {
						$queryCountMasuk = "SELECT COUNT(-1)
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C.DATE_FROM <= TO_DATE('$newformat', 'YYYY-MM-DD')
								AND (C.DATE_TO >= TO_DATE('$newformat', 'YYYY-MM-DD') OR C.DATE_TO IS NULL)
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariIni'-- Hari absen
								AND E.SHT_ID=F.ID";
						//echo $queryCountMasuk;
						$resultCountMasuk = oci_parse($con,$queryCountMasuk);
						oci_execute($resultCountMasuk);
						$rowCountMasuk = oci_fetch_row($resultCountMasuk);
						$CountMasuk = $rowCountMasuk[0]; 
						//echo $CountMasuk;exit;
						if($CountMasuk >= 1){
							$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C.DATE_FROM <= TO_DATE('$newformat', 'YYYY-MM-DD')
								AND (C.DATE_TO >= TO_DATE('$newformat', 'YYYY-MM-DD') OR C.DATE_TO IS NULL)
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariIni'-- Hari absen
								AND E.SHT_ID=F.ID";
							//echo $queryMasuk;exit;
							$resultMasuk = oci_parse($con,$queryMasuk);
							oci_execute($resultMasuk);
							$rowMasuk = oci_fetch_row($resultMasuk);
							$Terlambat = $rowMasuk[0]; 
							$Masuk = $rowMasuk[1]; 
							$Keluar = $rowMasuk[2]; 
							$Hours = $rowMasuk[3]; 

						$CekOff = "SELECT COUNT(-1)
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$newformat', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C.DATE_FROM <= TO_DATE('$newformat', 'YYYY-MM-DD')
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariIni'-- Hari absen
								AND E.SHT_ID=F.ID
								AND F.NAME = 'Off'";
						//echo $queryCountMasuk;
						$resultCekOff = oci_parse($con,$CekOff);
						oci_execute($resultCekOff);
						$rowCekOff = oci_fetch_row($resultCekOff);
						$CekOfff = $rowCekOff[0]; 
						} else {
							$Terlambat = ''; 
							$Masuk = ''; 
							$Keluar = ''; 
						}
				}
				//echo $Masuk;exit;
				//FLAG ALPHA, TERLAMBAT, LEMBUR
				$FlagATL = 'Y'; 
				//echo $HariIni . '<BR>' . $HariLibur . '<BR>' . $JamKeluarAsli . '<BR>' . $JamMasukAsli . '<BR>' . $CountMasuk . '<BR>' . $dataId;
				//echo 'Masuk : '.$Masuk.' - '.' Keluar : '.$Keluar;exit;
				// exit;
				//MULAI GET DATA TIMECARD
				//CEK APAKAH DI HARI INI KARYAWAN HARUS MASUK KERJA
				//echo $Keluar;exit;
				if($Keluar == 2400 || $Keluar == 0)
				{
					$Keluar = '0000'; 
				}
				//echo " HariLibur: ". $HariLibur. " - " . " CekOfff : ". " - " .$CekOfff. "    ";exit;
				if(($HariLibur == 1 && $CekOfff == 0) || ($CekOfff == 0 && $HariIni != 'SUNDAY') || ($HariIni == 'SUNDAY' && $CekOfff == 0)){
					//echo $JamKeluarAsli. "||". $JamMasukAsli;exit;
					//CEK JIKA TIDAK ADA JAM MASUK & JAM KELUAR 
					if ($JamKeluarAsli=='' || $JamMasukAsli==''){
						if ($JamKeluarAsli=='' && $JamMasukAsli==''){
							$FlagATL = 'N'; 
						}
						//echo $FlagATL;exit;
						//echo $CountMasuk;exit;
						//CEK ADA SHIFT
						if($CountMasuk >= 1){
							$queryCountKategori = "SELECT COUNT(-1)
							FROM MJ.MJ_T_SIK 
							WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
							AND '$newformat' BETWEEN TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD')
							AND STATUS_DOK = 'Approved'
							AND STATUS = 1";
							//echo $queryCountKategori;
							$resultCountKategori = oci_parse($con,$queryCountKategori);
							oci_execute($resultCountKategori);
							$rowCountKategori = oci_fetch_row($resultCountKategori);
							$CountKategori = $rowCountKategori[0]; 
							//echo $CountKategori;exit;
							//CEK APAKAH HARI TSB ADA SIK
							if($CountKategori >= 1){
								$queryKategori = "SELECT ID, KATEGORI, IJIN_KHUSUS
								FROM MJ.MJ_T_SIK 
								WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$dataId AND EFFECTIVE_END_DATE > SYSDATE) 
								AND '$newformat' BETWEEN TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD')
								AND STATUS_DOK = 'Approved'
								AND STATUS = 1";
								$resultKategori = oci_parse($con,$queryKategori);
								oci_execute($resultKategori);
								$rowKategori = oci_fetch_row($resultKategori);
								$IdSIK = $rowKategori[0]; 
								$Kategori = $rowKategori[1]; 
								$IjinKhusus = $rowKategori[2]; 
								
								if($Kategori == 'Cuti'){
									$CutiTahunan = -1;
									$queryCutiTahunan = "SELECT (CUTI_TAHUNAN) FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$dataId";
									$resultCutiTahunan = oci_parse($con,$queryCutiTahunan);
									oci_execute($resultCutiTahunan);
									$rowCutiTahunan = oci_fetch_row($resultCutiTahunan);
									$CutiTahunan = $rowCutiTahunan[0]; 
									
									if($CutiTahunan == 0){
										$Status=StatusTimecard($con, 'IJIN'); //Ijin
									} else {
										if ($CutiTahunan >= 1){
											$Status=StatusTimecard($con, 'CUTI'); //Cuti
											// $CutiTahunan = $CutiTahunan - 1;
											// $query = "UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN = $CutiTahunan, LAST_UPDATED_BY='funsiTimecard', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$dataId";
											// $result = oci_parse($con,$query);
											// oci_execute($result);
										}
									}
								} elseif ($Kategori == 'Sakit'){
									// $queryCutiSakit = "SELECT (CUTI_SAKIT) FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$dataId";
									// $resultCutiSakit = oci_parse($con,$queryCutiSakit);
									// oci_execute($resultCutiSakit);
									// $rowCutiSakit = oci_fetch_row($resultCutiSakit);
									// $CutiSakit = $rowCutiSakit[0]; 
									
									// if($CutiSakit == 0){
										// $Status=StatusTimecard($con, 'IJIN'); //Ijin
									// } else {
										$Status=StatusTimecard($con, 'SAKIT'); //Sakit
										// $CutiSakit = $CutiSakit - 1;
										// $query = "UPDATE MJ.MJ_M_CUTI SET CUTI_SAKIT = $CutiSakit WHERE PERSON_ID=$dataId";
										// $result = oci_parse($con,$query);
										// oci_execute($result);
									// }
								} elseif ($Kategori == 'Terlambat'){
									$Status=StatusTimecard($con, 'MASUK'); //Masuk
									//$xStatus=StatusTimecard($con, 'TERLAMBAT'); //Terlambat
									// if ($JamMasukAsli==''){
										// $SubMasukJam = substr($Terlambat, 0, -2);
										// $SubMasukMenit = substr($Terlambat, -2);
										
										// if(strlen($SubMasukJam) == 1){
											// $SubMasukJam = "0".$SubMasukJam;
										// }
										// $xJamMasukAsli = $SubMasukJam . ":" . $SubMasukMenit;
									
										// $querySIK = "SELECT JAM_TO FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
										// $resultSIK = oci_parse($con,$querySIK);
										// oci_execute($resultSIK);
										// $rowSIK = oci_fetch_row($resultSIK);
										// $TempJamKeluarAsli = $rowSIK[0]; 
										// $ArrTempKeluar = explode(":", $TempJamKeluarAsli);
										// $vTempJamKeluar = $ArrTempKeluar[0];
										// $vTempMenitKeluar = $ArrTempKeluar[1];
										
										// if(strlen($vTempJamKeluar) == 1){
											// $vTempJamKeluar = "0".$vTempJamKeluar;
										// }
										// if(strlen($vTempMenitKeluar) == 1){
											// $vTempMenitKeluar = "0".$vTempMenitKeluar;
										// }
										// $xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
										
										// $resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
										// oci_execute($resultSeq);
										// $rowHSeq = oci_fetch_row($resultSeq);
										// $IdTimecard = $rowHSeq[0];
										
										// $queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE)";
										// $result = oci_parse($con,$queryTimeCard);
										// oci_execute($result);
										
										// $JamMasukAsli = $xJamKeluarAsli;
									// }
								} elseif ($Kategori == 'Ijin'){
									if($IjinKhusus != ''){
										$Status = StatusTimecard($con, 'IJIN KHUSUS'); //Ijin Khusus
									} else {
										$Status = StatusTimecard($con, 'IJIN'); //Ijin
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
										$Status=StatusTimecard($con, 'MASUK'); //Masuk
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
										$Status=StatusTimecard($con, 'MASUK'); //Masuk
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
										$Status=StatusTimecard($con, 'MASUK'); //Masuk
									}
									if($IjinKhusus == 'SETENGAH HARI'){
										$xStatus = StatusTimecard($con, 'IJIN KHUSUS'); //Ijin Khusus
										$Status=StatusTimecard($con, 'MASUK'); //Masuk
										$querySIK = "SELECT JAM_FROM, JAM_TO FROM MJ.MJ_T_SIK WHERE ID=$IdSIK";
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
											$zStatus=StatusTimecard($con, 'TERLAMBAT'); //Terlambat
											
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
											
											$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$zJamMasukAsli', '$xJamKeluarAsli', '$zStatus', 'Belum', '$user_id', SYSDATE, TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
											$result = oci_parse($con,$queryTimeCard);
											oci_execute($result);
										}
										
										$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
										oci_execute($resultSeq);
										$rowHSeq = oci_fetch_row($resultSeq);
										$IdTimecard = $rowHSeq[0];
										
										$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
										$result = oci_parse($con,$queryTimeCard);
										oci_execute($result);
										if ($JamMasukAsli==''){
											$JamMasukAsli = $xJamKeluarAsli;
										} else {
											$JamKeluarAsli = $xJamMasukAsli;
										}
									}
								} else { 
									if($FlagATL == 'Y'){
										$Status=StatusTimecard($con, 'ABSEN TIDAK LENGKAP'); //ABSEN TIDAK LENGKAP
									} else {
										$Status=StatusTimecard($con, 'ALPHA'); //Alpha
									}
								}
							} else { //echo $JamMasukAsli.'-'.substr($besok,11,5).'-'.$JamKeluarAsli;exit;
									//$masukplode = explode(":", $JamMasukAsli);
									//echo $masukplode;exit;
								if($FlagATL == 'Y'){ 
									//echo shift, echo jam keluar besok, cek apakah 
									$Status=StatusTimecard($con, 'ABSEN TIDAK LENGKAP'); //ABSEN TIDAK LENGKAP
								} else {
									$Status=StatusTimecard($con, 'ALPHA'); //Alpha
								}
/*								echo $JamMasuk.' >= '.$Terlambat;EXIT;
								if ($JamMasukAsli != ''){
									if($JamMasuk >= $Terlambat){
										$oStatus=StatusTimecard($con, 'TERLAMBAT'); //Terlambat
									
										$SubMasukJam = substr($Terlambat, 0, -2);
										$SubMasukMenit = substr($Terlambat, -2);
										
										if(strlen($SubMasukJam) == 1){
											$SubMasukJam = "0".$SubMasukJam;
										}
										$oJamMasukAsli = $SubMasukJam . ":" . $SubMasukMenit;
										
										$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
										oci_execute($resultSeq);
										$rowHSeq = oci_fetch_row($resultSeq);
										$IdTimecard = $rowHSeq[0];
										
										$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$oJamMasukAsli', '$JamMasukAsli', '$oStatus', 'Belum', '$user_id', SYSDATE)";
										$result = oci_parse($con,$queryTimeCard);
										oci_execute($result);
									}
								}*/
							}
							//echo $JamKeluarAsli.'ASD';EXIT;
							//echo $Masuk;exit;
							if ($Masuk != '') {
								//echo 'masukin';exit;
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
								if($JamMasuk >= $Terlambat){
									$xStatus=StatusTimecard($con, 'TERLAMBAT'); //Terlambat
									
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
									
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, DATE_IN, DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamMasukAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
									$result = oci_parse($con,$queryTimeCard);
									oci_execute($result);
								}
								$JamKeluar = str_replace(':', '', $JamKeluarAsli);
								//echo $JamKeluar . '>' . $Keluar;EXIT;
								if($JamKeluar > $Keluar){
									$xStatus=StatusTimecard($con, 'LEMBUR'); //Lembur
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
									//echo $tempJam;
									if($tempJam >= 1){
										//echo "A : " . $xJamKeluarAsli;
										if($vORG_ID == 81){
											//SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamKeluarAsli, $JamKeluarAsli, $vTotalJam);
										}
										$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
										oci_execute($resultSeq);
										$rowHSeq = oci_fetch_row($resultSeq);
										$IdTimecard = $rowHSeq[0];
										
										$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamKeluarAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
										$result = oci_parse($con,$queryTimeCard);
										oci_execute($result);
									}
								}
								$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
								oci_execute($resultSeq);
								$rowHSeq = oci_fetch_row($resultSeq);
								$IdTimecard = $rowHSeq[0];
								
								$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
							} else {
								//echo 'masukin';exit;
								if ($JamMasukAsli==''){
									$time = substr($Masuk,0,1).':'.substr($Masuk,2,2);
									$JamMasukAsli = "$time";
									//$JamMasukAsli = "555";
								}
								if ($JamKeluarAsli==''){
									$time = substr($Keluar,0,2).':'.substr($Keluar,2,2);
									$JamKeluarAsli = "$time";
									//$JamKeluarAsli = "555";
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
								//echo $cekLembur;exit;
								if($tempJam >= $cekLembur){
									$xStatus=StatusTimecard($con, 'LEMBUR'); //Lembur
									// echo "B : " . $xJamMasukAsli;
									if($vORG_ID == 81){
										//SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamMasukAsli, $JamKeluarAsli, $vTotalJam);  
									}
									$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
									oci_execute($resultSeq);
									$rowHSeq = oci_fetch_row($resultSeq);
									$IdTimecard = $rowHSeq[0];
									
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
									$result = oci_parse($con,$queryTimeCard);
									oci_execute($result);
								} else {
									$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
									oci_execute($resultSeq);
									$rowHSeq = oci_fetch_row($resultSeq);
									$IdTimecard = $rowHSeq[0];
									
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
									$result = oci_parse($con,$queryTimeCard);
									oci_execute($result);
								}
							}
						}
					} else { //JIKA JAM KELUAR ATAU JAM MASUK ADA ATAU TIDAK KOSONG
						//echo 'a';exit;
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
						//echo $CountMasuk;exit;
						//echo $JamMasuk . ' dan ' . $Terlambat; 1434 dan 706
						if($CountMasuk >= 1){
							if ($Masuk != '') {
								if($JamMasuk >= $Terlambat){
									$xStatus=StatusTimecard($con, 'TERLAMBAT'); //Terlambat
									$Status=StatusTimecard($con, 'MASUK'); //Masuk
									
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
									
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamMasukAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
									$result = oci_parse($con,$queryTimeCard);
									oci_execute($result);
								} else { 
									$Status=StatusTimecard($con, 'MASUK'); //Masuk
								}
								//echo $JamMasuk . ' dan ' . $Masuk;exit;
								$JamKeluar = str_replace(':', '', $JamKeluarAsli);
								//echo $JamMasuk. ">". $Keluar;exit;
								if($JamMasuk > $JamKeluar){
									$JamKeluar=$JamKeluar+2400;
								}
								//echo $JamKeluar. ">". $Keluar;exit;
								if($JamKeluar > $Keluar){
									$xStatus=StatusTimecard($con, 'LEMBUR'); //Lembur
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
									
									if(str_replace(':', '', $JamMasukAsli) > str_replace(':', '', $JamKeluarAsli)){
										$vJamKeluar=$vJamKeluar+24;
									}
									//echo $vJamKeluar. ">=1";exit;
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
									//echo $tempJam. ">=1";exit;
									if($tempJam >= 1){
										// echo "C : " . $xJamKeluarAsli;
										if($vORG_ID == 81){
											//SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamKeluarAsli, $JamKeluarAsli, $vTotalJam);
										}
										$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
										oci_execute($resultSeq);
										$rowHSeq = oci_fetch_row($resultSeq);
										$IdTimecard = $rowHSeq[0];
										
										$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamKeluarAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
										$result = oci_parse($con,$queryTimeCard);
										oci_execute($result);
									}
								}
								//echo $JamMasuk . ' dan ' . $Masuk;exit;
								$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
								oci_execute($resultSeq);
								$rowHSeq = oci_fetch_row($resultSeq);
								$IdTimecard = $rowHSeq[0];
								//echo $Masuk+300;exit;
								if ($JamMasuk >= $Masuk + 300){
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', 247, 'Belum', '$user_id', SYSDATE, TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
								}
								else {
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
									//echo $queryTimeCard;
								}
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
							} else {
								$Status=StatusTimecard($con, 'MASUK'); //Masuk
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
									$xStatus=StatusTimecard($con, 'LEMBUR'); //Lembur
									// echo "D : " . $xJamMasukAsli;
									if($vORG_ID == 81){
										//SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $xJamMasukAsli, $JamKeluarAsli, $vTotalJam);
									}
									$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
									oci_execute($resultSeq);
									$rowHSeq = oci_fetch_row($resultSeq);
									$IdTimecard = $rowHSeq[0];
									
									$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$xJamMasukAsli', '$JamKeluarAsli', '$xStatus', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
									$result = oci_parse($con,$queryTimeCard);
									oci_execute($result);
								}
								$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
								oci_execute($resultSeq);
								$rowHSeq = oci_fetch_row($resultSeq);
								$IdTimecard = $rowHSeq[0];
								
								$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
							}
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
				} else {//LEMBUR DI HARI LIBUR 
					$Status=StatusTimecard($con, 'LEMBUR'); // Lembur
					//echo $JamKeluar;exit;
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
								// echo "E : " . $JamMasukAsli;
								if($vORG_ID == 81){
									//SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $JamMasukAsli, $JamKeluarAsli, $vTotalJam);
								} 
/*								echo "ID = " . $dataId . ", Tanggal=" . $newformat . ", Jam Masuk=" . $JamMasukAsli . ", Jam Keluar=" . $JamKeluarAsli . ", Status=" . 										$Status . ", Selisih=" . $Selisih;
								echo "<br>";
								$queryProcedure = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($dataId, '$newformat $JamMasukAsli:00', '$newformat $JamKeluarAsli:00', '$Status')";
								echo $queryProcedure;
								$resultProcedure = oci_parse($con,$queryProcedure);
								oci_execute($resultProcedure);	*/
								
								$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_TIMECARD_SEQ.nextval FROM DUAL");
								oci_execute($resultSeq);
								$rowHSeq = oci_fetch_row($resultSeq);
								$IdTimecard = $rowHSeq[0];
								
								$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
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
						//echo $CountKategori;exit;
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
						if($JamMasukAsli != '' && $JamKeluarAsli != ''){ echo 'a';exit;
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
									//SimpanSPL($con, $dataId, $newformat, $vLOCATION_CODE, $vFULL_NAME, $vSPV, $vEMAIL_SPV, $vMANAGER, $vEMAIL_MANAGER, $JamMasukAsli, $JamKeluarAsli, $vTotalJam);
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
								
								$queryTimeCard = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE,DATE_IN,DATE_OUT) VALUES ($IdTimecard, " . APPCODE . ", $dataId, TO_DATE('$newformat', 'YYYY-MM-DD'), '$JamMasukAsli', '$JamKeluarAsli', '$Status', 'Belum', '$user_id', SYSDATE,TO_DATE('$newformat', 'YYYY-MM-DD'),TO_DATE('$dateout', 'YYYY-MM-DD'))";
								$result = oci_parse($con,$queryTimeCard);
								oci_execute($result);
							}
						}
					}
				}
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
		
		$queryDepartment = "SELECT REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3)
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$emp_name'";
		$resultDepartment = oci_parse($con,$queryDepartment);
		oci_execute($resultDepartment);
		$rowDepartment = oci_fetch_row($resultDepartment);
		$vDepartment = $rowDepartment[0]; 
		
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
		
		$query = "INSERT INTO MJ.MJ_T_SPL_DETAIL (ID, MJ_T_SPL_ID, NAMA, DEPARTEMEN, JAM_FROM, JAM_TO, TOTAL_JAM, PEKERJAAN, STATUS_DOK, TINGKAT, CREATED_BY, CREATED_DATE) VALUES ($seqD, $hdid, '$emp_name', '$vDepartment', '$jam_keluar_sys', '$jam_keluar', '$jam_lembur', 'Lembur Karyawan MJB', 'In process', $tingkat, '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);		
	}
	
	function StatusTimecard($con, $elementName){
		$elementID = 0;
		$queryStatus = "SELECT ELEMENT_ID FROM MJ.MJ_M_ELEMENT WHERE ELEMENT_NAME='$elementName'";
		$resultStatus = oci_parse($con,$queryStatus);
		oci_execute($resultStatus);
		$rowStatus = oci_fetch_row($resultStatus);
		$elementID = $rowStatus[0];
		return $elementID;
	}
?>