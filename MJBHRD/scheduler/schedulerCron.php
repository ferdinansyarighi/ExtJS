<?PHP
	include 'D:/dataSource/MJBHRD/main/koneksi.php';

	$queryCekCron = "SELECT COUNT(-1) FROM MJ.MJ_SYS_CHECK_CRON WHERE CRON_NAME = 'load_sj_shipping' AND CRON_STATUS = 1
	AND LAST_TIME <= SYSDATE - INTERVAL '4' HOUR";
	$resultCekCron = oci_parse($con,$queryCekCron);
	oci_execute($resultCekCron);
	$rowCron = oci_fetch_row($resultCekCron);
	$countCron = $rowCron[0]; 
	//echo $countData;exit;
	//echo $newformat; exit;
	if($countCron >= 1){
			$query = "UPDATE MJ.MJ_SYS_CHECK_CRON SET CRON_STATUS = 0, LAST_TIME = SYSDATE WHERE CRON_NAME = 'load_sj_shipping'";
			$result = oci_parse($con, $query);
			oci_execute($result);
		}
?>