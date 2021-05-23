<?php
include '../../main/koneksi.php';

$data = "gagal";
$plant=""; 
$periode=""; 
$vPeriode1="";
$vPeriode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tglskrdipakai=date('d F Y'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
if (isset($_POST['bulan']) || isset($_POST['tahun']))
{	
	$revisi = trim($_POST['revisi']);
	$periodegaji = $_POST['periodegaji'];
	$periode_start = $_POST['periode_start'];
	$periode_end = $_POST['periode_end'];
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	$company = $_POST['company'];
	$plant = $_POST['plant'];
	//$dept = $_POST['dept'];
	$grade = $_POST['grade'];
	
	if($company != 'All'){
		$queryfilter .= " AND PAF.ORGANIZATION_ID = $company";
	}
	if($plant != ''){
		$queryfilter .= " AND HL.LOCATION_ID = $plant";
	}
	// if($dept != ''){
		// $queryfilter .= " AND PJ.JOB_ID = $dept";
	// }
	if($grade != ''){
		$queryfilter .= " AND PG.GRADE_ID = $grade";
	}
	
	if($periodegaji == 'BULANAN'){
		if($bulan == 'January'){
			$bulanAngka = '01';
			$bulanBesok = '02';
		} elseif ($bulan == 'February'){
			$bulanAngka = '02';
			$bulanBesok = '03';
		} elseif ($bulan == 'March'){
			$bulanAngka = '03';
			$bulanBesok = '04';
		} elseif ($bulan == 'April'){
			$bulanAngka = '04';
			$bulanBesok = '05';
		} elseif ($bulan == 'May'){
			$bulanAngka = '05';
			$bulanBesok = '06';
		} elseif ($bulan == 'June'){
			$bulanAngka = '06';
			$bulanBesok = '07';
		} elseif ($bulan == 'July'){
			$bulanAngka = '07';
			$bulanBesok = '08';
		} elseif ($bulan == 'August'){
			$bulanAngka = '08';
			$bulanBesok = '09';
		} elseif ($bulan == 'September'){
			$bulanAngka = '09';
			$bulanBesok = '10';
		} elseif ($bulan == 'October'){
			$bulanAngka = '10';
			$bulanBesok = '11';
		} elseif ($bulan == 'November'){
			$bulanAngka = '11';
			$bulanBesok = '12';
		} else {
			$bulanAngka = '12';
			$bulanBesok = '01';
		}
		
		//Set Periode Gajian
		$queryPeriode = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') PERIODE1 
		, TO_CHAR(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 'YYYY-MM-DD') PERIODE2
		FROM DUAL ";
		//echo $queryJumHari;
		$resultPeriode = oci_parse($con,$queryPeriode);
		oci_execute($resultPeriode);
		$rowPeriode = oci_fetch_row($resultPeriode);
		$vPeriode1 = $rowPeriode[0]; 
		$vPeriode2 = $rowPeriode[1];
	}else{
		$vPeriode1 = $periode_start; 
		$vPeriode2 = $periode_end;
	}	
	
	$vCount = 0;
	$vTotalNama = '';
	$vCheckData = '';
	$data = '';
	
	$queryGaji = "SELECT X.FULL_NAME, COUNT(-1) JUMLAH
    FROM (
SELECT DISTINCT PPF.PERSON_ID, PPF.employee_number, NVL(PPF.honors, 0) id_finger, PPF.FULL_NAME
, PAF.ORGANIZATION_ID, HL.LOCATION_ID
, PJ.JOB_ID, pps.position_id, PG.GRADE_ID
, TO_CHAR(PPF.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS DATE_OF_HIRE
, nvl(pot.harga_cicilan_p,0) cicilan_pinjaman
, nvl(pot.harga_cicilan_b,0) cicilan_bs
, NVL(TO_CHAR(PPF.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
, NVL(TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)), '') AS NO_REKENING
, NVL(TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)), '') AS ATAS_NAMA
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN MJ.MJ_M_LINK_GROUP MLG ON PPF.PERSON_ID = MLG.PERSON_ID
	AND (TRUNC(SYSDATE) BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE) AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MLG.ID = MLGD.ID_LINK_GROUP
INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MLGD.ID_ELEMENT = EGM.ID
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	AND (TRUNC(SYSDATE) BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE) AND PAF.PRIMARY_FLAG='Y'
inner join APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
inner join APPS.HR_LOCATIONS HL ON PAF.location_id = hl.location_id
INNER JOIN APPS.per_positions pps ON paf.position_id = pps.position_id
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN mj.mj_m_group_element_detail MGED ON MLGD.ID_group_detail = MGED.ID
LEFT JOIN (select person_id, outstanding_p, outstanding_b, harga_cicilan_p, harga_cicilan_b
						from mj_t_potongan
						where status='A') POT ON PPF.PERSON_ID = POT.PERSON_ID
WHERE MLG.PERIODE_GAJI = '$periodegaji' $queryfilter
--AND PPF.PERSON_ID = 3625 
and EGM.status = 'Y' and MLG.status = 'Y'
ORDER BY PPF.PERSON_ID) x
GROUP BY X.FULL_NAME";
	//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vFullName = $row[0];
		$vJumlah = $row[1];
		
		if($vJumlah >= 2) {
			if ($vCount == 0){
				$vTotalNama = $vFullName;
			} else {
				$vTotalNama = $vTotalNama . '<BR> ' . $vFullName;
			}
			$vCheckData = 'error';
			$vCount++;
		}
	}
}

$result = array('success' => true,
				'results' => $vCount,
				'rows' => $vTotalNama
			);
echo json_encode($result);

?>