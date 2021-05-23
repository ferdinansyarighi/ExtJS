<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi2.php'; //Koneksi ke database

 
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

$tglskr=date('Y-m-d'); 
$tahunskr=date('Y'); 
$bulanskr=date('m'); 
$hariskr=date('d'); 

$tahunsebelum=$tahunskr - 1; 
$bulansebelum=$bulanskr - 1; 
$harisebelum=$hariskr - 1; 

$tahunsesudah=$tahunskr + 1; 
$bulansesudah=$bulanskr + 1; 
$harisesudah=$hariskr + 1; 

if(strlen($bulansebelum)==1)
	$bulansebelum="0" . $bulansebelum;
if(strlen($bulansesudah)==1)
	$bulansesudah="0" . $bulansesudah;
if(strlen($bulanskr)==1)
	$bulanskr="0" . $bulanskr;
if($bulanskr == '01'){
	$bulansebelum='12';
}
if($bulanskr == '12'){
	$bulansesudah='01';
}
	
if($bulanskr == '01'){
	$periode1 = $tahunsebelum . "-" . $bulansebelum . "-21";
} else {
	$periode1 = $tahunskr . "-" . $bulansebelum . "-21";
}
$periode2 = $tahunskr . "-" . $bulanskr . "-20";

$periode1 = '2016-06-21';
$periode2 = '2016-07-20';

	$queryTimecard = "SELECT COUNT(-1)
	FROM MJ.MJ_T_TIMECARD MTT
	INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID 
	AND PPF.EFFECTIVE_END_DATE > SYSDATE 
	AND UPPER(PPF.CURRENT_EMPLOYEE_FLAG) = 'Y'
	WHERE MTT.APP_ID=" . APPCODE . " AND STATUS_SYNC='Belum' AND ELEMENT_NAME IN ('LEMBUR')
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2'
	--AND MTT.PERSON_ID IN (2117)";
	//echo $queryTimecard;
	$resultTimecard = oci_parse($con,$queryTimecard);
	oci_execute($resultTimecard);
	$rowTimecard = oci_fetch_row($resultTimecard);
	$vTimecard = $rowTimecard[0];
	
	if($vTimecard > 0){
		$query = "SELECT MTT.ID
		, MTT.PERSON_ID
		, TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') || ' ' || NVL(MTT.JAM_MASUK, '00:00') || ':00' AS TGL_MASUK
		, TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') || ' ' || NVL(MTT.JAM_KELUAR, '00:00') || ':00' AS TGL_KELUAR
		, MTT.STATUS
		, TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')
		, NVL(MTT.JAM_MASUK, '00:00')
		, NVL(MTT.JAM_KELUAR, '00:00')
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID 
		AND PPF.EFFECTIVE_END_DATE > SYSDATE 
		AND UPPER(PPF.CURRENT_EMPLOYEE_FLAG) = 'Y'
		WHERE MTT.APP_ID=" . APPCODE . " AND MTT.STATUS_SYNC='Belum' AND ELEMENT_NAME IN ('LEMBUR')
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2'
		--AND MTT.PERSON_ID IN (2117)";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$vID=$row[0];
			$vPerson_id=$row[1];
			$vTglMasuk=$row[2];
			$vTglKeluar=$row[3];
			$vStatus=$row[4];
			$vTanggalTrans=$row[5];
			$vJamMasuk=$row[6];
			$vJamKeluar=$row[7];
			
			$queryCountSPL = "SELECT COUNT(-1)
			FROM MJ.MJ_T_SPL MTS
			INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
			WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE)
			AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')='$vTanggalTrans'";
			//echo $queryCountSPL;
			$resultCountSPL = oci_parse($con,$queryCountSPL);
			oci_execute($resultCountSPL);
			$rowCountSPL = oci_fetch_row($resultCountSPL);
			$CountSPL = $rowCountSPL[0];
			//echo $CountSPL;
			if($CountSPL >= 1){
				$queryCountSPL = "SELECT COUNT(-1)
				FROM MJ.MJ_T_SPL MTS
				INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
				WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE)
				AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')='$vTanggalTrans'
				AND MTSD.STATUS_DOK = 'Approved'";
				//echo $queryCountSPL;
				$resultCountSPL = oci_parse($con,$queryCountSPL);
				oci_execute($resultCountSPL);
				$rowCountSPL = oci_fetch_row($resultCountSPL);
				$CountSPL = $rowCountSPL[0];
				//echo $CountSPL;
				if($CountSPL >= 1){
					$queryCountML = "SELECT COUNT(-1)
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
					INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = PAF.POSITION_ID
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
					//echo $queryCountSPL;
					$resultCountML = oci_parse($con,$queryCountML);
					oci_execute($resultCountML);
					$rowCountML = oci_fetch_row($resultCountML);
					$CountML = $rowCountML[0];
					//echo $CountML;
					if($CountML >= 1){
						$transTime = strtotime($vTanggalTrans);
						$sisaLembur = 0;
						$hariskr=date('d', $transTime);
						$bulanskr=date('m', $transTime);
						$tahunskr=date('Y', $transTime);
						
						if($hariskr >= 21){
							$bulandepan = $bulanskr + 1;
							if($bulandepan == 13){
								$bulandepan = "01";
								$tahundepan = $tahunskr + 1;
							} else {
								if(strlen($bulandepan) == 1){
									$bulandepan = "0" . $bulandepan;
								}
								$tahundepan = $tahunskr;
							}
							$periodeAwal = $tahunskr . "-" . $bulanskr . "-21";
							$periodeAkhir = $tahundepan . "-" . $bulandepan . "-20";
						} else {
							$bulanlalu = $bulanskr - 1;
							if($bulanlalu == 0){
								$bulanlalu = "12";
								$tahunlalu = $tahunskr - 1;
							} else {
								if(strlen($bulanlalu) == 1){
									$bulanlalu = "0" . $bulanlalu;
								}
								$tahunlalu = $tahunskr;
							}
							$periodeAwal = $tahunlalu . "-" . $bulanlalu . "-20";
							$periodeAkhir =  $tahunskr . "-" . $bulanskr . "-21";
						}
						
						$queryCountML = "SELECT MMJ.JUMLAH_LEMBUR * 60 AS JUMLAH_LEMBUR
						FROM APPS.PER_PEOPLE_F PPF
						INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
						INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = PAF.POSITION_ID
						WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
						//echo $queryCountSPL;
						$resultCountML = oci_parse($con,$queryCountML);
						oci_execute($resultCountML);
						$rowCountML = oci_fetch_row($resultCountML);
						$JamLembur = $rowCountML[0];
						$SudahLembur = 0;
						$TotalLembur = 0;
						
						$queryCountLembur = "SELECT COUNT(-1)
						FROM MJ.MJ_T_TIMECARD
						WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
						AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
						AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
						AND PERSON_ID=$vPerson_id";
						//echo $queryCountSPL;
						$resultCountLembur = oci_parse($con,$queryCountLembur);
						oci_execute($resultCountLembur);
						$rowCountLembur = oci_fetch_row($resultCountLembur);
						$CountLembur = $rowCountLembur[0];
						
						if($CountLembur >= 1){
							$queryCountLembur = "SELECT JAM_MASUK, JAM_KELUAR
							FROM MJ.MJ_T_TIMECARD
							WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
							AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
							AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
							AND PERSON_ID=$vPerson_id";
							//echo $queryCountSPL;
							$resultCountLembur = oci_parse($con,$queryCountLembur);
							oci_execute($resultCountLembur);
							while($rowSudahLembur = oci_fetch_row($resultCountLembur))
							{
								$TempJamMasukAsli = $rowSudahLembur[0]; 
								$TempJamKeluarAsli = $rowSudahLembur[1]; 
								$ArrTempMasuk = explode(":", $TempJamMasukAsli);
								$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
								$vTempJamMasuk = $ArrTempMasuk[0];
								$vTempJamMasuk = ($vTempJamMasuk * 60) + 60;
								$vTempMenitMasuk = $ArrTempMasuk[1];
								$vTempMenitMasuk = $vTempMenitMasuk + $vTempJamMasuk;
								$vTempJamKeluar = $ArrTempKeluar[0];
								$vTempJamKeluar = $vTempJamKeluar * 60;
								$vTempMenitKeluar = $ArrTempKeluar[1];
								$vTempMenitKeluar = $vTempMenitKeluar + $vTempJamKeluar;
								$tempMenit = $vTempMenitKeluar - $vTempMenitMasuk;
								$SudahLembur = $SudahLembur + $tempMenit;
							}
						}
						$TotalLembur = $JamLembur - $SudahLembur;
						//echo $TotalLembur;
						if ($TotalLembur >= 0){
							$queryCountSPL = "SELECT MTSD.JAM_FROM, MTSD.JAM_TO
							FROM MJ.MJ_T_SPL MTS
							INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
							WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE)
							AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')='$vTanggalTrans'
							AND MTSD.STATUS_DOK = 'Approved'";
							$resultSIK = oci_parse($con,$queryCountSPL);
							oci_execute($resultSIK);
							$rowSIK = oci_fetch_row($resultSIK);
							$TempJamMasukAsli = $rowSIK[0]; 
							$TempJamKeluarAsli = $rowSIK[1]; 
							$ArrTempMasuk = explode(":", $TempJamMasukAsli);
							$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
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
							if(str_replace(':', '', $xJamKeluarAsli) > str_replace(':', '', $vJamKeluar)){
								$ArrTempMasuk = explode(":", $vJamMasuk);
								$ArrTempKeluar = explode(":", $vJamKeluar);
								$vTempJamMasuk = $ArrTempMasuk[0];
								$vTempMenitMasuk = $ArrTempMasuk[1];
								$vTempJamKeluar = $ArrTempKeluar[0];
								$vTempMenitKeluar = $ArrTempKeluar[1];
								$JumJamMasuk = $vTempJamMasuk * 60;
								$JumMenitMasuk = $vTempMenitMasuk + $JumJamMasuk;
								$JumJamKeluar = $vTempJamKeluar * 60;
								$JumMenitKeluar = $vTempMenitKeluar + $JumJamKeluar;
								$xJamKeluarAsli = $vJamKeluar;
							} else {
								$Arrhasil = explode(".", $vTempMenitKeluar);
								$vTempMenitKeluar = $Arrhasil[0];
								$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
								$JumJamMasuk = $vTempJamMasuk * 60;
								$JumMenitMasuk = $vTempMenitMasuk + $JumJamMasuk;
								$JumJamKeluar = $vTempJamKeluar * 60;
								$JumMenitKeluar = $vTempMenitKeluar + $JumJamKeluar;
							}
							$tempMenit = $JumMenitKeluar - $JumMenitMasuk;
							if($tempMenit <= $TotalLembur){
								$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
							} else {
								$JumMenitMasuk = $JumMenitMasuk + $TotalLembur;
								$vTempJamKeluar = $JumMenitMasuk / 60;
								$Arrhasil = explode(".", $vTempJamKeluar);
								$vTempJamKeluar = $Arrhasil[0];
								if(strlen($vTempJamKeluar) == 1){
									$vTempJamKeluar = "0".$vTempJamKeluar;
								}
								$vTempMenitKeluar = $JumMenitMasuk % 60;
								$Arrhasil = explode(".", $vTempMenitKeluar);
								$vTempMenitKeluar = $Arrhasil[0];
								if(strlen($vTempMenitKeluar) == 1){
									$vTempMenitKeluar = "0".$vTempMenitKeluar;
								}
								$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
								$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
							}
							//echo $queryUpload;
							$resultUpload = oci_parse($con,$queryUpload);
							$oe = oci_execute($resultUpload);
							if($oe){
								$queryUpdate = "UPDATE MJ.MJ_T_TIMECARD SET STATUS_SYNC='Sudah', LAST_UPDATED_BY=24, LAST_UPDATED_DATE=SYSDATE WHERE ID=$vID";
								//echo $query;
								$resultUpdate = oci_parse($con,$queryUpdate);
								$oeu = oci_execute($resultUpdate);
								// if($oeu){
									// echo "ID: " . $vID . " Terupdate.";
									// echo "<br>";
								// } else {
									// echo $queryUpdate;
									// echo "<br>";
								// }
							}
						}
					} else {
						$queryCountML = "SELECT COUNT(-1)
						FROM APPS.PER_PEOPLE_F PPF
						INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
						INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = 0
						WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
						//echo $queryCountSPL;
						$resultCountML = oci_parse($con,$queryCountML);
						oci_execute($resultCountML);
						$rowCountML = oci_fetch_row($resultCountML);
						$CountML = $rowCountML[0];
						//echo $CountML;
						if($CountML >= 1){
							$transTime = strtotime($vTanggalTrans);
							$sisaLembur = 0;
							$hariskr=date('d', $transTime);
							$bulanskr=date('m', $transTime);
							$tahunskr=date('Y', $transTime);
							
							if($hariskr >= 21){
								$bulandepan = $bulanskr + 1;
								if($bulandepan == 13){
									$bulandepan = "01";
									$tahundepan = $tahunskr + 1;
								} else {
									if(strlen($bulandepan) == 1){
										$bulandepan = "0" . $bulandepan;
									}
									$tahundepan = $tahunskr;
								}
								$periodeAwal = $tahunskr . "-" . $bulanskr . "-21";
								$periodeAkhir = $tahundepan . "-" . $bulandepan . "-20";
							} else {
								$bulanlalu = $bulanskr - 1;
								if($bulanlalu == 0){
									$bulanlalu = "12";
									$tahunlalu = $tahunskr - 1;
								} else {
									if(strlen($bulanlalu) == 1){
										$bulanlalu = "0" . $bulanlalu;
									}
									$tahunlalu = $tahunskr;
								}
								$periodeAwal = $tahunlalu . "-" . $bulanlalu . "-20";
								$periodeAkhir =  $tahunskr . "-" . $bulanskr . "-21";
							}
							
							$queryCountML = "SELECT MMJ.JUMLAH_LEMBUR * 60 AS JUMLAH_LEMBUR
							FROM APPS.PER_PEOPLE_F PPF
							INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
							INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = 0
							WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
							//echo $queryCountSPL;
							$resultCountML = oci_parse($con,$queryCountML);
							oci_execute($resultCountML);
							$rowCountML = oci_fetch_row($resultCountML);
							$JamLembur = $rowCountML[0];
							$SudahLembur = 0;
							$TotalLembur = 0;
							//echo $JamLembur;
							$queryCountLembur = "SELECT COUNT(-1)
							FROM MJ.MJ_T_TIMECARD
							WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
							AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
							AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
							AND PERSON_ID=$vPerson_id";
							//echo $queryCountSPL;
							$resultCountLembur = oci_parse($con,$queryCountLembur);
							oci_execute($resultCountLembur);
							$rowCountLembur = oci_fetch_row($resultCountLembur);
							$CountLembur = $rowCountLembur[0];
							
							if($CountLembur >= 1){
								$queryCountLembur = "SELECT JAM_MASUK, JAM_KELUAR
								FROM MJ.MJ_T_TIMECARD
								WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
								AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
								AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
								AND PERSON_ID=$vPerson_id";
								//echo $queryCountSPL;
								$resultCountLembur = oci_parse($con,$queryCountLembur);
								oci_execute($resultCountLembur);
								while($rowSudahLembur = oci_fetch_row($resultCountLembur))
								{
									$TempJamMasukAsli = $rowSudahLembur[0]; 
									$TempJamKeluarAsli = $rowSudahLembur[1]; 
									$ArrTempMasuk = explode(":", $TempJamMasukAsli);
									$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
									$vTempJamMasuk = $ArrTempMasuk[0];
									$vTempJamMasuk = ($vTempJamMasuk * 60) + 60;
									$vTempMenitMasuk = $ArrTempMasuk[1];
									$vTempMenitMasuk = $vTempMenitMasuk + $vTempJamMasuk;
									$vTempJamKeluar = $ArrTempKeluar[0];
									$vTempJamKeluar = $vTempJamKeluar * 60;
									$vTempMenitKeluar = $ArrTempKeluar[1];
									$vTempMenitKeluar = $vTempMenitKeluar + $vTempJamKeluar;
									$tempMenit = $vTempMenitKeluar - $vTempMenitMasuk;
									$SudahLembur = $SudahLembur + $tempMenit;
								}
							}
							$TotalLembur = $JamLembur - $SudahLembur;
							//echo $SudahLembur;
							if ($TotalLembur >= 0){
								$queryCountSPL = "SELECT MTSD.JAM_FROM, MTSD.JAM_TO
								FROM MJ.MJ_T_SPL MTS
								INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
								WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE)
								AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')='$vTanggalTrans'
								AND MTSD.STATUS_DOK = 'Approved'";
								$resultSIK = oci_parse($con,$queryCountSPL);
								oci_execute($resultSIK);
								$rowSIK = oci_fetch_row($resultSIK);
								$TempJamMasukAsli = $rowSIK[0]; 
								$TempJamKeluarAsli = $rowSIK[1]; 
								$ArrTempMasuk = explode(":", $TempJamMasukAsli);
								$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
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
								if(str_replace(':', '', $xJamKeluarAsli) > str_replace(':', '', $vJamKeluar)){
									$ArrTempMasuk = explode(":", $vJamMasuk);
									$ArrTempKeluar = explode(":", $vJamKeluar);
									$vTempJamMasuk = $ArrTempMasuk[0];
									$vTempMenitMasuk = $ArrTempMasuk[1];
									$vTempJamKeluar = $ArrTempKeluar[0];
									$vTempMenitKeluar = $ArrTempKeluar[1];
									$JumJamMasuk = $vTempJamMasuk * 60;
									$JumMenitMasuk = $vTempMenitMasuk + $JumJamMasuk;
									$JumJamKeluar = $vTempJamKeluar * 60;
									$JumMenitKeluar = $vTempMenitKeluar + $JumJamKeluar;
									$xJamKeluarAsli = $vJamKeluar;
								} else {
									$Arrhasil = explode(".", $vTempMenitKeluar);
									$vTempMenitKeluar = $Arrhasil[0];
									$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
									$JumJamMasuk = $vTempJamMasuk * 60;
									$JumMenitMasuk = $vTempMenitMasuk + $JumJamMasuk;
									$JumJamKeluar = $vTempJamKeluar * 60;
									$JumMenitKeluar = $vTempMenitKeluar + $JumJamKeluar;
								}
								$tempMenit = $JumMenitKeluar - $JumMenitMasuk;
								if($tempMenit <= $TotalLembur){
									$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
								} else {
									$JumMenitMasuk = $JumMenitMasuk + $TotalLembur;
									$vTempJamKeluar = $JumMenitMasuk / 60;
									$Arrhasil = explode(".", $vTempJamKeluar);
									$vTempJamKeluar = $Arrhasil[0];
									if(strlen($vTempJamKeluar) == 1){
										$vTempJamKeluar = "0".$vTempJamKeluar;
									}
									$vTempMenitKeluar = $JumMenitMasuk % 60;
									$Arrhasil = explode(".", $vTempMenitKeluar);
									$vTempMenitKeluar = $Arrhasil[0];
									if(strlen($vTempMenitKeluar) == 1){
										$vTempMenitKeluar = "0".$vTempMenitKeluar;
									}
									$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
									$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
								}
								//echo $queryUpload;
								$resultUpload = oci_parse($con,$queryUpload);
								$oe = oci_execute($resultUpload);
								if($oe){
									$queryUpdate = "UPDATE MJ.MJ_T_TIMECARD SET STATUS_SYNC='Sudah', LAST_UPDATED_BY=24, LAST_UPDATED_DATE=SYSDATE WHERE ID=$vID";
									//echo $query;
									$resultUpdate = oci_parse($con,$queryUpdate);
									$oeu = oci_execute($resultUpdate);
									// if($oeu){
										// echo "ID: " . $vID . " Terupdate.";
										// echo "<br>";
									// } else {
										// echo $queryUpdate;
										// echo "<br>";
									// }
								}
							}
						} else {
							$queryCountSPL = "SELECT MTSD.JAM_FROM, MTSD.JAM_TO
							FROM MJ.MJ_T_SPL MTS
							INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
							WHERE MTSD.NAMA = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE)
							AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')='$vTanggalTrans'
							AND MTSD.STATUS_DOK = 'Approved'";
							//echo $queryCountSPL;
							$resultSIK = oci_parse($con,$queryCountSPL);
							oci_execute($resultSIK);
							$rowSIK = oci_fetch_row($resultSIK);
							$TempJamMasukAsli = $rowSIK[0]; 
							$TempJamKeluarAsli = $rowSIK[1]; 
							$ArrTempMasuk = explode(":", $TempJamMasukAsli);
							$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
							$vTempJamMasuk = $ArrTempMasuk[0];
							$vTempMenitMasuk = $ArrTempMasuk[1];
							$vTempJamKeluar = $ArrTempKeluar[0];
							$vTempMenitKeluar = round($ArrTempKeluar[1]);
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
							if($vTempMenitKeluar == "60"){
								$vTempMenitKeluar = "00";
								$vTempJamKeluar = $vTempJamKeluar + 1;
							}
							$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
							
							//echo $xJamKeluarAsli;
							
							if(str_replace(':', '', $xJamKeluarAsli) > str_replace(':', '', $vJamKeluar)){
								$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '$vTglKeluar', '$vStatus')";
								//echo "1" . $queryUpload;
							} else {
								$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
								//echo "2" . $queryUpload;
							}
							$resultUpload = oci_parse($con,$queryUpload);
							$oe = oci_execute($resultUpload);
							if($oe){
								$queryUpdate = "UPDATE MJ.MJ_T_TIMECARD SET STATUS_SYNC='Sudah', LAST_UPDATED_BY=24, LAST_UPDATED_DATE=SYSDATE WHERE ID=$vID";
								//echo $query;
								$resultUpdate = oci_parse($con,$queryUpdate);
								$oeu = oci_execute($resultUpdate);
								// if($oeu){
									// echo "ID: " . $vID . " Terupdate.";
									// echo "<br>";
								// } else {
									// echo $queryUpdate;
									// echo "<br>";
								// }
							} 
						}
					}
				} else {
					$queryUpdate = "UPDATE MJ.MJ_T_TIMECARD SET STATUS_SYNC='Bukan', LAST_UPDATED_BY=24, LAST_UPDATED_DATE=SYSDATE WHERE ID=$vID";
					//echo $query;
					$resultUpdate = oci_parse($con,$queryUpdate);
					$oeu = oci_execute($resultUpdate);
				}
			} else {
				$queryUpdate = "UPDATE MJ.MJ_T_TIMECARD SET STATUS_SYNC='Bukan', LAST_UPDATED_BY=24, LAST_UPDATED_DATE=SYSDATE WHERE ID=$vID";
				//echo $query;
				$resultUpdate = oci_parse($con,$queryUpdate);
				$oeu = oci_execute($resultUpdate);
			}
		}
	}
	
	// //========================= SPL yang tidak ada di TIMECARD ============================================
	$queryTimecard = "SELECT COUNT(-1)
	FROM MJ.MJ_T_SPL MTS
	INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.FULL_NAME=MTSD.NAMA AND PPF.EFFECTIVE_END_DATE > SYSDATE
	AND UPPER(PPF.CURRENT_EMPLOYEE_FLAG) = 'Y'
    INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
	LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.PERSON_ID=PPF.PERSON_ID AND MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253
	WHERE TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')>='$periode1'
	AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')<='$periode2'
	AND MTSD.STATUS_DOK = 'Approved'
	AND MTT.ID IS NULL
	AND PAF.PAYROLL_ID IS NOT NULL
	AND PAF.PEOPLE_GROUP_ID <> 3061";
	//echo $queryTimecard;
	$resultTimecard = oci_parse($con,$queryTimecard);
	oci_execute($resultTimecard);
	$rowTimecard = oci_fetch_row($resultTimecard);
	$vTimecard = $rowTimecard[0];
	
	if($vTimecard >= 1){
		$querySPLNew = "SELECT MTSD.ID
		, PPF.PERSON_ID
		, TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD')
		, MTSD.JAM_FROM
		, MTSD.JAM_TO
		, TO_CHAR(MTS.TANGGAL_SPL + 1, 'YYYY-MM-DD') TGL_BESOK
		FROM MJ.MJ_T_SPL MTS
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.FULL_NAME=MTSD.NAMA AND PPF.EFFECTIVE_END_DATE > SYSDATE
		AND UPPER(PPF.CURRENT_EMPLOYEE_FLAG) = 'Y'
		INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.PERSON_ID=PPF.PERSON_ID AND MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253
		WHERE TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')>='$periode1'
		AND TO_CHAR(TANGGAL_SPL, 'YYYY-MM-DD')<='$periode2'
		AND MTSD.STATUS_DOK = 'Approved'
		AND MTT.ID IS NULL
		AND PAF.PEOPLE_GROUP_ID <> 3061";
		$resultSPLNew = oci_parse($con, $querySPLNew);
		oci_execute($resultSPLNew);
		while($rowSPLNew = oci_fetch_row($resultSPLNew))
		{
			$vID=$rowSPLNew[0];
			$vPerson_id=$rowSPLNew[1];
			$vTanggalTrans=$rowSPLNew[2];
			$vJamMasuk=$rowSPLNew[3];
			$vJamKeluar=$rowSPLNew[4];
			$vTanggalBesok=$rowSPLNew[5];
			$vStatus=253;
			
			$queryCountML = "SELECT COUNT(-1)
			FROM APPS.PER_PEOPLE_F PPF
			INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
			INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = PAF.POSITION_ID
			WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
			//echo $queryCountSPL;
			$resultCountML = oci_parse($con,$queryCountML);
			oci_execute($resultCountML);
			$rowCountML = oci_fetch_row($resultCountML);
			$CountML = $rowCountML[0];
			
			if($CountML >= 1){
				$transTime = strtotime($vTanggalTrans);
				$sisaLembur = 0;
				$hariskr=date('d', $transTime);
				$bulanskr=date('m', $transTime);
				$tahunskr=date('Y', $transTime);
				
				if($hariskr >= 21){
					$bulandepan = $bulanskr + 1;
					if($bulandepan == 13){
						$bulandepan = "01";
						$tahundepan = $tahunskr + 1;
					} else {
						if(strlen($bulandepan) == 1){
							$bulandepan = "0" . $bulandepan;
						}
						$tahundepan = $tahunskr;
					}
					$periodeAwal = $tahunskr . "-" . $bulanskr . "-21";
					$periodeAkhir = $tahundepan . "-" . $bulandepan . "-20";
				} else {
					$bulanlalu = $bulanskr - 1;
					if($bulanlalu == 0){
						$bulanlalu = "12";
						$tahunlalu = $tahunskr - 1;
					} else {
						if(strlen($bulanlalu) == 1){
							$bulanlalu = "0" . $bulanlalu;
						}
						$tahunlalu = $tahunskr;
					}
					$periodeAwal = $tahunlalu . "-" . $bulanlalu . "-20";
					$periodeAkhir =  $tahunskr . "-" . $bulanskr . "-21";
				}
				
				$queryCountML = "SELECT MMJ.JUMLAH_LEMBUR * 60 AS JUMLAH_LEMBUR
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
				INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = PAF.POSITION_ID
				WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
				//echo $queryCountSPL;
				$resultCountML = oci_parse($con,$queryCountML);
				oci_execute($resultCountML);
				$rowCountML = oci_fetch_row($resultCountML);
				$JamLembur = $rowCountML[0];
				$SudahLembur = 0;
				$TotalLembur = 0;
				$TotalLemburTambahan = 0;
				$queryUploadTambahan = "";
				
				$queryCountLembur = "SELECT COUNT(-1)
				FROM MJ.MJ_T_TIMECARD
				WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
				AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
				AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
				AND PERSON_ID=$vPerson_id";
				//echo $queryCountSPL;
				$resultCountLembur = oci_parse($con,$queryCountLembur);
				oci_execute($resultCountLembur);
				$rowCountLembur = oci_fetch_row($resultCountLembur);
				$CountLembur = $rowCountLembur[0];
				
				if($CountLembur >= 1){
					$queryCountLembur = "SELECT JAM_MASUK, JAM_KELUAR
					FROM MJ.MJ_T_TIMECARD
					WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
					AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
					AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
					AND PERSON_ID=$vPerson_id";
					//echo $queryCountSPL;
					$resultCountLembur = oci_parse($con,$queryCountLembur);
					oci_execute($resultCountLembur);
					while($rowSudahLembur = oci_fetch_row($resultCountLembur))
					{
						$TempJamMasukAsli = $rowSudahLembur[0]; 
						$TempJamKeluarAsli = $rowSudahLembur[1]; 
						$ArrTempMasuk = explode(":", $TempJamMasukAsli);
						$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
						$vTempJamMasuk = $ArrTempMasuk[0];
						$vTempJamMasuk = ($vTempJamMasuk * 60) + 60;
						$vTempMenitMasuk = $ArrTempMasuk[1];
						$vTempMenitMasuk = $vTempMenitMasuk + $vTempJamMasuk;
						$vTempJamKeluar = $ArrTempKeluar[0];
						$vTempJamKeluar = $vTempJamKeluar * 60;
						$vTempMenitKeluar = $ArrTempKeluar[1];
						$vTempMenitKeluar = $vTempMenitKeluar + $vTempJamKeluar;
						$tempMenit = $vTempMenitKeluar - $vTempMenitMasuk;
						$SudahLembur = $SudahLembur + $tempMenit;
					}
				}
				$TotalLembur = $JamLembur - $SudahLembur;
				if ($TotalLembur >= 0){
					$TempJamMasukAsli = $vJamMasuk; 
					$TempJamKeluarAsli = $vJamKeluar; 
					$ArrTempMasuk = explode(":", $TempJamMasukAsli);
					$vTempJamMasuk = $ArrTempMasuk[0];
					$vTempMenitMasuk = $ArrTempMasuk[1];
					if(strlen($vTempJamMasuk) == 1){
						$vTempJamMasuk = "0".$vTempJamMasuk;
					}
					if(strlen($vTempMenitMasuk) == 1){
						$vTempMenitMasuk = "0".$vTempMenitMasuk;
					}
					$xJamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
					$JumJamMasuk = $vTempJamMasuk * 60;
					$JumMenitMasuk = $vTempMenitMasuk + $JumJamMasuk;
					$ArrTempKeluarCek = explode(":", $TempJamKeluarAsli);
					if (str_replace(":", "", $ArrTempKeluarCek[0]) > "23" ){
							$TempJamKeluarAsli = "23:59";
							$ArrTempKeluarTambahan = explode(":", $vJamKeluar);
							$vTempJamKeluarTambahan = $ArrTempKeluarTambahan[0] - 24;
							$vTempMenitKeluarTambahan = $ArrTempKeluarTambahan[1] + 1;
							$JumJamKeluarTambahan = $vTempJamKeluarTambahan * 60;
							$JumMenitKeluarTambahan = $vTempMenitKeluarTambahan + $JumJamKeluarTambahan;
							
							if(strlen($vTempJamKeluarTambahan) == 1){
								$vTempJamKeluarTambahan = "0".$vTempJamKeluarTambahan;
							}
							if(strlen($vTempMenitKeluarTambahan) == 1){
								$vTempMenitKeluarTambahan = "0".$vTempMenitKeluarTambahan;
							}
							$Arrhasil = explode(".", $vTempMenitKeluarTambahan);
							$vTempMenitKeluarTambahan = $Arrhasil[0];
							$xJamKeluarTambahan = $vTempJamKeluarTambahan . ":" . $vTempMenitKeluarTambahan;
					}
					$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
					$vTempJamKeluar = $ArrTempKeluar[0];
					$vTempMenitKeluar = $ArrTempKeluar[1];
					if(strlen($vTempJamKeluar) == 1){
						$vTempJamKeluar = "0".$vTempJamKeluar;
					}
					if(strlen($vTempMenitKeluar) == 1){
						$vTempMenitKeluar = "0".$vTempMenitKeluar;
					}
					$Arrhasil = explode(".", $vTempMenitKeluar);
					$vTempMenitKeluar = $Arrhasil[0];
					$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
					$JumJamKeluar = $vTempJamKeluar * 60;
					$JumMenitKeluar = $vTempMenitKeluar + $JumJamKeluar;
					$tempMenit = $JumMenitKeluar - $JumMenitMasuk;
					$TotalLemburTambahan = $TotalLembur - $tempMenit;
					if($tempMenit <= $TotalLembur){
						$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
						if($JumMenitKeluarTambahan >= 1){
							if($JumMenitKeluarTambahan <= $TotalLemburTambahan){
								$queryUploadTambahan = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalBesok . " 00:00:00', '" . $vTanggalBesok . " " . $xJamKeluarTambahan . ":00', '$vStatus')";
							} else {
								$vTempJamKeluarTambahan = $JumMenitKeluarTambahan / 60;
								$Arrhasil = explode(".", $vTempJamKeluarTambahan);
								$vTempJamKeluarTambahan = $Arrhasil[0];
								if(strlen($vTempJamKeluarTambahan) == 1){
									$vTempJamKeluarTambahan = "0".$vTempJamKeluarTambahan;
								}
								$vTempMenitKeluarTambahan = $JumMenitKeluarTambahan % 60;
								$Arrhasil = explode(".", $vTempMenitKeluarTambahan);
								$vTempMenitKeluarTambahan = $Arrhasil[0];
								if(strlen($vTempMenitKeluarTambahan) == 1){
									$vTempMenitKeluarTambahan = "0".$vTempMenitKeluarTambahan;
								}
								$xJamKeluarTambahan = $vTempJamKeluarTambahan . ":" . $vTempMenitKeluarTambahan;
								$queryUploadTambahan = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalBesok . " 00:00:00', '" . $vTanggalBesok . " " . $xJamKeluarTambahan . ":00', '$vStatus')";
							}
						}
					} else {
						$JumMenitMasuk = $JumMenitMasuk + $TotalLembur;
						$vTempJamKeluar = $JumMenitMasuk / 60;
						$Arrhasil = explode(".", $vTempJamKeluar);
						$vTempJamKeluar = $Arrhasil[0];
						if(strlen($vTempJamKeluar) == 1){
							$vTempJamKeluar = "0".$vTempJamKeluar;
						}
						$vTempMenitKeluar = $JumMenitMasuk % 60;
						$Arrhasil = explode(".", $vTempMenitKeluar);
						$vTempMenitKeluar = $Arrhasil[0];
						if(strlen($vTempMenitKeluar) == 1){
							$vTempMenitKeluar = "0".$vTempMenitKeluar;
						}
						$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
						$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
					}
					//echo $queryUpload;
					$resultUpload = oci_parse($con,$queryUpload);
					$oe = oci_execute($resultUpload);
					if($oe){
						$queryUpdate = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, LAST_UPDATED_BY, LAST_UPDATED_DATE)
						VALUES (MJ.MJ_T_TIMECARD_SEQ.NEXTVAL, " . APPCODE . ", $vPerson_id, TO_DATE('$vTanggalTrans', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$vStatus', 'Sudah', 25, SYSDATE, 25, SYSDATE)";
						//echo $query;
						$resultUpdate = oci_parse($con,$queryUpdate);
						$oeu = oci_execute($resultUpdate);
						
						echo $queryUploadTambahan . "Data 1. ";
						if($oeu && $queryUploadTambahan != ""){
							$resultUploadTambahan = oci_parse($con,$queryUploadTambahan);
							$oeTambahan = oci_execute($resultUploadTambahan);
							if (str_replace(":", "", $ArrTempKeluarCek[0]) > "23" && $oeTambahan){
								$queryUpdateTambahan = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, LAST_UPDATED_BY, LAST_UPDATED_DATE)
								VALUES (MJ.MJ_T_TIMECARD_SEQ.NEXTVAL, " . APPCODE . ", $vPerson_id, TO_DATE('$vTanggalBesok', 'YYYY-MM-DD'), '00:00', '$xJamKeluarTambahan', '$vStatus', 'Sudah', 25, SYSDATE, 25, SYSDATE)";
								//echo $query;
								$resultUpdateTambahan = oci_parse($con,$queryUpdateTambahan);
								$oeuTambahan = oci_execute($resultUpdateTambahan);
							}	
						}
					}
				}
			} else {
				$queryCountML = "SELECT COUNT(-1)
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
				INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = 0
				WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
				//echo $queryCountSPL;
				$resultCountML = oci_parse($con,$queryCountML);
				oci_execute($resultCountML);
				$rowCountML = oci_fetch_row($resultCountML);
				$CountML = $rowCountML[0];
				
				if($CountML >= 1){
					$transTime = strtotime($vTanggalTrans);
					$sisaLembur = 0;
					$hariskr=date('d', $transTime);
					$bulanskr=date('m', $transTime);
					$tahunskr=date('Y', $transTime);
					
					if($hariskr >= 21){
						$bulandepan = $bulanskr + 1;
						if($bulandepan == 13){
							$bulandepan = "01";
							$tahundepan = $tahunskr + 1;
						} else {
							if(strlen($bulandepan) == 1){
								$bulandepan = "0" . $bulandepan;
							}
							$tahundepan = $tahunskr;
						}
						$periodeAwal = $tahunskr . "-" . $bulanskr . "-21";
						$periodeAkhir = $tahundepan . "-" . $bulandepan . "-20";
					} else {
						$bulanlalu = $bulanskr - 1;
						if($bulanlalu == 0){
							$bulanlalu = "12";
							$tahunlalu = $tahunskr - 1;
						} else {
							if(strlen($bulanlalu) == 1){
								$bulanlalu = "0" . $bulanlalu;
							}
							$tahunlalu = $tahunskr;
						}
						$periodeAwal = $tahunlalu . "-" . $bulanlalu . "-20";
						$periodeAkhir =  $tahunskr . "-" . $bulanskr . "-21";
					}
					
					$queryCountML = "SELECT MMJ.JUMLAH_LEMBUR * 60 AS JUMLAH_LEMBUR
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
					INNER JOIN MJ.MJ_M_JAMLEMBUR MMJ ON MMJ.PLANT_ID = PAF.LOCATION_ID AND MMJ.POSITION_ID = 0
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.PERSON_ID=$vPerson_id AND MMJ.STATUS='A'";
					//echo $queryCountSPL;
					$resultCountML = oci_parse($con,$queryCountML);
					oci_execute($resultCountML);
					$rowCountML = oci_fetch_row($resultCountML);
					$JamLembur = $rowCountML[0];
					$SudahLembur = 0;
					$TotalLembur = 0;
					$TotalLemburTambahan = 0;
					$queryUploadTambahan = "";
					
					$queryCountLembur = "SELECT COUNT(-1)
					FROM MJ.MJ_T_TIMECARD
					WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
					AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
					AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
					AND PERSON_ID=$vPerson_id";
					//echo $queryCountSPL;
					$resultCountLembur = oci_parse($con,$queryCountLembur);
					oci_execute($resultCountLembur);
					$rowCountLembur = oci_fetch_row($resultCountLembur);
					$CountLembur = $rowCountLembur[0];
					
					if($CountLembur >= 1){
						$queryCountLembur = "SELECT JAM_MASUK, JAM_KELUAR
						FROM MJ.MJ_T_TIMECARD
						WHERE APP_ID=" . APPCODE . " AND STATUS_SYNC='Sudah' AND STATUS=253
						AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') >= '$periodeAwal'
						AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') <= '$periodeAkhir'
						AND PERSON_ID=$vPerson_id";
						//echo $queryCountSPL;
						$resultCountLembur = oci_parse($con,$queryCountLembur);
						oci_execute($resultCountLembur);
						while($rowSudahLembur = oci_fetch_row($resultCountLembur))
						{
							$TempJamMasukAsli = $rowSudahLembur[0]; 
							$TempJamKeluarAsli = $rowSudahLembur[1]; 
							$ArrTempMasuk = explode(":", $TempJamMasukAsli);
							$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
							$vTempJamMasuk = $ArrTempMasuk[0];
							$vTempJamMasuk = ($vTempJamMasuk * 60) + 60;
							$vTempMenitMasuk = $ArrTempMasuk[1];
							$vTempMenitMasuk = $vTempMenitMasuk + $vTempJamMasuk;
							$vTempJamKeluar = $ArrTempKeluar[0];
							$vTempJamKeluar = $vTempJamKeluar * 60;
							$vTempMenitKeluar = $ArrTempKeluar[1];
							$vTempMenitKeluar = $vTempMenitKeluar + $vTempJamKeluar;
							$tempMenit = $vTempMenitKeluar - $vTempMenitMasuk;
							$SudahLembur = $SudahLembur + $tempMenit;
						}
					}
					$TotalLembur = $JamLembur - $SudahLembur;
					if ($TotalLembur >= 0){
						$TempJamMasukAsli = $vJamMasuk; 
						$TempJamKeluarAsli = $vJamKeluar; 
						$ArrTempMasuk = explode(":", $TempJamMasukAsli);
						$vTempJamMasuk = $ArrTempMasuk[0];
						$vTempMenitMasuk = $ArrTempMasuk[1];
						if(strlen($vTempJamMasuk) == 1){
							$vTempJamMasuk = "0".$vTempJamMasuk;
						}
						if(strlen($vTempMenitMasuk) == 1){
							$vTempMenitMasuk = "0".$vTempMenitMasuk;
						}
						$xJamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
						$JumJamMasuk = $vTempJamMasuk * 60;
						$JumMenitMasuk = $vTempMenitMasuk + $JumJamMasuk;
						$ArrTempKeluarCek = explode(":", $TempJamKeluarAsli);
						
						//echo str_replace(":", "", $ArrTempKeluarCek[0]);
						
						if (str_replace(":", "", $ArrTempKeluarCek[0]) > "23"){
								$TempJamKeluarAsli = "23:59";
								$ArrTempKeluarTambahan = explode(":", $vJamKeluar);
								$vTempJamKeluarTambahan = $ArrTempKeluarTambahan[0] - 24;
								$vTempMenitKeluarTambahan = $ArrTempKeluarTambahan[1] + 1;
								//echo $vTempMenitKeluarTambahan;
								$JumJamKeluarTambahan = $vTempJamKeluarTambahan * 60;
								$JumMenitKeluarTambahan = $vTempMenitKeluarTambahan + $JumJamKeluarTambahan;
								
								if(strlen($vTempJamKeluarTambahan) == 1){
									$vTempJamKeluarTambahan = "0".$vTempJamKeluarTambahan;
								}
								if(strlen($vTempMenitKeluarTambahan) == 1){
									$vTempMenitKeluarTambahan = "0".$vTempMenitKeluarTambahan;
								}
								$Arrhasil = explode(".", $vTempMenitKeluarTambahan);
								$vTempMenitKeluarTambahan = $Arrhasil[0];
								$xJamKeluarTambahan = $vTempJamKeluarTambahan . ":" . $vTempMenitKeluarTambahan;
						}
						$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
						$vTempJamKeluar = $ArrTempKeluar[0];
						$vTempMenitKeluar = $ArrTempKeluar[1];
						if(strlen($vTempJamKeluar) == 1){
							$vTempJamKeluar = "0".$vTempJamKeluar;
						}
						if(strlen($vTempMenitKeluar) == 1){
							$vTempMenitKeluar = "0".$vTempMenitKeluar;
						}
						$Arrhasil = explode(".", $vTempMenitKeluar);
						$vTempMenitKeluar = $Arrhasil[0];
						$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
						$JumJamKeluar = $vTempJamKeluar * 60;
						$JumMenitKeluar = $vTempMenitKeluar + $JumJamKeluar;
						$tempMenit = $JumMenitKeluar - $JumMenitMasuk;
						$TotalLemburTambahan = $TotalLembur - $tempMenit;
						echo " Total Lembur Tambahan: ". $TotalLemburTambahan;
						if($tempMenit <= $TotalLembur){
							$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
							echo "Total MEnit Tambahan : " . $JumMenitKeluarTambahan ;
							if($JumMenitKeluarTambahan >= 1){
								if($JumMenitKeluarTambahan <= $TotalLemburTambahan){
									$queryUploadTambahan = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalBesok . " 00:00:00', '" . $vTanggalBesok . " " . $xJamKeluarTambahan . ":00', '$vStatus')";
								} else {
									$vTempJamKeluarTambahan = $JumMenitKeluarTambahan / 60;
									$Arrhasil = explode(".", $vTempJamKeluarTambahan);
									$vTempJamKeluarTambahan = $Arrhasil[0];
									if(strlen($vTempJamKeluarTambahan) == 1){
										$vTempJamKeluarTambahan = "0".$vTempJamKeluarTambahan;
									}
									$vTempMenitKeluarTambahan = $JumMenitKeluarTambahan % 60;
									$Arrhasil = explode(".", $vTempMenitKeluarTambahan);
									$vTempMenitKeluarTambahan = $Arrhasil[0];
									if(strlen($vTempMenitKeluarTambahan) == 1){
										$vTempMenitKeluarTambahan = "0".$vTempMenitKeluarTambahan;
									}
									$xJamKeluarTambahan = $vTempJamKeluarTambahan . ":" . $vTempMenitKeluarTambahan;
									$queryUploadTambahan = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalBesok . " 00:00:00', '" . $vTanggalBesok . " " . $xJamKeluarTambahan . ":00', '$vStatus')";
								}
							}
						} else {
							$JumMenitMasuk = $JumMenitMasuk + $TotalLembur;
							$vTempJamKeluar = $JumMenitMasuk / 60;
							$Arrhasil = explode(".", $vTempJamKeluar);
							$vTempJamKeluar = $Arrhasil[0];
							if(strlen($vTempJamKeluar) == 1){
								$vTempJamKeluar = "0".$vTempJamKeluar;
							}
							$vTempMenitKeluar = $JumMenitMasuk % 60;
							$Arrhasil = explode(".", $vTempMenitKeluar);
							$vTempMenitKeluar = $Arrhasil[0];
							if(strlen($vTempMenitKeluar) == 1){
								$vTempMenitKeluar = "0".$vTempMenitKeluar;
							}
							$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
							$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
						}
						//echo $queryUpload;
						$resultUpload = oci_parse($con,$queryUpload);
						$oe = oci_execute($resultUpload);
						if($oe){
							$queryUpdate = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, LAST_UPDATED_BY, LAST_UPDATED_DATE)
							VALUES (MJ.MJ_T_TIMECARD_SEQ.NEXTVAL, " . APPCODE . ", $vPerson_id, TO_DATE('$vTanggalTrans', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$vStatus', 'Sudah', 25, SYSDATE, 25, SYSDATE)";
							//echo $query;
							$resultUpdate = oci_parse($con,$queryUpdate);
							$oeu = oci_execute($resultUpdate);
							
							//echo $queryUploadTambahan . "Data 2. ";
							if($oeu && $queryUploadTambahan != ""){
								$resultUploadTambahan = oci_parse($con,$queryUploadTambahan);
								$oeTambahan = oci_execute($resultUploadTambahan);
								if(str_replace(":", "", $ArrTempKeluarCek[0]) > "23" && $oeTambahan){
									$queryUpdateTambahan = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, LAST_UPDATED_BY, LAST_UPDATED_DATE)
									VALUES (MJ.MJ_T_TIMECARD_SEQ.NEXTVAL, " . APPCODE . ", $vPerson_id, TO_DATE('$vTanggalBesok', 'YYYY-MM-DD'), '00:00', '$xJamKeluarTambahan', '$vStatus', 'Sudah', 25, SYSDATE, 25, SYSDATE)";
									//echo $query;
									$resultUpdateTambahan = oci_parse($con,$queryUpdateTambahan);
									$oeuTambahan = oci_execute($resultUpdateTambahan);
								}
							}
						}
					}
				} else {
					$queryUploadTambahan = "";
					$TempJamMasukAsli = $vJamMasuk; 
					$TempJamKeluarAsli = $vJamKeluar; 
					$ArrTempMasuk = explode(":", $TempJamMasukAsli);
					$vTempJamMasuk = $ArrTempMasuk[0];
					$vTempMenitMasuk = $ArrTempMasuk[1];
					if(strlen($vTempJamMasuk) == 1){
						$vTempJamMasuk = "0".$vTempJamMasuk;
					}
					if(strlen($vTempMenitMasuk) == 1){
						$vTempMenitMasuk = "0".$vTempMenitMasuk;
					}
					$xJamMasukAsli = $vTempJamMasuk . ":" . $vTempMenitMasuk;
					$ArrTempKeluarCek = explode(":", $TempJamKeluarAsli);
					if (str_replace(":", "", $ArrTempKeluarCek[0]) > "23" ){
						$TempJamKeluarAsli = "23:59";
						$ArrTempKeluarTambahan = explode(":", $vJamKeluar);
						$vTempJamKeluarTambahan = $ArrTempKeluarTambahan[0] - 24;
						$vTempMenitKeluarTambahan = $ArrTempKeluarTambahan[1] + 1;
						
						if(strlen($vTempJamKeluarTambahan) == 1){
							$vTempJamKeluarTambahan = "0".$vTempJamKeluarTambahan;
						}
						if(strlen($vTempMenitKeluarTambahan) == 1){
							$vTempMenitKeluarTambahan = "0".$vTempMenitKeluarTambahan;
						}
						$Arrhasil = explode(".", $vTempMenitKeluarTambahan);
						$vTempMenitKeluarTambahan = $Arrhasil[0];
						$xJamKeluarTambahan = $vTempJamKeluarTambahan . ":" . $vTempMenitKeluarTambahan;
					}
					$ArrTempKeluar = explode(":", $TempJamKeluarAsli);
					$vTempJamKeluar = $ArrTempKeluar[0];
					$vTempMenitKeluar = round($ArrTempKeluar[1]);
					if(strlen($vTempJamKeluar) == 1){
						$vTempJamKeluar = "0".$vTempJamKeluar;
					}
					if(strlen($vTempMenitKeluar) == 1){
						$vTempMenitKeluar = "0".$vTempMenitKeluar;
					}
					if($vTempMenitKeluar == "60"){
						$vTempMenitKeluar = "00";
						$vTempJamKeluar = $vTempJamKeluar + 1;
					}
					$xJamKeluarAsli = $vTempJamKeluar . ":" . $vTempMenitKeluar;
					
					$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalTrans . " " . $xJamMasukAsli . ":00', '" . $vTanggalTrans . " " . $xJamKeluarAsli . ":00', '$vStatus')";
					if (str_replace(":", "", $TempJamKeluarAsli) > "23" ){
						$queryUploadTambahan = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '" . $vTanggalBesok . " 00:00:00', '" . $vTanggalBesok . " " . $xJamKeluarTambahan . ":00', '$vStatus')";
					}
					$resultUpload = oci_parse($con,$queryUpload);
					$oe = oci_execute($resultUpload);
					if($oe){
						$queryUpdate = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, LAST_UPDATED_BY, LAST_UPDATED_DATE)
						VALUES (MJ.MJ_T_TIMECARD_SEQ.NEXTVAL, " . APPCODE . ", $vPerson_id, TO_DATE('$vTanggalTrans', 'YYYY-MM-DD'), '$xJamMasukAsli', '$xJamKeluarAsli', '$vStatus', 'Sudah', 25, SYSDATE, 25, SYSDATE)";
						//echo $query;
						$resultUpdate = oci_parse($con,$queryUpdate);
						$oeu = oci_execute($resultUpdate);
						
						//echo $queryUploadTambahan . "Data 3. ";
						if($oeu && $queryUploadTambahan != ""){
							$resultUploadTambahan = oci_parse($con,$queryUploadTambahan);
							$oeTambahan = oci_execute($resultUploadTambahan);
							if (str_replace(":", "", $TempJamKeluarAsli) > "23" && $oeTambahan){
								$queryUpdateTambahan = "INSERT INTO MJ.MJ_T_TIMECARD (ID, APP_ID, PERSON_ID, TANGGAL, JAM_MASUK, JAM_KELUAR, STATUS, STATUS_SYNC, CREATED_BY, CREATED_DATE, LAST_UPDATED_BY, LAST_UPDATED_DATE)
								VALUES (MJ.MJ_T_TIMECARD_SEQ.NEXTVAL, " . APPCODE . ", $vPerson_id, TO_DATE('$vTanggalBesok', 'YYYY-MM-DD'), '00:00', '$xJamKeluarTambahan', '$vStatus', 'Sudah', 25, SYSDATE, 25, SYSDATE)";
								//echo $query;
								$resultUpdateTambahan = oci_parse($con,$queryUpdateTambahan);
								$oeuTambahan = oci_execute($resultUpdateTambahan);
							}	
						}
					} 
				}
			}
		}
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