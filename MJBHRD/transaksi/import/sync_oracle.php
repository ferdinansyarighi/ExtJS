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

$periode1 = '2016-06-21';
$periode2 = '2016-07-20';

	$queryTimecard = "SELECT COUNT(-1)
	FROM MJ.MJ_T_TIMECARD MTT
	INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID 
	AND PPF.EFFECTIVE_END_DATE > SYSDATE 
	AND UPPER(PPF.CURRENT_EMPLOYEE_FLAG) = 'Y'
	WHERE MTT.APP_ID=" . APPCODE . " AND MTT.STATUS_SYNC='Belum' AND ELEMENT_NAME NOT IN ('ALPHA', 'TERLAMBAT', 'LEMBUR')
	AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1' AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2' -- AND PERSON_ID=2129";
	//echo $queryTimecard;
	$resultTimecard = oci_parse($con,$queryTimecard);
	oci_execute($resultTimecard);
	$rowTimecard = oci_fetch_row($resultTimecard);
	$vTimecard = $rowTimecard[0];
	//echo $vTimecard;
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
		WHERE MTT.APP_ID=" . APPCODE . " AND STATUS_SYNC='Belum' AND ELEMENT_NAME NOT IN ('ALPHA', 'TERLAMBAT', 'LEMBUR')
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1' AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2' -- AND PERSON_ID=2129";
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
			
		}
	}
	
	

?>