<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";
$satuan = "";
$tgldblama='2015-02-01';

if(isset($_POST['nama_pem'])){
	$nama_pem=str_replace("'", "''", $_POST['nama_pem']);

	$querypid = "SELECT PPF.PERSON_ID
	FROM PER_PEOPLE_F PPF
	WHERE PPF.FULL_NAME = '$nama_pem'";
	$resultpid = oci_parse($con,$querypid);
	oci_execute($resultpid);
	$rowpid = oci_fetch_row($resultpid);
	$pid = $rowpid[0];
	//echo $pid;exit;
	// $query = "SELECT PJ.NAME 
	// FROM APPS.PER_PEOPLE_F PPF
	// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	// INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PPF.FULL_NAME LIKE '$nama_pem'";
	$query = "SELECT COUNT(1) JML_BPJS_KS 
	FROM APPS.PER_CONTACT_RELATIONSHIPS PCR LEFT JOIN APPS.PER_CONTACT_EXTRA_INFO_F PCEI ON PCR.CONTACT_RELATIONSHIP_ID = PCEI.CONTACT_RELATIONSHIP_ID 
	AND REPLACE(PCEI.INFORMATION_TYPE, 'BPJD', 'BPJS') LIKE 'BPJS%' INNER JOIN APPS.PER_PEOPLE_F PPFR ON
	PCR.CONTACT_PERSON_ID = PPFR.PERSON_ID WHERE PCR.PERSON_ID = $pid
	AND PCR.CONTACT_TYPE != 'SPV' AND REPLACE(PCEI.INFORMATION_TYPE, 'BPJD', 'BPJS') = 'BPJS_CONTACT'";
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