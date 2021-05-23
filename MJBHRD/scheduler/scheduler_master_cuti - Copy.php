<?PHP
 include 'E:/dataSource/MJBHRD/main/koneksi2.php'; //Koneksi ke database

$tglskr=date('Y-m-d'); 
//$tglskr='2015-11-07';
$bulan=substr($tglskr, 5, 2);

$resultSeq = oci_parse($con,"SELECT TO_CHAR(TANGGAL_REFRESH, 'YYYY-MM-DD') FROM MJ.MJ_PM_CUTI WHERE JENIS_CUTI='TAHUNAN'");
oci_execute($resultSeq);
$row = oci_fetch_row($resultSeq);
$tglCutiTahunan = $row[0];
$subtglCutiTahunan = substr($tglCutiTahunan, 5);

$resultSeq = oci_parse($con,"SELECT TO_CHAR(TANGGAL_REFRESH, 'YYYY-MM-DD') FROM MJ.MJ_PM_CUTI WHERE JENIS_CUTI='SAKIT'");
oci_execute($resultSeq);
$row = oci_fetch_row($resultSeq);
$tglCutiSakit = $row[0];
$subtglCutiSakit = substr($tglCutiSakit, 5);

$query = "SELECT PERSON_ID, TO_CHAR(EFFECTIVE_START_DATE + 90, 'YYYY-MM-DD'), TO_CHAR(EFFECTIVE_START_DATE + 365, 'YYYY-MM-DD')
FROM APPS.PER_PEOPLE_F 
WHERE EFFECTIVE_END_DATE > SYSDATE --AND PERSON_ID = 991 ";
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$vPersonId = $row[0];
	$vEfDateSakit = $row[1];
	$vEfDateTahunan = $row[2];
	$vsubEfDateSakit = substr($vEfDateSakit, 5);
	$vsubEfDateTahunan = substr($vEfDateTahunan, 5);
	$vsubEfDateTahunan2 = substr($vEfDateTahunan, 0, 4);
	$vsubTglSkr = substr($tglskr, 5);
	$vsubTglSkr2 = substr($tglskr, 0, 4);
	//echo $vsubTglSkr2;
	
	$resultCount = oci_parse($con, "SELECT COUNT(-1) FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$vCount = $rowCount[0];
	if($vCount > 0){
		if($tglskr >= $vEfDateTahunan){
			if($vsubEfDateTahunan2 == $vsubTglSkr){
				if($vsubTglSkr == $vsubEfDateTahunan){
					$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN=12 WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
					oci_execute($resultCuti);
				}
			} 
			if ($vsubEfDateTahunan2 > $vsubTglSkr){
				if($vsubTglSkr == $subtglCutiTahunan){
					$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_TAHUNAN=12 WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
					oci_execute($resultCuti);
				}
			}
		}
		//echo $vsubTglSkr . " dan " . $subtglCutiSakit;
		//echo $tglskr . " dan " . $vEfDateSakit;
		if($tglskr >= $vEfDateSakit){
			if($vsubTglSkr == $subtglCutiSakit){
				$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_SAKIT=1 WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
				oci_execute($resultCuti);
			}
			//echo "Masuk";
			$resultCuti = oci_parse($con, "SELECT BULAN, CUTI_SAKIT FROM MJ.MJ_M_CUTI WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
			oci_execute($resultCuti);
			$rowCuti = oci_fetch_row($resultCuti);
			$vBulan = $rowCuti[0];
			$vSakit = $rowCuti[1] + 1;
			$jumno=strlen($vBulan);
			if($jumno==1){
				$vBulan = "0".$vBulan;
			}
			//echo $vBulan . " dan " . $bulan;
			if ($vBulan != $bulan){
				$resultCuti = oci_parse($con,"UPDATE MJ.MJ_M_CUTI SET CUTI_SAKIT=$vSakit, BULAN=$bulan WHERE PERSON_ID=$vPersonId AND APP_ID=" . APPCODE . "");
				oci_execute($resultCuti);
			}
		}
	} else {
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_CUTI_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$rowSeq = oci_fetch_row($resultSeq);
		$seq = $rowSeq[0];
		
		$queryCuti = "INSERT INTO MJ.MJ_M_CUTI (ID, APP_ID, PERSON_ID, CUTI_TAHUNAN, CUTI_SAKIT, BULAN) VALUES ($seq, " . APPCODE . ", $vPersonId, 0, 0, 0)";
		$resultCuti = oci_parse($con,$queryCuti);
		oci_execute($resultCuti); 
	}
} 
?>