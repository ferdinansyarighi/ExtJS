<?PHP
include '../../main/koneksi.php';


$result = oci_parse($con, "SELECT SHIFT_ID,SHIFT_NAME, RANGE_EARLY_IN, RANGE_LATE_IN, RANGE_EARLY_OUT, RANGE_LATE_OUT, AKTIF, MMS.CREATED_BY, MMS.CREATED_DATE, PPF.FULL_NAME, TO_CHAR(NVL(MMS.LAST_UPDATED_DATE,MMS.CREATED_DATE), 'DD-MON-YYYY HH24:MM:SS'), ID FROM MJ.MJ_M_RANGE_SHIFT MMS, PER_PEOPLE_F PPF WHERE PPF.PERSON_ID = NVL(MMS.LAST_UPDATED_BY,MMS.CREATED_BY) ORDER BY SHIFT_NAME");

oci_execute($result);
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['DATA_SHIFT_ID']=$row[0];
	$record['DATA_SHIFT']=$row[1];
	$record['DATA_EARLY_IN']=$row[2];
	$record['DATA_LATE_IN']=$row[3];
	$record['DATA_EARLY_OUT']=$row[4];
	$record['DATA_LATE_OUT']=$row[5];
	$record['DATA_STATUS']=$row[6];
	$record['DATA_CREATED_BY']=$row[7];
	$record['DATA_CREATED_DATE']=$row[8];
	$record['DATA_UPDATE_BY']=$row[9];
	$record['DATA_UPDATE_DATE']=$row[10];
	$record['DATA_ID']=$row[11];
	$data[]=$record;
}
	
echo json_encode($data); 
?>