<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/importplant/fungsiTimecardxo.php';
// Import Absence, FED Algorithm
 
$hari="";
$bulan="";
$tahun=""; 
$lop = "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$namaFile=""; 
$data="gagal";
$data_value = '';
$HariLibur=0;
$HariIni='';
$Status='';
$DataTgl='';
$JamMasuk='';
$JamKeluar='';
$dataId =0;
$dataTgl='';
$x = 11;
$pengurang = 3;
$pengurangan = 3-1;
$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
if (!$conn) {
	echo "Connection MSSQL Black failed.";
	exit;
}

$queryCountPlant = "SELECT COUNT(-1)
FROM MJ.MJ_M_GROUP_ELEMENT MMGE
    INNER JOIN APPS.HR_LOCATIONS HL ON MMGE.PLANT = HL.LOCATION_ID
    INNER JOIN MJ.MJ_M_LINK_GROUP MMLG ON MMGE.ID = MMLG.ID_GROUP
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON MMLG.ASSIGNMENT_ID = PAF.ASSIGNMENT_ID
    INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND MMLG.PERSON_ID = PPF.PERSON_ID
WHERE MMGE.PERIODE_GAJI = 'MINGGUAN' AND MMGE.STATUS = 'Y'
    AND 
    (
        (
            MMGE.PLANT IN (57849, 146, 148, 166, 92053, 324, 6317, 161, 153, 152)  
            AND PAF.ORGANIZATION_ID = 81
        ) 
        OR PPF.PERSON_ID IN (43183, 43187)
    ) 
    AND MMLG.STATUS = 'Y' AND PAF.PRIMARY_FLAG = 'Y' AND SYSDATE BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE
    AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND SYSDATE BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
ORDER BY HL.LOCATION_CODE, PPF.FULL_NAME
";
$resultCountPLant = oci_parse($con, $queryCountPlant);
oci_execute($resultCountPLant);
$rowCountPlant = oci_fetch_row($resultCountPLant);
$countPlant = $rowCountPlant[0];
// echo $countPlant;exit;

$queryPlant = "SELECT PPF.HONORS
FROM MJ.MJ_M_GROUP_ELEMENT MMGE
    INNER JOIN APPS.HR_LOCATIONS HL ON MMGE.PLANT = HL.LOCATION_ID
    INNER JOIN MJ.MJ_M_LINK_GROUP MMLG ON MMGE.ID = MMLG.ID_GROUP
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON MMLG.ASSIGNMENT_ID = PAF.ASSIGNMENT_ID
    INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND MMLG.PERSON_ID = PPF.PERSON_ID
WHERE MMGE.PERIODE_GAJI = 'MINGGUAN' AND MMGE.STATUS = 'Y'
    AND 
    (
        (
            MMGE.PLANT IN (57849, 146, 148, 166, 92053, 324, 6317, 161, 153, 152)  
            AND PAF.ORGANIZATION_ID = 81
        ) 
        OR PPF.PERSON_ID IN (43183, 43187)
    ) 
    AND MMLG.STATUS = 'Y' AND PAF.PRIMARY_FLAG = 'Y' AND SYSDATE BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE
    AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND SYSDATE BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
ORDER BY HL.LOCATION_CODE, PPF.FULL_NAME
";
$resultPlant = oci_parse($con, $queryPlant);
oci_execute($resultPlant);
$i = 0;
while ($rowPlant = oci_fetch_row($resultPlant)) {
	$honorsPlant = $rowPlant[0];	
	if ($i == $countPlant - 1) { //masuk sini jika data pada posisis terakhir 
		$lop .= $honorsPlant;		
	}else{
		$lop .= $honorsPlant.',';		
	}	
	$i++;	
}

$queryDate = "SELECT SYSDATE - $pengurang FROM DUAL";
$resultDate = oci_parse($con,$queryDate);
oci_execute($resultDate);
$rowDate = oci_fetch_row($resultDate);
$vSdate = $rowDate[0];

		$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -$pengurang, getdate()),120) FROM MastKarya MK 
		WHERE MK.KaryaCode IN ($lop)";
		
		$resultHeader = odbc_exec($conn, $queryHeader);
		while(odbc_fetch_row($resultHeader)){
			$dataId = odbc_result($resultHeader, 1);
			$dataId = trim($dataId," ");
			$TglHeader = substr(odbc_result($resultHeader, 2), 0, 10);
			$JamMasukAsli = '';
			$JamKeluarAsli = '';
			$countIn = 0;
			$cekShiftGanti = "SELECT COUNT(-1)
							  FROM PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF,MJ.MJ_M_CHANGE_SHIFT MCS
							  WHERE PPF.HONORS = '$dataId'
							  AND PPF.PERSON_ID = PAF.PERSON_ID 
							  AND PAF.ASSIGNMENT_ID = MCS.ASSIGNMENT_ID 
							  AND MCS.DATE_DETAIL = TO_DATE('$vSdate')";

			$resultCekGanti = oci_parse($con,$cekShiftGanti);
			oci_execute($resultCekGanti);
			$rowCekGanti = oci_fetch_row($resultCekGanti);
			$CekGanti = $rowCekGanti[0];
			if($CekGanti >= 1)
			{ 
					$cekShift = "SELECT COUNT(-1)
								 FROM PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF,MJ.MJ_M_SHIFT MMS
								 WHERE PPF.HONORS = '$dataId'
								 AND PPF.PERSON_ID = PAF.PERSON_ID 
								 AND PAF.ASSIGNMENT_ID = MMS.ASSIGNMENT_ID 
								 AND MMS.DATE_FROM <= TO_DATE('$vSdate') AND (MMS.DATE_TO >= TO_DATE('$vSdate', 'YYYY-MM-DD') OR MMS.DATE_TO IS NULL)";		
					$resultCekShift = oci_parse($con,$cekShift);
					oci_execute($resultCekShift);
					$rowCekShift = oci_fetch_row($resultCekShift);
					$cekShift = $rowCekShift[0];
					
					if($cekShift >= 1)
					{
						$query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
								  FROM CardInOut CIO 
								  WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, - $pengurang, getdate()),120)
								  AND CIO.KaryaCode=$dataId ";

						$result = odbc_exec($conn, $query);
						$Tgl = '';
						$Status = '';
						$JamKeluarAsli = '';
						$JamMasukAsli = '';
						$JamMasuk = '';
						$JamMasukHitung = '';
						$JamKeluarHitung = '';
						$JamKeluar = '';
						$Masuk = '';
						$Terlambat = '';
						$Keluar = '';
						$Hours = '';
						$mskbesok = '';
						$HariIni = '';
						$besok = '';
						$dateout = '';
						$jamMaxMasukBesok = '';
						$jamMaxMasukIni = '';
						$countwik  = 0; 
						$countah   = 0; 
						$early_in  = '';
						$late_in   = '';
						$early_out = '';
						$late_out  = '';
						$ShiftId   = '';
						$TglBesok = '';
						$StatusBesok = '';
						$tglsubsbesok = '';
						$HariBesok = '';
						$TerlambatBesok = ''; 
						$MasukBesok = ''; 
						$KeluarBesok = ''; 
						$HoursBesok = '';
						$JamBesok = '';
						$JamHitungBesok = '';
						
						while(odbc_fetch_row($result))
						{	
							$Tgl = odbc_result($result, 2);
							$Status = odbc_result($result, 3);
							$tglsubs = substr($Tgl,0,10);
							
							$queryHariIni  = "SELECT TO_CHAR(TO_DATE('$tglsubs', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
							$resultHariIni = oci_parse($con,$queryHariIni);
							oci_execute($resultHariIni);
							$rowHariIni = oci_fetch_row($resultHariIni);
							$HariIni = $rowHariIni[0];
							$HariIni = trim($HariIni);

							$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS, E.TWS_ID
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_CHANGE_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.HONORS=$dataId AND A. EFFECTIVE_END_DATE >= TO_DATE('$tglsubs', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE >= TO_DATE('$tglsubs', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. DATE_DETAIL = TO_DATE('$tglsubs', 'YYYY-MM-DD')
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariIni' -- Hari absen
								AND E.SHT_ID=F.ID";
							$resultMasuk = oci_parse($con,$queryMasuk);
							oci_execute($resultMasuk);
							$rowMasuk   = oci_fetch_row($resultMasuk);
							$Terlambat  = $rowMasuk[0]; 
							$Masuk 		= $rowMasuk[1]; 
							$Keluar     = $rowMasuk[2]; 
							$Hours  	= $rowMasuk[3];
							$ShiftId  	= $rowMasuk[4];
							
							$querybes = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
							FROM CardInOut CIO 
							WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, - $pengurangan, getdate()),120)
							AND CIO.KaryaCode=$dataId ";
							$resultbes = odbc_exec($conn, $querybes);
							while(odbc_fetch_row($resultbes)){						
								$TglBesok = odbc_result($resultbes, 2);
								$StatusBesok = odbc_result($resultbes, 3);
								$tglsubsbesok = substr($TglBesok,0,10);
							
							$queryHariBesok  = "SELECT TO_CHAR(TO_DATE('$tglsubs', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
							$resultHariBesok = oci_parse($con,$queryHariIni);
							oci_execute($resultHariBesok);
							$rowHariBesok = oci_fetch_row($resultHariBesok);
							$HariBesok = $rowHariBesok[0];
							$HariBesok = trim($HariBesok);
							
							$queryMasukBesok = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_CHANGE_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE >= TO_DATE('$tglsubsbesok', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE >= TO_DATE('$tglsubsbesok', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. DATE_DETAIL = TO_DATE('$tglsubsbesok', 'YYYY-MM-DD')
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariBesok' -- Hari absen
								AND E.SHT_ID=F.ID";
							$resultMasukBesok = oci_parse($con,$queryMasukBesok);
							oci_execute($resultMasukBesok);
							$rowMasukBesok = oci_fetch_row($resultMasukBesok);
							$TerlambatBesok = $rowMasukBesok[0]; 
							$MasukBesok = $rowMasukBesok[1]; 
							$KeluarBesok = $rowMasukBesok[2]; 
							$HoursBesok = $rowMasukBesok[3];

							$queryRange = "SELECT RANGE_EARLY_IN,RANGE_LATE_IN,RANGE_EARLY_OUT,RANGE_LATE_OUT FROM MJ.MJ_M_RANGE_SHIFT WHERE SHIFT_ID = $ShiftId AND AKTIF = 'Y'";
							$resultRange = oci_parse($con,$queryRange);
							oci_execute($resultRange);
							$rowRange   = oci_fetch_row($resultRange);
							$early_in   = $rowRange[0]*100; 
							$late_in 	= $rowRange[1]*100; 
							$early_out  = $rowRange[2]*100; 
							$late_out  	= $rowRange[3]*100;
		
							//echo 'Keluar : '.$Keluar.' Masuk : '.$Masuk;//exit;
							if ($Keluar > $Masuk) 
							{
								$Jam 		     = substr($Tgl, 11, 5);							
								$JamHitung       = str_replace(':', '', $Jam);
								$JamBesok 	     = substr($TglBesok, 11, 5);							
								$JamHitungBesok  = str_replace(':', '', $JamBesok);
								$countbro  = 0;
								$early_in  = $Masuk - $early_in;
								$late_in   = $Masuk + $late_in;
								$early_out = $Keluar - $early_out;
								$late_out  = $Keluar + $late_out;

								if ($late_out > 2400)
								{
									$late_out = $late_out - 2400;
									$countbro = 1;
								}

								
								if ($JamHitung <= $late_in && $JamHitung >= $early_in) 
								{
									if($countwik == 0) 
									{
										$JamMasuk = $Jam;
										$JamMasukAsli  = str_replace(':', '', $JamMasuk);
										$countwik++;
									}
								}
								if ($countbro >= 1)
								{ 
									if ($JamHitungBesok < $late_out)
									{ 
										$JamKeluar = $JamBesok;
										$JamKeluarAsli  = str_replace(':', '', $JamKeluar);
										$countah ++;

										$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
										$resultDateOut = oci_parse($con,$queryDateOut);
										oci_execute($resultDateOut);
										$rowDateOut = oci_fetch_row($resultDateOut);
										$dateout = $rowDateOut[0];
									}
									else if ($countah == 0)
									{ // JIKA TIDAK ADA DATA JAM BESOK YANG KURANG DARI LATE OUT MAKA CEK DATA HARI INI YANG LEBIH DARI EARLY OUT, DATA TERAKHIR DIDAPAT SESUAI LOOP
										if ($JamHitung > $early_out)
										{
											$JamKeluar = $Jam;
											$JamKeluarAsli  = str_replace(':', '', $JamKeluar);

											$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD'),'YYYY-MM-DD') FROM DUAL";
											$resultDateOut = oci_parse($con,$queryDateOut);
											oci_execute($resultDateOut);
											$rowDateOut = oci_fetch_row($resultDateOut);
											$dateout = $rowDateOut[0];
										}
									}
									else if($JamHitungBesok == '')
									{
										if ($JamHitung > $early_out)
										{
											$JamKeluar = $Jam;
											$JamKeluarAsli  = str_replace(':', '', $JamKeluar);

											$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD'),'YYYY-MM-DD') FROM DUAL";
											$resultDateOut = oci_parse($con,$queryDateOut);
											oci_execute($resultDateOut);
											$rowDateOut = oci_fetch_row($resultDateOut);
											$dateout = $rowDateOut[0];
										}
										else { 
											$JamKeluar = '';
											$JamKeluarAsli  = '';
										}
									}
								}
								else if ($countbro = 0)
								{ 
									if ($JamHitung > $early_out && $JamHitung < $late_out)
									{
											$JamKeluar = $Jam;
											$JamKeluarAsli  = str_replace(':', '', $JamKeluar);
									}
								}
							}
							else { 	
									$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
									$resultDateOut = oci_parse($con,$queryDateOut);
									oci_execute($resultDateOut);
									$rowDateOut = oci_fetch_row($resultDateOut);
									$dateout = $rowDateOut[0];

									$Jam 		     = substr($Tgl, 11, 5);							
									$JamHitung       = str_replace(':', '', $Jam);
									$JamBesok 	     = substr($TglBesok, 11, 5);							
									$JamHitungBesok  = str_replace(':', '', $JamBesok);
									$countbro  = 0;
									$early_in  = $Masuk - $early_in;
									$late_in   = $Masuk + $late_in;

									if ($late_in > 2400)
									{
										$late_in = $late_in - 2400;
										$countbro = 1;
									}
									$early_out = $Keluar - $early_out;
									$late_out  = $Keluar + $late_out;
									if ($countbro == 1)
									{ 
										if ($JamHitung >= $early_in)
										{
											if($countwik == 0) 
											{ 
												$JamMasuk = $Jam;
												$JamMasukAsli  = str_replace(':', '', $JamMasuk);
												$countwik++;
												$countah ++;
											}
										}
										else if ($countah == 0) 
										{
											if ($JamHitungBesok <= $late_in)
											{
												if($countwik == 0) 
												{
													$JamMasuk = $JamBesok;
													$JamMasukAsli  = str_replace(':', '', $JamMasuk);
													$countwik++;
												}
											}
											else { 
												$JamMasuk = '';
												$JamMasukAsli = '';
											}
										}
									}
									else if ($countbro == 0)
									{ 
										if ($JamHitung >= $early_in && $JamHitung <= $late_in)
										{
											if($countwik == 0) 
											{
												$JamMasuk = $Jam;
												$JamMasukAsli  = str_replace(':', '', $JamMasuk);
												$countwik++;
											}
										}
									}
									if ($JamHitungBesok > $early_out && $JamHitungBesok < $late_out)
									{
										$JamKeluar = $JamBesok;
										$JamKeluarAsli  = str_replace(':', '', $JamKeluar);
									}
									else { 
										$JamKeluar = '';
										$JamKeluarAsli = '';
									}
								}
							}
						}
							echo trim($dataId)."-".$TglHeader."-".$JamMasuk."-".$JamMasukHitung."-".$JamKeluar."-".$JamKeluarHitung." ";
							echo "dateout : ".$dateout;
					InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasuk, $JamMasukHitung, $JamKeluar, $JamKeluarHitung, 24, $pengurang, $dateout);
				}
			}
			else { 
					$cekShift = "SELECT COUNT(-1)
								 FROM PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF,MJ.MJ_M_SHIFT MMS
								 WHERE PPF.HONORS = '$dataId'
								 AND PPF.PERSON_ID = PAF.PERSON_ID 
								 AND PAF.ASSIGNMENT_ID = MMS.ASSIGNMENT_ID 
								 AND MMS.DATE_FROM <= TO_DATE('$vSdate') AND (MMS.DATE_TO >= TO_DATE('$vSdate', 'YYYY-MM-DD') OR MMS.DATE_TO IS NULL)";
			
					$resultCekShift = oci_parse($con,$cekShift);
					oci_execute($resultCekShift);
					$rowCekShift = oci_fetch_row($resultCekShift);
					$cekShift = $rowCekShift[0];
					//echo $cekShift;exit;
					if($cekShift >= 1)
					{
						$query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
								  FROM CardInOut CIO 
								  WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, - $pengurang, getdate()),120)
								  AND CIO.KaryaCode=$dataId ";

						$result = odbc_exec($conn, $query);
						$Tgl = '';
						$Status = '';
						$JamKeluarAsli = '';
						$JamMasukAsli = '';
						$JamMasuk = '';
						$JamMasukHitung = '';
						$JamKeluarHitung = '';
						$JamKeluar = '';
						$Masuk = '';
						$Terlambat = '';
						$Keluar = '';
						$Hours = '';
						$mskbesok = '';
						$HariIni = '';
						$besok = '';
						$dateout = '';
						$jamMaxMasukBesok = '';
						$jamMaxMasukIni = '';
						$countwik  = 0; 
						$countah   = 0; 
						$early_in  = '';
						$late_in   = '';
						$early_out = '';
						$late_out  = '';
						$ShiftId   = '';
						$TglBesok = '';
						$StatusBesok = '';
						$tglsubsbesok = '';
						$HariBesok = '';
						$TerlambatBesok = ''; 
						$MasukBesok = ''; 
						$KeluarBesok = ''; 
						$HoursBesok = '';
						$JamBesok = '';
						$JamHitungBesok = '';
						
						while(odbc_fetch_row($result))
						{
							$Tgl = odbc_result($result, 2);
							$Status = odbc_result($result, 3);
							$tglsubs = substr($Tgl,0,10);
							
							$queryHariIni  = "SELECT TO_CHAR(TO_DATE('$tglsubs', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
							$resultHariIni = oci_parse($con,$queryHariIni);
							oci_execute($resultHariIni);
							$rowHariIni = oci_fetch_row($resultHariIni);
							$HariIni = $rowHariIni[0];
							$HariIni = trim($HariIni);

							$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS, E.TWS_ID
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.HONORS=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$tglsubs', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$tglsubs', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C.DATE_FROM <= TO_DATE('$tglsubs', 'YYYY-MM-DD') 
								AND (C.DATE_TO >= TO_DATE('$tglsubs', 'YYYY-MM-DD') OR C.DATE_TO IS NULL)
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariIni'-- Hari absen
								AND E.SHT_ID=F.ID";
							$resultMasuk = oci_parse($con,$queryMasuk);
							oci_execute($resultMasuk);
							$rowMasuk  = oci_fetch_row($resultMasuk);
							$Terlambat = $rowMasuk[0]; 
							$Masuk   = $rowMasuk[1]; 
							$Keluar  = $rowMasuk[2]; 
							$Hours   = $rowMasuk[3];
							$ShiftId = $rowMasuk[4];

							$querybes = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
							FROM CardInOut CIO 
							WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, - $pengurangan, getdate()),120)
							AND CIO.KaryaCode=$dataId ";
							$resultbes = odbc_exec($conn, $querybes);
							while(odbc_fetch_row($resultbes)){						
								$TglBesok = odbc_result($resultbes, 2);
								$StatusBesok = odbc_result($resultbes, 3);
								$tglsubsbesok = substr($TglBesok,0,10);
							
							$queryHariBesok  = "SELECT TO_CHAR(TO_DATE('$tglsubs', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
							$resultHariBesok = oci_parse($con,$queryHariIni);
							oci_execute($resultHariBesok);
							$rowHariBesok = oci_fetch_row($resultHariBesok);
							$HariBesok = $rowHariBesok[0];
							$HariBesok = trim($HariBesok);
							
							$queryMasukBesok = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
								FROM APPS.PER_PEOPLE_F A
								, APPS.PER_ALL_ASSIGNMENTS_F B
								, MJ.MJ_M_SHIFT C
								, APPS.HXT_WORK_SHIFTS_FMV E
								, APPS.HXT_SHIFTS F
								WHERE A.HONORS=$dataId AND A. EFFECTIVE_END_DATE > TO_DATE('$tglsubsbesok', 'YYYY-MM-DD')
								AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > TO_DATE('$tglsubsbesok', 'YYYY-MM-DD')
								AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C.DATE_FROM <= TO_DATE('$tglsubsbesok', 'YYYY-MM-DD') 
								AND (C.DATE_TO >= TO_DATE('$tglsubsbesok', 'YYYY-MM-DD') OR C.DATE_TO IS NULL)
								AND C.SHIFT_ID = E.TWS_ID
								AND UPPER(E.MEANING)='$HariBesok'-- Hari absen
								AND E.SHT_ID=F.ID";
							$resultMasukBesok = oci_parse($con,$queryMasukBesok);
							oci_execute($resultMasukBesok);
							$rowMasukBesok = oci_fetch_row($resultMasukBesok);
							$TerlambatBesok = $rowMasukBesok[0]; 
							$MasukBesok = $rowMasukBesok[1]; 
							$KeluarBesok = $rowMasukBesok[2]; 
							$HoursBesok = $rowMasukBesok[3];

							$queryRange = "SELECT RANGE_EARLY_IN,RANGE_LATE_IN,RANGE_EARLY_OUT,RANGE_LATE_OUT FROM MJ.MJ_M_RANGE_SHIFT WHERE SHIFT_ID = $ShiftId AND AKTIF = 'Y'";
							$resultRange = oci_parse($con,$queryRange);
							oci_execute($resultRange);
							$rowRange   = oci_fetch_row($resultRange);
							$early_in   = $rowRange[0]*100; 
							$late_in 	= $rowRange[1]*100; 
							$early_out  = $rowRange[2]*100; 
							$late_out  	= $rowRange[3]*100;
							//echo $Keluar.$Masuk;exit;

							if ($Keluar > $Masuk) 
							{ 
								$Jam 		     = substr($Tgl, 11, 5);							
								$JamHitung       = str_replace(':', '', $Jam);
								$JamBesok 	     = substr($TglBesok, 11, 5);							
								$JamHitungBesok  = str_replace(':', '', $JamBesok);
								$countbro  = 0;
								$early_in  = $Masuk - $early_in;
								$late_in   = $Masuk + $late_in;
								$early_out = $Keluar - $early_out;
								$late_out  = $Keluar + $late_out;

								if ($late_out > 2400) 
								{
									$late_out = $late_out - 2400;
									$countbro = 1;
								}
								if ($JamHitung <= $late_in && $JamHitung >= $early_in) 
								{
									if($countwik == 0) 
									{
										$JamMasuk = $Jam;
										$JamMasukAsli  = str_replace(':', '', $JamMasuk);
										$countwik++;
									}
								}
								if ($countbro >= 1)
								{
									if ($JamHitungBesok < $late_out)
									{ 
										$JamKeluar = $JamBesok;
										$JamKeluarAsli  = str_replace(':', '', $JamKeluar);
										$countah ++;

										$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
										$resultDateOut = oci_parse($con,$queryDateOut);
										oci_execute($resultDateOut);
										$rowDateOut = oci_fetch_row($resultDateOut);
										$dateout = $rowDateOut[0];
									}
									else if ($countah == 0)
									{ 
										if ($JamHitung > $early_out)
										{
											$JamKeluar = $Jam;
											$JamKeluarAsli  = str_replace(':', '', $JamKeluar);

											$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD'),'YYYY-MM-DD') FROM DUAL";
											$resultDateOut = oci_parse($con,$queryDateOut);
											oci_execute($resultDateOut);
											$rowDateOut = oci_fetch_row($resultDateOut);
											$dateout = $rowDateOut[0];
										}
									} 
									else if($JamHitungBesok == '')
									{
										if ($JamHitung > $early_out)
										{
											$JamKeluar = $Jam;
											$JamKeluarAsli  = str_replace(':', '', $JamKeluar);

											$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD'),'YYYY-MM-DD') FROM DUAL";
											$resultDateOut = oci_parse($con,$queryDateOut);
											oci_execute($resultDateOut);
											$rowDateOut = oci_fetch_row($resultDateOut);
											$dateout = $rowDateOut[0];
										}
										else { 
											$JamKeluar = '';
											$JamKeluarAsli  = '';
										}
									}
								}
								else if ($countbro == 0)
								{ 
									if ($JamHitung > $early_out && $JamHitung < $late_out)
									{
											$JamKeluar = $Jam;
											$JamKeluarAsli  = str_replace(':', '', $JamKeluar);
									}
								}
							}
							else { 		
									$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
									$resultDateOut = oci_parse($con,$queryDateOut);
									oci_execute($resultDateOut);
									$rowDateOut = oci_fetch_row($resultDateOut);
									$dateout = $rowDateOut[0];

									$Jam 		     = substr($Tgl, 11, 5);							
									$JamHitung       = str_replace(':', '', $Jam);
									$JamBesok 	     = substr($TglBesok, 11, 5);							
									$JamHitungBesok  = str_replace(':', '', $JamBesok);
									$countbro  = 0;
									$early_in  = $Masuk - $early_in;
									$late_in   = $Masuk + $late_in;

									if ($late_in > 2400)
									{
										$late_in = $late_in - 2400;
										$countbro = 1;
									}
									$early_out = $Keluar - $early_out;
									$late_out  = $Keluar + $late_out;

						
									if ($countbro == 1)
									{ 
										if ($JamHitung >= $early_in)
										{
											if($countwik == 0) 
											{ 
												$JamMasuk = $Jam;
												$JamMasukAsli  = str_replace(':', '', $JamMasuk);
												$countwik++;
												$countah ++; 
											}
										}
										else if ($countah == 0) 
										{
											if ($JamHitungBesok <= $late_in)
											{
												if($countwik == 0) 
												{
													$JamMasuk = $JamBesok;
													$JamMasukAsli  = str_replace(':', '', $JamMasuk);
													$countwik++;
												}
											}
											else {
												$JamMasuk = '';
												$JamMasukAsli = '';
											}
										}
									}
									else if ($countbro == 0)
									{ 
										if ($JamHitung >= $early_in && $JamHitung <= $late_in)
										{
											if($countwik == 0) 
											{
												$JamMasuk = $Jam;
												$JamMasukAsli  = str_replace(':', '', $JamMasuk);
												$countwik++;
											}
										}
									}
									
									if ($JamHitungBesok > $early_out && $JamHitungBesok < $late_out)
									{
										$JamKeluar = $JamBesok;
										$JamKeluarAsli  = str_replace(':', '', $JamKeluar);
									}
									else { 
										$JamKeluar = '';
										$JamKeluarAsli = '';
									}
								}
							}
						}
					}
						echo trim($dataId)."-".$TglHeader."-".$JamMasuk."-".$JamMasukHitung."-".$JamKeluar."-".$JamKeluarHitung." ";
						echo "dateout : ".$dateout;
					InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasuk, $JamMasukHitung, $JamKeluar, $JamKeluarHitung, 24, $pengurang, $dateout);
			}
		}
?>