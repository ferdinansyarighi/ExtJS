<?PHP
include '../../main/koneksi.php';

if (isset($_GET['assid']))
{	
	$id = $_GET['assid'];
	$periode1 = $_GET['tglfrom'];
	//$periode1=substr($periode1, 0, 10);
	$hari=substr($periode1, 0, 2);
	$bulan=substr($periode1, 3, 2);
	$tahun=substr($periode1, 6, 2);
	$date_from = $hari.$bulan."20".$tahun;
	//echo $date_from;exit;

	$periode2 = $_GET['tglto'];
	//$periode2=substr($periode2, 0, 10);
	$hari=substr($periode2, 0, 2);
	$bulan=substr($periode2, 3, 2);
	$tahun=substr($periode2, 6, 2);
	$date_to = $hari.$bulan."20".$tahun;
}

	$result = oci_parse($con, "SELECT DT DATES, TO_CHAR(DT, 'DAY') DAY, HWW.NAME SHIFT, HS.STANDARD_START||' - '||HS.STANDARD_STOP WORK_SCHEDULE, HS.HOURS WORK_HOUR,  NVL(MMC.SHIFT_ID, MMS.SHIFT_ID) SHIFT_ID, MMS.ID, MMS.DATE_FROM, MMS.DATE_TO
            FROM
                (
                    SELECT TRUNC ((TO_DATE('$date_to', 'DDMMYYYY')+1) - ROWNUM) DT
                    FROM DUAL CONNECT BY ROWNUM <= (TO_DATE('$date_to', 'DDMMYYYY')-(TO_DATE('$date_from', 'DDMMYYYY')-1))
                ) CAL
            LEFT JOIN APPS.HXT_HOLIDAY_DAYS_VL HHD ON CAL.DT = HHD.HOLIDAY_DATE
            LEFT JOIN MJ.MJ_M_SHIFT MMS ON CAL.DT BETWEEN MMS.DATE_FROM AND nvl(MMS.DATE_TO, to_date('12/31/4272','MM/DD/YYYY')) AND MMS.ASSIGNMENT_ID = $id AND MMS.STATUS = 'Y'
            LEFT JOIN MJ.MJ_M_CHANGE_SHIFT MMC ON MMS.ASSIGNMENT_ID = MMC.ASSIGNMENT_ID AND CAL.DT = MMC.DATE_DETAIL 
            LEFT JOIN APPS.HXT_WORK_SHIFTS_FMV HWS ON nvl(mmc.shift_id, MMS.SHIFT_ID) = HWS.TWS_ID AND TRIM(TO_CHAR(DT, 'DAY')) = UPPER(HWS.MEANING)
            LEFT JOIN APPS.HXT_SHIFTS HS ON HWS.SHT_ID = HS.ID 
            LEFT JOIN APPS.HXT_WEEKLY_WORK_SCHEDULES_FMV HWW ON nvl(mmc.shift_id, MMS.SHIFT_ID) = HWW.ID
            ORDER BY DT");

	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_DATE']=$row[0];
		$record['DATA_DAY']=$row[1];
		$record['DATA_SHIFT']=$row[2];
		$record['DATA_WORKSCHEDULE']=$row[3];
		$record['DATA_WORKHOUR']=$row[4];
		$record['DATA_SHIFT_ID']=$row[5];
		$record['DATA_ID']=$row[6];
		$record['DATA_DATEFROM']=$row[7];
		$record['DATA_DATETO']=$row[8];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>