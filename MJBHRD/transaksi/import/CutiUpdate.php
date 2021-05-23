<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database
 include 'D:/dataSource/MJBHRD/transaksi/import/fungsiTimecard.php';

 // $vPeriode1='2016-10-21';
 // $vPeriode2='2016-12-20';
 $vHariCuti = 0;
 
 $queryPeriode = "SELECT 
	TO_CHAR(ADD_MONTHS(SYSDATE, -2), 'YYYY-MM') || '-21' PERIODE1,
	TO_CHAR(ADD_MONTHS(SYSDATE, -1), 'YYYY-MM') || '-20' PERIODE2
	FROM DUAL";
 $resultPeriode = oci_parse($con, $queryPeriode);
 oci_execute($resultPeriode);
 while($rowPeriode = oci_fetch_row($resultPeriode)){
	 $vPeriode1=$rowPeriode[0];
	 $vPeriode2=$rowPeriode[1];
 }
	
	$queryHeader = "SELECT PERSON_ID, CUTI_TAHUNAN FROM MJ.MJ_M_CUTI WHERE CUTI_TAHUNAN >= 1 --AND PERSON_ID<>4756 ";
	$resultHeader = oci_parse($con, $queryHeader);
	oci_execute($resultHeader);
	while($row = oci_fetch_row($resultHeader)){
		$vPersonId = $row[0];
		$vCutiTahunan = $row[1];
		
		$queryJumCuti = "SELECT COUNT(-1) JumCuti FROM
        (
            SELECT DISTINCT MTT.TANGGAL
            FROM MJ.MJ_T_TIMECARD MTT
            INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
            WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
            AND MME.ELEMENT_NAME IN ('CUTI')
            AND MTT.PERSON_ID=$vPersonId
            UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
			AND (MTS.KATEGORI='Cuti')
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA', 'IJIN KHUSUS')
			AND MTT.PERSON_ID=$vPersonId
        )";
		//echo $queryJumHari;
		$resultJumCuti = oci_parse($con,$queryJumCuti);
		oci_execute($resultJumCuti);
		while($rowJumCuti = oci_fetch_row($resultJumCuti))
		{
			$vHariCuti = $rowJumCuti[0]; 
		}
		//echo $vHariCuti;
		if($vHariCuti > $vCutiTahunan){
			$vCutiTahunan = 0;
		} else {
			$vCutiTahunan = $vCutiTahunan - $vHariCuti;
		}
		
		$queryUpdate = "UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN = $vCutiTahunan, LAST_UPDATED_BY='schedulerCutiUpdate', LAST_UPDATED_DATE=SYSDATE WHERE PERSON_ID=$vPersonId";
		$resultUpdate = oci_parse($con,$queryUpdate);
		oci_execute($resultUpdate);
	}
?>