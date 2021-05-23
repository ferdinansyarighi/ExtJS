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
	$query = "select  pps.name positions_name, ppf.rowid,ppf.person_id, ppf.town_of_birth, ppf.date_of_birth, ppf.full_name, 
        ppf.email_address, ppf.marital_status, 
        paf.location_id, paf.ORGANIZATION_ID, hla.location_code, ppf.employee_number emp_num, ppf.party_id
        , paf.effective_end_date end_date, ppf2.full_name updated_by
from    per_people_f ppf, per_assignments_f paf, per_positions pps, hr_locations_all hla, 
        fnd_user fnu, per_people_f ppf2
where   PPF.FULL_NAME LIKE '%$nama_pem%'--pps.name like '%Collec%' 
        --and paf.location_id = 160 
        and ppf.person_id = paf.person_id
        and ppf.last_updated_by = fnu.user_id
        and ppf2.person_id = fnu.employee_id
        and paf.position_id = pps.position_id(+)
        and paf.location_id = hla.location_id(+)
		and paf.primary_flag = 'Y'
        and trunc(sysdate) between paf.effective_start_date and paf.effective_end_date
        and ppf.current_employee_flag = 'Y'
        and ppf.effective_end_date > trunc(sysdate)
order by person_id desc";
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