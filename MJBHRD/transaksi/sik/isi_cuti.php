<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_pem'])){
	$nama_pem=$_POST['nama_pem'];
	$kategoriForm=$_POST['kategoriForm'];

	if($kategoriForm=='Cuti'){
		$query = "SELECT MMC.CUTI_TAHUNAN 
		FROM MJ.MJ_M_CUTI MMC
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMC.PERSON_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.FULL_NAME LIKE '$nama_pem'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$hasil=$row[0];
		}
	} elseif($kategoriForm=='Sakit'){
		$query = "SELECT MMC.CUTI_SAKIT
		FROM MJ.MJ_M_CUTI MMC
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMC.PERSON_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.FULL_NAME LIKE '$nama_pem'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$hasil=$row[0];
		}
	} else {
		$hasil=0;
	}	
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>