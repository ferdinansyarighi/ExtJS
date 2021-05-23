<?PHP
include '../../main/koneksi.php';

if (isset($_GET['assignment_id']))
{	
	$id = $_GET['assignment_id'];
	$periode1 = $_GET['periode1'];
	$periode1=substr($periode1, 0, 10);
	$hari=substr($periode1, 8, 2);
	$bulan=substr($periode1, 5, 2);
	$tahun=substr($periode1, 0, 4);
	$date_from = $hari.$bulan.$tahun;
	
	$periode2 = $_GET['periode2'];
	$periode2=substr($periode2, 0, 10);
	$hari=substr($periode2, 8, 2);
	$bulan=substr($periode2, 5, 2);
	$tahun=substr($periode2, 0, 4);
	$date_to = $hari.$bulan.$tahun;
}

	$result = oci_parse($con, "SELECT DT DATES, TO_CHAR(DT, 'DAY') DAY , HWW.NAME SHIFT, HS.STANDARD_START||' - '||HS.STANDARD_STOP WORK_SCHEDULE, HS.HOURS WORK_HOUR
FROM
    (
        SELECT TRUNC ((TO_DATE('$date_to', 'DDMMYYYY')+1) - ROWNUM) DT
        FROM DUAL CONNECT BY ROWNUM <= (TO_DATE('$date_to', 'DDMMYYYY')-(TO_DATE('$date_from', 'DDMMYYYY')-1))
    ) CAL
LEFT JOIN HXT_HOLIDAY_DAYS_VL HHD ON CAL.DT = HHD.HOLIDAY_DATE
LEFT JOIN MJ.MJ_M_SHIFT MMS ON CAL.DT BETWEEN MMS.DATE_FROM AND nvl(MMS.DATE_TO, to_date('12/31/4272','MM/DD/YYYY')) AND MMS.ASSIGNMENT_ID = $id AND MMS.STATUS = 'Y'
LEFT JOIN HXT_WORK_SHIFTS_FMV HWS ON MMS.SHIFT_ID = HWS.TWS_ID AND trim(TO_CHAR(DT, 'DAY')) = UPPER(HWS.MEANING)
LEFT JOIN HXT_SHIFTS HS ON HWS.SHT_ID = HS.ID 
LEFT JOIN APPS.HXT_WEEKLY_WORK_SCHEDULES_FMV HWW ON MMS.SHIFT_ID = HWW.ID
ORDER BY DT");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_DATE']=$row[0];
		$record['DATA_DAY']=$row[1];
		$record['DATA_SHIFT']=$row[2];
		$record['DATA_WS']=$row[3];
		$record['DATA_WH']=$row[4];
		$data[]=$record;
	}
	
echo json_encode($data); 
?>