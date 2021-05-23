<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_pem'])){
	$nama_pem=str_replace("'", "''", $_POST['nama_pem']);

	// $query = "SELECT PJ.NAME 
	// FROM APPS.PER_PEOPLE_F PPF
	// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	// INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PPF.FULL_NAME LIKE '$nama_pem'";
	$query = "SELECT DISTINCT CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END NAME
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PPF.FULL_NAME LIKE '$nama_pem'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$hasil=$row[0];
	}
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>