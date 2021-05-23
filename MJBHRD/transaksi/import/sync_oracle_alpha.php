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
// echo $periode1 . " dan " . $periode2;

	$queryTimecard = "SELECT COUNT(-1)
	FROM MJ.MJ_T_TIMECARD MTT
	INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID 
	AND PPF.EFFECTIVE_END_DATE > SYSDATE 
	AND UPPER(PPF.CURRENT_EMPLOYEE_FLAG) = 'Y'
	WHERE MTT.APP_ID=" . APPCODE . " AND STATUS_SYNC='Belum' AND ELEMENT_NAME IN ('ALPHA', 'TERLAMBAT')
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2' --AND PERSON_ID IN (2028)";
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
		WHERE MTT.APP_ID=" . APPCODE . " AND STATUS_SYNC='Belum' AND ELEMENT_NAME IN ('ALPHA', 'TERLAMBAT')
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2' --AND PERSON_ID IN (2028)";
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
			
			// $queryCountKategori = "SELECT COUNT(-1)
			// FROM MJ.MJ_T_SIK 
			// WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE) 
			// AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$vTanggalTrans'
			// AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$vTanggalTrans'
			// AND STATUS_DOK = 'Approved'";
			$queryCountKategori = "SELECT COUNT(-1)
			FROM MJ.MJ_T_SIK 
			WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE) 
			AND '$vTanggalTrans' BETWEEN TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD')
			AND STATUS_DOK = 'Approved'";
			//echo $queryCountKategori;
			$resultCountKategori = oci_parse($con,$queryCountKategori);
			oci_execute($resultCountKategori);
			$rowCountKategori = oci_fetch_row($resultCountKategori);
			$CountKategori = $rowCountKategori[0]; 
			
			if($CountKategori >= 1){
				// $queryKategori = "SELECT ID, KATEGORI, IJIN_KHUSUS
				// FROM MJ.MJ_T_SIK 
				// WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE) 
				// AND TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') >= '$vTanggalTrans'
				// AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') <= '$vTanggalTrans'
				// AND STATUS_DOK = 'Approved'";
				$queryKategori = "SELECT ID, KATEGORI, IJIN_KHUSUS
				FROM MJ.MJ_T_SIK 
				WHERE PEMOHON = (SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE PERSON_ID=$vPerson_id AND EFFECTIVE_END_DATE > SYSDATE) 
				AND '$vTanggalTrans' BETWEEN TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AND TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD')
				AND STATUS_DOK = 'Approved'";
				$resultKategori = oci_parse($con,$queryKategori);
				oci_execute($resultKategori);
				$rowKategori = oci_fetch_row($resultKategori);
				$IdSIK = $rowKategori[0]; 
				$Kategori = $rowKategori[1]; 
				$IjinKhusus = $rowKategori[2]; 
				
				if($Kategori == 'Cuti'){
					$CutiTahunan = -1;
					$queryCutiTahunan = "SELECT (CUTI_TAHUNAN) FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$vPerson_id";
					$resultCutiTahunan = oci_parse($con,$queryCutiTahunan);
					oci_execute($resultCutiTahunan);
					$rowCutiTahunan = oci_fetch_row($resultCutiTahunan);
					$CutiTahunan = $rowCutiTahunan[0]; 
					
					if($CutiTahunan == 0){
						$vStatus=StatusTimecard($con, 'IJIN'); //Ijin
					} else {
						if ($CutiTahunan >= 1){
							$vStatus=StatusTimecard($con, 'CUTI'); //Cuti
							// $CutiTahunan = $CutiTahunan - 1;
							// $query = "UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN = $CutiTahunan, LAST_UPDATED_BY='syncalpha', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPerson_id";
							// $resultUpdate = oci_parse($con,$query);
							// oci_execute($resultUpdate);
						}
					}
				} elseif ($Kategori == 'Sakit'){
					$queryCutiSakit = "SELECT (CUTI_SAKIT) FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$vPerson_id";
					$resultCutiSakit = oci_parse($con,$queryCutiSakit);
					oci_execute($resultCutiSakit);
					$rowCutiSakit = oci_fetch_row($resultCutiSakit);
					$CutiSakit = $rowCutiSakit[0]; 
					
					if($CutiSakit == 0){
						$vStatus=StatusTimecard($con, 'IJIN'); //Ijin
					} else {
						$vStatus=StatusTimecard($con, 'SAKIT'); //Sakit
						// $CutiSakit = $CutiSakit - 1;
						// $query = "UPDATE MJ.MJ_M_CUTI SET CUTI_SAKIT = $CutiSakit WHERE PERSON_ID=$vPerson_id";
						// $resultUpdate = oci_parse($con,$query);
						// oci_execute($resultUpdate);
					}
				} elseif ($Kategori == 'Terlambat'){
					//$vStatus=StatusTimecard($con, 'TERLAMBAT'); //Terlambat
					$vStatus='SUKSES';
				} elseif ($Kategori == 'Ijin'){
					if($IjinKhusus != ''){
						$vStatus = StatusTimecard($con, 'IJIN KHUSUS'); //Ijin Khusus
						if($IjinKhusus == 'LUPA ABSEN PULANG' || $IjinKhusus == 'LUPA ABSEN DATANG' || $IjinKhusus == 'TIDAK ABSEN KARENA URUSAN KANTOR' || $IjinKhusus == 'SETENGAH HARI'){
							$vCekMasuk = 0;
							$queryCekMasuk = "SELECT COUNT(-1)
							FROM MJ.MJ_T_TIMECARD MTT
							INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
							WHERE MTT.APP_ID=" . APPCODE . " AND ELEMENT_NAME = 'MASUK' AND PERSON_ID=$vPerson_id AND TO_CHAR(TANGGAL, 'YYYY-MM-DD') = '$vTanggalTrans'";
							//echo $queryCekMasuk;
							$resultCekMasuk = oci_parse($con,$queryCekMasuk);
							oci_execute($resultCekMasuk);
							$rowCekMasuk = oci_fetch_row($resultCekMasuk);
							$vCekMasuk = $rowCekMasuk[0];
							if($vCekMasuk == 0){
								$vStatusDet = StatusTimecard($con, 'MASUK'); //MASUK
							
								$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '$vTglMasuk', '$vTglKeluar', '$vStatusDet')";
								//echo $queryUpload;
								$resultUpload = oci_parse($con,$queryUpload);
								oci_execute($resultUpload);
							}
							
						}
					} else {
						$vStatus = StatusTimecard($con, 'IJIN'); //Ijin
					}
				}
			}
			
			if($vStatus != 'SUKSES'){
				$queryUpload = "CALL MJ_HRIS_PKG.P_MJ_TIMECARD($vPerson_id, '$vTglMasuk', '$vTglKeluar', '$vStatus')";
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
			} else {
				$queryUpdate = "UPDATE MJ.MJ_T_TIMECARD SET STATUS_SYNC='Sudah', LAST_UPDATED_BY=20, LAST_UPDATED_DATE=SYSDATE WHERE ID=$vID";
				//echo $query;
				$resultUpdate = oci_parse($con,$queryUpdate);
				$oeu = oci_execute($resultUpdate);
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