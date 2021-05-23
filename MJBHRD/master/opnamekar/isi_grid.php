<?PHP
include '../../main/koneksi.php';
$queryFilter = '';
$data = '';
$result= '';

	if(isset($_GET['karyawan'])){	
		
		$karyawan_id = $_GET['karyawan'];
		if ($karyawan_id != '' && $karyawan_id != 'null') {			
			$queryFilter .= "AND PERSON_ID = '$karyawan_id'";		
		}
	}

	if(isset($_GET['plant'])){	
		
		$plant = $_GET['plant'];
		if ($plant != '' && $plant != 'null') {			
			$queryFilter .= "AND PLANT = '$plant'";		
		}
	}

	// echo "";exit;
	$result = oci_parse($con, "SELECT ID,
		NAMA,
        HL.LOCATION_CODE PLANT,        
		STATUS,
		PERSON_ID,
		PLANT
		FROM MJ.MJ_MASTER_OPNAME MMO		 
		LEFT JOIN APPS.HR_LOCATIONS HL ON MMO.PLANT = HL.LOCATION_ID
		WHERE 1=1 $queryFilter
		");
		// oci_execute($result);
	oci_execute($result);		
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA_HR_AREA']=$row[1];
		$record['DATA_PLANT']=$row[2];
		$record['DATA_STATUS']=$row[3];
		$record['DATA_NAMA_HR_AREA_ID']=$row[4];
		$record['DATA_PLANT_ID']=$row[5];		
		$data[]=$record;
	}	
	
echo json_encode($data); 
?>