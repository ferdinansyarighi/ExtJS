<?PHP
 include 'E:/dataSource/MJBHRD/main/koneksi2.php'; //Koneksi ke database

 
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

$tahundipakai=0; 
$bulandipakai=0; 
$haridipakai=0; 

$tahundipakai2=0; 
$bulandipakai2=0; 
$haridipakai2=0; 
	
if($hariskr >= 21){
	if(strlen($bulansesudah)==1)
		$bulansesudah="0" . $bulansesudah;
	$periode1 = $tahunskr . "-" . $bulanskr . "-21";
	$tahundipakai=$tahunskr; 
	$bulandipakai=$bulanskr; 
	$haridipakai=21; 
	if($bulanskr=='12'){
		$periode2 = $tahunsesudah . "-01-20";
	} else {
		$periode2 = $tahunskr . "-" . $bulansesudah . "-20";
	}
} else {
	if(strlen($bulansebelum)==1)
		$bulansebelum="0" . $bulansebelum;
	if($bulanskr=='01'){
		$periode1 = $tahunsebelum . "-12-21";
		$tahundipakai=$tahunsebelum; 
		$bulandipakai=$bulansebelum; 
		$haridipakai=21;
	} else {
		$periode1 = $tahunskr . "-" . $bulansebelum . "-21";
		$tahundipakai=$tahunskr; 
		$bulandipakai=$bulansebelum; 
		$haridipakai=21;
	}
	$periode2 = $tahunskr . "-" . $bulanskr . "-20";
}

$periode1 = '2016-05-21';
$periode2 = '2016-06-20';

	$queryTimecard = "SELECT COUNT(-1)
	FROM MJ.MJ_T_TIMECARD MTT
	INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
	WHERE MTT.APP_ID=" . APPCODE . " AND STATUS_SYNC='Belum' AND ELEMENT_NAME IN ('LEMBUR')
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2'
	--AND MTT.PERSON_ID IN (4756)";
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
		WHERE MTT.APP_ID=" . APPCODE . " AND STATUS_SYNC='Belum' AND ELEMENT_NAME IN ('LEMBUR')
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2'
		--AND MTT.PERSON_ID IN (4756)";
		//echo $query;
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
					//echo $queryCountML;
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
							//echo $xJamKeluarAsli;
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
						//echo $queryCountML;
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
								//echo $xJamKeluarAsli . " dan " . $vJamKeluar;
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
								//echo $xJamKeluarAsli . " dan " . $vJamKeluar;
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