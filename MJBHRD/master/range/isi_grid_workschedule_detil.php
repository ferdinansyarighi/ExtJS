<?PHP
include '../../main/koneksi.php';

if (isset($_GET['shift']))
{	
	$shift = $_GET['shift'];
}

	$query = "SELECT DISTINCT E.SEQ_NO,F.NAME, E.MEANING, F.STANDARD_START || ' - ' || F.STANDARD_STOP WORKSCHEDULE
			FROM APPS.HXT_WORK_SHIFTS_FMV E
			, APPS.HXT_SHIFTS F
			WHERE  E.TWS_ID = $shift
			AND UPPER(E.MEANING) IN ('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY') -- Hari absen
			AND E.SHT_ID=F.ID
			ORDER BY E.SEQ_NO";

	$result = oci_parse($con,$query);
	oci_execute($result);
	$count = 0;
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_SEQ_NO']=$row[0];
		$record['DATA_NAMA_SHIFT']=$row[1];
		$record['DATA_HARI']=$row[2];
		$record['DATA_WORKSCHEDULE']=$row[3];
		$data[]=$record;
		$count++;
	}

	if ($count==0)
	{
		$data="salah";
	}
	 echo json_encode($data);
?>