<?php
include '../main/koneksi.php';

	$query = "
	SELECT DISTINCT TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') || ' - ' ||TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD') PERIODE
	FROM MJ.MJ_T_RITASI_QC
	ORDER BY PERIODE DESC";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[0];
		$data[]=$record;
	}

	echo json_encode($data);
?>