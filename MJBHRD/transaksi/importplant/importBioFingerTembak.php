<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/importplant/fungsiTimecardimport.php';

 
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
$JamMasuk='';
$JamKeluar='';
$dataId =0;
$dataTgl='';
$x = 11;
$pengurang = 6;
$pengurangan = 6-1;
$conn = odbc_connect('bioFinger', 'absen', 'absen123');
if (!$conn) {
	echo "Connection MSSQL Black failed.";
	exit;
}
$queryDate = "SELECT SYSDATE - $pengurang FROM DUAL";
$resultDate = oci_parse($con,$queryDate);
oci_execute($resultDate);
$rowDate = oci_fetch_row($resultDate);
$vSdate = $rowDate[0];

		$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -$pengurang, getdate()),120) FROM MastKarya MK 
		WHERE MK.KaryaCode IN (600876,600643,600505,600519,600731,600759,600750,600779,600865,600888,600978,600981,600996,600993,60023,600709,600831,600373,600985,601017,600100,601004,601024,601032, 601040
		)";
		
		$resultHeader = odbc_exec($conn, $queryHeader);
		while(odbc_fetch_row($resultHeader)){
			$dataId = odbc_result($resultHeader, 1);
			$dataId = trim($dataId," ");
			$TglHeader = substr(odbc_result($resultHeader, 2), 0, 10);
			$JamMasukAsli = '';
			$JamKeluarAsli = '';
			$countIn = 0;
			//echo $dataId;exit;
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
				$query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
				FROM CardInOut CIO 
				WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, - $pengurang, getdate()),120)
				AND CIO.KaryaCode=$dataId";
				//echo $query;exit;
				$result = odbc_exec($conn, $query);

				$Tgl = '';
				$Status = '';
				$JamKeluarAsli = '';
				$JamMasukAsli = '';
				$JamMasuk = '';
				$JamKeluar = '';
				$Masuk = '';
				$Terlambat = '';
				$Keluar = '';
				$Hours = '';
				$mskbesok = '';
				$HariIni = '';
				$besok = '';
				$dateout = '';

				while(odbc_fetch_row($result)){
					$Tgl = odbc_result($result, 2);
					$Status = odbc_result($result, 3);
					$tglsubs = substr($Tgl,0,10);
					
					$queryHariIni = "SELECT TO_CHAR(TO_DATE('$tglsubs', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
					$resultHariIni = oci_parse($con,$queryHariIni);
					oci_execute($resultHariIni);
					$rowHariIni = oci_fetch_row($resultHariIni);
					$HariIni = $rowHariIni[0];
					$HariIni = trim($HariIni);
					//echo $HariIni;exit;
					$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
						FROM APPS.PER_PEOPLE_F A
						, APPS.PER_ALL_ASSIGNMENTS_F B
						, MJ.MJ_M_CHANGE_SHIFT C
						, APPS.HXT_WORK_SHIFTS_FMV E
						, APPS.HXT_SHIFTS F
						WHERE A.PERSON_ID=$dataId AND A. EFFECTIVE_END_DATE >= TO_DATE('$tglsubs', 'YYYY-MM-DD')
						AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE >= TO_DATE('$tglsubs', 'YYYY-MM-DD')
						AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. DATE_DETAIL = TO_DATE('$tglsubs', 'YYYY-MM-DD')
						AND C.SHIFT_ID = E.TWS_ID
						AND UPPER(E.MEANING)='$HariIni' -- Hari absen
						AND E.SHT_ID=F.ID";
					$resultMasuk = oci_parse($con,$queryMasuk);
					oci_execute($resultMasuk);
					$rowMasuk = oci_fetch_row($resultMasuk);
					$Terlambat = $rowMasuk[0]; 
					$Masuk = $rowMasuk[1]; 
					$Keluar = $rowMasuk[2]; 
					$Hours = $rowMasuk[3];
				   $queryceker = "SELECT COUNT(-1)
				   FROM CardInOut CIO 
				   WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) =  CONVERT(VARCHAR(10),DATEADD(day, - $pengurang, getdate()),120)
				   AND CIO.KaryaCode=$dataId
				   AND CIO.StateCode=1";

					$resultceker = odbc_exec($conn, $queryceker);
					while(odbc_fetch_row($resultceker)){
						$cekeran = odbc_result($resultceker, 1);
					}

					$querybesok = "SELECT CIO.TranDate
								   FROM CardInOut CIO 
								   WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) =  CONVERT(VARCHAR(10),DATEADD(day, - $pengurangan, getdate()),120)
								   AND CIO.KaryaCode=$dataId
								   AND CIO.StateCode=1";

					$resultbesok = odbc_exec($conn, $querybesok);
					while(odbc_fetch_row($resultbesok)){
						$besok = odbc_result($resultbesok, 1);
					}

					if ($besok != '')
					{ 
						$JamKeluarAsliBesok = substr($besok, 11, 5);
						$JamKeluarBesok = str_replace(':', '', $JamKeluarAsliBesok);
						$dateout = substr($besok,0,10);
					}
					else {
						$JamKeluarAsliBesok = '';
						$JamKeluarBesok = '';
						$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
						$resultDateOut = oci_parse($con,$queryDateOut);
						oci_execute($resultDateOut);
						$rowDateOut = oci_fetch_row($resultDateOut);
						$dateout = $rowDateOut[0];
					}

					$querymskbesok = "SELECT CIO.TranDate
							   	      FROM CardInOut CIO 
								      WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) =  CONVERT(VARCHAR(10),DATEADD(day, - $pengurangan, getdate()),120)
								      AND CIO.KaryaCode=$dataId
								      AND CIO.StateCode=0";
					$resultmskbesok = odbc_exec($conn, $querymskbesok);
					while(odbc_fetch_row($resultmskbesok)){
						$mskbesok = odbc_result($resultmskbesok, 1);
					}					

					$JamKeluarAsliBesok = substr($besok, 11, 5);
					$JamKeluarBesok = str_replace(':', '', $JamKeluarAsliBesok);
					if ($mskbesok != '')
					{ 
						$JamMasukAsliBesok = substr($mskbesok, 11, 5);
						$JamMasukBesok = str_replace(':', '', $JamMasukAsliBesok);
						$dateout = substr($besok,0,10);
					}
					else {
						$JamMasukAsliBesok = '';
						$JamMasukBesok = '';
						$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
						$resultDateOut = oci_parse($con,$queryDateOut);
						oci_execute($resultDateOut);
						$rowDateOut = oci_fetch_row($resultDateOut);
						$dateout = $rowDateOut[0];
					}

					if($Status == 0 && $countIn == 0){
						$JamMasukAsli = substr($Tgl, 11, 5);
						$JamMasuk = str_replace(':', '', $JamMasukAsli);
						$countIn++;
					}
					if($Status == 1){
						$JamKeluarAsli = substr($Tgl, 11, 5);
						$JamKeluar = str_replace(':', '', $JamKeluarAsli);
					}
				}	
				if ($JamKeluarAsli == '' && $Masuk > $Keluar || $JamKeluar < $JamMasuk || $JamMasukAsli == '' && $Masuk > $Keluar || $Masuk > $Keluar || $JamKeluarAsli == '')
				{
					if($JamKeluarBesok < $JamMasukBesok || $JamMasukBesok == '')
					{ 
						$JamKeluarAsli = substr($besok, 11, 5);
						$JamKeluar = str_replace(':', '', $JamKeluarAsli);
					}
					else if ($JamMasukBesok < $JamKeluarBesok)
					{ 
						$JamKeluarAsli = '';
						$JamKeluar = '';
					} 
					else if ($JamKeluarBesok == '')
					{ 
						$JamKeluarAsli = '';
						$JamKeluar = '';
					}
					else if ($JamKeluarBesok != '')
					{ 
						$JamKeluarAsli = substr($besok, 11, 5);
						$JamKeluar = str_replace(':', '', $JamKeluarAsli);
					}
					else if ($Masuk > $Keluar)
					{ 
						$JamKeluarAsli = substr($besok, 11, 5);
						$JamKeluar = str_replace(':', '', $JamKeluarAsli);
					}
					$dateout = $dateout;
				}	
				else {
					$dateout = $TglHeader;
				}
				echo trim($dataId)."-".$TglHeader."-".$JamMasukAsli."-".$JamMasuk."-".$JamKeluarAsli."-".$JamKeluar." ";
				echo "dateout : ".$dateout;
			InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24, $pengurang, $dateout);
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
						$JamKeluar = '';
						$Masuk = '';
						$Terlambat = '';
						$Keluar = '';
						$Hours = '';
						$mskbesok = '';
						$HariIni = '';
						$besok = '';
						$dateout = '';
						
						while(odbc_fetch_row($result)){
							
						$Tgl = odbc_result($result, 2);
						$Status = odbc_result($result, 3);
						$tglsubs = substr($Tgl,0,10);
						
						$queryHariIni  = "SELECT TO_CHAR(TO_DATE('$tglsubs', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
						$resultHariIni = oci_parse($con,$queryHariIni);
						oci_execute($resultHariIni);
						$rowHariIni = oci_fetch_row($resultHariIni);
						$HariIni = $rowHariIni[0];
						$HariIni = trim($HariIni);

						$queryMasuk = "SELECT F.STANDARD_START + 6, F.STANDARD_START, F.STANDARD_STOP, F.HOURS
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
						$rowMasuk = oci_fetch_row($resultMasuk);
						$Terlambat = $rowMasuk[0]; 
						$Masuk = $rowMasuk[1]; 
						$Keluar = $rowMasuk[2]; 
						$Hours = $rowMasuk[3];
						//echo $Keluar;
						$querybesok = "SELECT CIO.TranDate
									   FROM CardInOut CIO 
									   WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) =  CONVERT(VARCHAR(10),DATEADD(day, - $pengurangan, getdate()),120)
									   AND CIO.KaryaCode=$dataId
									   AND CIO.StateCode=1";
						$resultbesok = odbc_exec($conn, $querybesok);
						while(odbc_fetch_row($resultbesok)){
							$besok = odbc_result($resultbesok, 1);
						}
						if ($besok != '')
						{ 
							$JamKeluarAsliBesok = substr($besok, 11, 5);
							$JamKeluarBesok = str_replace(':', '', $JamKeluarAsliBesok);
							$dateout = substr($besok,0,10);
						}
						else {
							$JamKeluarAsliBesok = '';
							$JamKeluarBesok = '';
							$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
							$resultDateOut = oci_parse($con,$queryDateOut);
							oci_execute($resultDateOut);
							$rowDateOut = oci_fetch_row($resultDateOut);
							$dateout = $rowDateOut[0]; 
						}

						$querymskbesok = "SELECT CIO.TranDate
								   	      FROM CardInOut CIO 
									      WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) =  CONVERT(VARCHAR(10),DATEADD(day, - $pengurangan, getdate()),120)
									      AND CIO.KaryaCode=$dataId
									      AND CIO.StateCode=0";
						$resultmskbesok = odbc_exec($conn, $querymskbesok);
						while(odbc_fetch_row($resultmskbesok)){
							$mskbesok = odbc_result($resultmskbesok, 1);
						}					

						if ($mskbesok != '')
						{ 
							$JamMasukAsliBesok = substr($mskbesok, 11, 5);
							$JamMasukBesok = str_replace(':', '', $JamMasukAsliBesok);
							$dateout = substr($besok,0,10);
						}
						else {
							$JamMasukAsliBesok = '';
							$JamMasukBesok = '';
							$queryDateOut = "SELECT TO_CHAR(TO_DATE('$TglHeader', 'YYYY-MM-DD')+1,'YYYY-MM-DD') FROM DUAL";
							$resultDateOut = oci_parse($con,$queryDateOut);
							oci_execute($resultDateOut);
							$rowDateOut = oci_fetch_row($resultDateOut);
							$dateout = $rowDateOut[0]; 
						}
							if($Status == 0 && $countIn == 0){
								$JamMasukAsli = substr($Tgl, 11, 5);
								$JamMasuk = str_replace(':', '', $JamMasukAsli);
								$countIn++;
							}
							if($Status == 1){
								$JamKeluarAsli = substr($Tgl, 11, 5);
								$JamKeluar = str_replace(':', '', $JamKeluarAsli);
							}
							//echo $JamKeluarAsli.'-'.$JamKeluar.'-'.$JamMasuk.'-'.$JamMasukAsli.'-'.$JamKeluarBesok.'-'.$JamMasukBesok.'-'.$Masuk.'-'.$Keluar;
							//echo $JamKeluarBesok. '<' . $JamMasukBesok;
							//echo $TglHeader;exit;
							if ($JamKeluarAsli == '' && $Masuk > $Keluar || $JamKeluar < $JamMasuk || $JamMasukAsli == '' && $Masuk > $Keluar || $Masuk > $Keluar || $JamKeluarAsli == '')
							{
								if($JamKeluarBesok < $JamMasukBesok || $JamMasukBesok == '')
								{ 
									$JamKeluarAsli = substr($besok, 11, 5);
									$JamKeluar = str_replace(':', '', $JamKeluarAsli);
								}
								else if ($JamMasukBesok < $JamKeluarBesok)
								{ 
									$JamKeluarAsli = '';
									$JamKeluar = '';
								} 
								else if ($JamKeluarBesok == '')
								{ 
									$JamKeluarAsli = '';
									$JamKeluar = '';
								}
								else if ($JamKeluarBesok != '')
								{ 
									$JamKeluarAsli = substr($besok, 11, 5);
									$JamKeluar = str_replace(':', '', $JamKeluarAsli);
								}
								else if ($Masuk > $Keluar)
								{ 
									$JamKeluarAsli = substr($besok, 11, 5);
									$JamKeluar = str_replace(':', '', $JamKeluarAsli);
								}
								$dateout = $dateout;
							}
							else {
								$dateout = $TglHeader;
							}
						}
						//echo $Masuk.'>'.$Keluar;exit;
						//echo $JamKeluarAsli.'-'.$JamKeluar.'-'.$JamMasuk.'-'.$JamMasukAsli.'-'.$JamKeluarBesok.'-'.$JamMasukBesok.'-'.$Masuk.'-'.$Keluar;exit;
						//echo $JamKeluarAsli;exit;	
						//echo $JamKeluarAsli.'-'.$JamKeluar.'-'.$JamMasuk.'-'.$JamMasukAsli;exit;
						echo trim($dataId)."-".$TglHeader."-".$JamMasukAsli."-".$JamMasuk."-".$JamKeluarAsli."-".$JamKeluar." ";
						//echo $TglHeader + 1;exit;
						echo "dateout : ".$dateout;
					InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24, $pengurang, $dateout);
					}
			}
			// if($JamMasukAsli == ''){
				// $JamMasukAsli = '00:00:00';
			// }
			// if($JamKeluarAsli == ''){
				// $JamKeluarAsli = '00:00:00';
			// }
			// echo " FingerID : " . $dataId . " Tgl : " . $TglHeader . " Jam In : " . $JamMasukAsli . " Jam Out : " . $JamKeluarAsli . " JamMasuk : " . $JamMasuk . " JamKeluar : " . $JamKeluar;
			// echo "<br>";
		}
		//$x = $x - 1;
	//}
	// $queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
	// FROM MastKarya MK --WHERE MK.KaryaCode=10038 ";
	// $resultHeader = odbc_exec($conn, $queryHeader);
	// while(odbc_fetch_row($resultHeader)){
		// $dataId = odbc_result($resultHeader, 1);
		// $TglHeader = substr(odbc_result($resultHeader, 2), 0, 10);
		// $JamMasukAsli = '';
		// $JamKeluarAsli = '';
		// $countIn = 0;
		// $query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
		// FROM CardInOut CIO 
		// WHERE CONVERT(VARCHAR(10), CIO.TranDate,120)=CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
		// AND CIO.KaryaCode=$dataId ";
		// $result = odbc_exec($conn, $query);
		// while(odbc_fetch_row($result)){
			// $Tgl = odbc_result($result, 2);
			// $Status = odbc_result($result, 3);

			// if($Status == 0 && $countIn == 0){
				// $JamMasukAsli = substr($Tgl, 11, 5);
				// $JamMasuk = str_replace(':', '', $JamMasukAsli);
				// $countIn++;
			// }
			// if($Status == 1){
				// $JamKeluarAsli = substr($Tgl, 11, 5);
				// $JamKeluar = str_replace(':', '', $JamKeluarAsli);
			// }
		// }
		// // if($JamMasukAsli == ''){
			// // $JamMasukAsli = '00:00:00';
		// // }
		// // if($JamKeluarAsli == ''){
			// // $JamKeluarAsli = '00:00:00';
		// // }
		// // echo " FingerID : " . $FingerID . " Tgl : " . $TglHeader . " Jam In : " . $JamMasukAsli . " Jam Out : " . $JamKeluarAsli . " Status : " . $Status;
		// // echo "<br>";
		// InsertTimeCard($con, trim($dataId), $TglHeader, $JamMasukAsli, $JamMasuk, $JamKeluarAsli, $JamKeluar, 24);
	// }
?>