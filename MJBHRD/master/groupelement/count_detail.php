<?PHP 
include '../../main/koneksi.php';

$dtid=$_POST['dtid']; //222209  $_POST['hd_id']
	
	$query = "SELECT count(-1)
    FROM MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM
    INNER JOIN MJ.MJ_M_USER MMU ON EGM.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON EGM.LAST_UPDATED_BY = MMU2.ID
    INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGE ON EGM.ID = MGE.ID_ELEMENT
    WHERE 1=1 AND MGE.ID_GROUP = $dtid";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$rowcount = oci_fetch_row($result);
	$totalRow = $rowcount[0];
	
	$result = array('success' => true,
					'results' => '',
					'rows' => $totalRow,
				);
	echo json_encode($result);

?>