<?PHP
include '../../main/koneksi.php';
session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
$assign_id = 0;
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$queryfilter='';
$data='';

$queryDept = "SELECT COUNT(-1) FROM PER_ASSIGNMENTS_F PAF
INNER JOIN PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
WHERE PJ.NAME LIKE '%HRD%'
AND PERSON_ID = $emp_id";
$resultDept = oci_parse($con,$queryDept);
oci_execute($resultDept);
$rowDept = oci_fetch_row($resultDept);
$countDeptHrd=$rowDept[0];

$queryGrade = "SELECT COUNT(-1)  FROM PER_ASSIGNMENTS_F PAF
            INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
            WHERE SYSDATE BETWEEN PG.DATE_FROM AND NVL(PG.DATE_TO, SYSDATE)
            AND PG.GRADE_ID BETWEEN 1061 AND 1066 
            AND PAF.PERSON_ID = $emp_id";
$resultGrade = oci_parse($con,$queryGrade);
oci_execute($resultGrade);
$rowGrade = oci_fetch_row($resultGrade);
$countGrade=$rowGrade[0];

$queryStaf = "SELECT COUNT(-1)  FROM PER_ASSIGNMENTS_F PAF
            INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
            WHERE SYSDATE BETWEEN PG.DATE_FROM AND NVL(PG.DATE_TO, SYSDATE)
            AND PG.GRADE_ID = 1069
            AND PAF.PERSON_ID = $emp_id";
$resultStaf = oci_parse($con,$queryStaf);
oci_execute($resultStaf);
$rowStaf = oci_fetch_row($resultStaf);
$countStaf=$rowStaf[0];

if($countStaf>0){
	$queryfilter .= " AND (EGM.NAMA_GROUP like '%PLANT%' OR EGM.NAMA_GROUP like '%CP%' OR EGM.NAMA_GROUP like '%RNT%')";
}

if(isset($_GET['tffilter'])){
	$nama = $_GET['tffilter'];
	$queryfilter .= "and upper(ppf.full_name) like '%".$nama."%'";
}

/* $queryYES = "SELECT PERSON_ID FROM PER_PEOPLE_F WHERE FULL_NAME LIKE '%".$nama."%'";
$resultYES = oci_parse($con, $queryYES);
oci_execute($resultYES);
while($row = oci_fetch_row($resultYES))
{
	$hasilYES=$row[0];
} */
//echo $queryfilter;exit;
if($countDeptHrd>0){
	$result = oci_parse($con, "select mlg.id, DECODE(mlg.COMPANY, '', 'All', mlg.COMPANY) COMPANY_ID, NVL(HPU.NAME, 'All') COMPANY,
    mlg.person_id, ppf.full_name, mlg.id_group, egm.nama_group,
    NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH, 
    NVL(TO_CHAR(EGM.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(EGM.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL,
    mlg.periode_gaji, PG.NAME, mlg.status
    from mj.mj_m_link_group mlg
    inner join MJ.MJ_M_GROUP_ELEMENT EGM on mlg.id_group = EGM.id 
    LEFT JOIN HR_OPERATING_UNITS HPU ON mlg.COMPANY = HPU.ORGANIZATION_ID
    inner join per_people_f ppf on mlg.person_id = ppf.person_id and 
    current_employee_flag = 'Y' and effective_end_date > sysdate
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
    AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
    inner join APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
    INNER JOIN MJ.MJ_M_USER MMU ON mlg.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON mlg.LAST_UPDATED_BY = MMU2.ID
        WHERE 1=1 $queryfilter");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_COMPANY_ID']=$row[1];
		$record['DATA_COMPANY']=$row[2];
		$record['DATA_PERSON_ID']=$row[3];

		$querypid = "SELECT COUNT(-1) 
	    FROM APPS.PER_CONTACT_RELATIONSHIPS PCR LEFT JOIN APPS.PER_CONTACT_EXTRA_INFO_F PCEI ON PCR.CONTACT_RELATIONSHIP_ID = PCEI.CONTACT_RELATIONSHIP_ID 
	    AND REPLACE(PCEI.INFORMATION_TYPE, 'BPJD', 'BPJS') LIKE 'BPJS%' INNER JOIN APPS.PER_PEOPLE_F PPFR ON PCR.CONTACT_PERSON_ID = PPFR.PERSON_ID 
	    WHERE PCR.PERSON_ID = $row[3] 
	    AND PCR.CONTACT_TYPE != 'SPV' 
	    AND REPLACE(PCEI.INFORMATION_TYPE, 'BPJD', 'BPJS') = 'BPJS_TK_CONTACT'";
		$resultpid = oci_parse($con,$querypid);
		oci_execute($resultpid);
		$rowpid = oci_fetch_row($resultpid);
		$record['BPJSTK']=$rowpid[0];

		$queryprid = "SELECT COUNT(1)
		FROM APPS.PER_CONTACT_RELATIONSHIPS PCR LEFT JOIN APPS.PER_CONTACT_EXTRA_INFO_F PCEI ON PCR.CONTACT_RELATIONSHIP_ID = PCEI.CONTACT_RELATIONSHIP_ID 
		AND REPLACE(PCEI.INFORMATION_TYPE, 'BPJD', 'BPJS') LIKE 'BPJS%' INNER JOIN APPS.PER_PEOPLE_F PPFR ON
		PCR.CONTACT_PERSON_ID = PPFR.PERSON_ID 
		WHERE PCR.PERSON_ID = $row[3]
		AND PCR.CONTACT_TYPE != 'SPV' 
		AND REPLACE(PCEI.INFORMATION_TYPE, 'BPJD', 'BPJS') = 'BPJS_CONTACT'";
		$resultprid = oci_parse($con,$queryprid);
		oci_execute($resultprid);
		$rowprid = oci_fetch_row($resultprid);
		$record['BPJSKS']=$rowprid[0];

		$record['DATA_PERSON']=$row[4];
		$record['DATA_ID_GROUP']=$row[5];
		$record['DATA_GROUP']=$row[6];
		$record['DATA_OLEH']=$row[7];
		$record['DATA_TGL']=$row[8];
		$record['DATA_PERIODE']=$row[9];
		$record['DATA_GRADE']=$row[10];
		$record['DATA_STATUS']=$row[11];
		$data[]=$record;
	}
}

echo json_encode($data); 
?>