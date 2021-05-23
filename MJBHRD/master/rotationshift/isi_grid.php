<?PHP
include '../../main/koneksi.php';
$countid = 1;
$filter='';
//$queryfilter=' AND MGE.ID_ELEMENT_GAJI_MINGGUAN IS NULL ';
if (isset($_GET['assignment_id']))
{	
	$id = $_GET['assignment_id'];
	$filter = " AND MMS.assignment_id = $id";
}
	
	$result2 = oci_parse($con, "select to_char(max(mtt.tanggal), 'YYYYMMDD') from mj.mj_t_timecard mtt
		inner join per_assignments_f paf on mtt.person_id = paf.person_id
		where paf.assignment_id = $id
		group by mtt.person_id
	");
	oci_execute($result2);
	$rowMax = oci_fetch_row($result2);
	$maxDate = $rowMax[0];
	
	$result = oci_parse($con, "select MMS.SHIFT_ID, HWW.NAME, TO_CHAR(MMS.DATE_FROM, 'YYYY-MM-DD') DATE_FROM, TO_CHAR(MMS.DATE_TO, 'YYYY-MM-DD') DATE_TO, MMS.ID
	from mj.MJ_M_SHIFT MMS
	LEFT JOIN APPS.HXT_WEEKLY_WORK_SCHEDULES_FMV HWW ON MMS.SHIFT_ID = HWW.ID
	WHERE 1=1 $filter
	order by MMS.DATE_FROM
	");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_NO']=$countid;
		$record['DATA_SHIFT_ID']=$row[0];
		$record['DATA_NAMA']=$row[1];
		$record['DATA_DATE_FROM']=$row[2];
		$record['DATA_DATE_TO']=$row[3];
		$record['DATA_ID']=$row[4];
		$date_fromNum = str_replace("-","",$row[2]);
		if($date_fromNum > $maxDate){
			$record['DATA_CEK']=0;
		}else{
			$record['DATA_CEK']=1;
		}
		$data[]=$record;
		$countid++;
	}
	
echo json_encode($data); 
?>