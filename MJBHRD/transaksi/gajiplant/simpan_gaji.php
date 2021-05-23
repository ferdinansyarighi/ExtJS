<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 
// deklarasi variable dan session
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
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
 
$nama=""; 
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
if (isset($_POST['periodegaji']))
{	
	$revisiKe = trim($_POST['revisi']);
	$periodegaji = $_POST['periodegaji'];
	$periode_start = $_POST['periode_start'];
	$periode_end = $_POST['periode_end'];
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	$company = $_POST['company'];
	$plant = $_POST['plant'];
	
	if($company != 'All'){
		$queryfilter .= " AND PAF.ORGANIZATION_ID = $company";
	}
	if($plant != 'All'){
		$queryfilter .= " AND HL.LOCATION_ID = $plant";
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
}

/* 
$queryCekData = "SELECT COUNT(-1)
FROM MJ_M_GAJI_PLANT WHERE BULAN='$bulan' AND TAHUN='$tahun' AND REVISI=$revisi ";
//echo $queryJumHari;
$resultCekData = oci_parse($con,$queryCekData);
oci_execute($resultCekData);
$rowCekData = oci_fetch_row($resultCekData);
$vCekData = $rowCekData[0]; 

if($vCekData >= 0){
	$queryCekData = "DELETE	FROM MJ_M_GAJI _PLANT WHERE BULAN='$bulan' AND TAHUN='$tahun' AND REVISI=$revisi ";
	//echo $queryJumHari;
	$resultCekData = oci_parse($con,$queryCekData);
	oci_execute($resultCekData);
} 
*/

//Untuk menghitung hari aktif pada periode tersebut
// $vHariAktif=0;
// $queryAktif = "SELECT ((SELECT COUNT(*) FROM 
// (SELECT LEVEL AS DNUM FROM DUAL
// CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
// WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
// (SELECT COUNT(-1)
// FROM APPS.HXT_HOLIDAY_DAYS A
// , APPS.HXT_HOLIDAY_CALENDARS B
// WHERE A.HCL_ID=B.ID 
// AND B.EFFECTIVE_END_DATE>SYSDATE 
// AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD') AND '$vPeriode2'
// AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
// FROM DUAL";
// //echo $queryJumHari;
// $resultAktif = oci_parse($con,$queryAktif);
// oci_execute($resultAktif);
// while($rowAktif = oci_fetch_row($resultAktif))
// {
	// $vHariAktif = $rowAktif[0]; 
// }

//Query Gaji Karyawan	
$queryPerson = "SELECT DISTINCT PPF.PERSON_ID, PPF.employee_number, NVL(PPF.honors, 0) id_finger, PPF.FULL_NAME
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
LEFT JOIN (
                    SELECT  PERSON_ID
                            , NVL( SUM( OUTSTANDING_P ), 0 ) OUTSTANDING_P
                            , 0 OUTSTANDING_B
                            , NVL( SUM( HARGA_CICILAN_P ), 0 ) HARGA_CICILAN_P
                            , 0 HARGA_CICILAN_B
                    FROM 
                    (
                        (               
                            SELECT  MTP.PERSON_ID,
                                    SUM( NVL( MTP.NOMINAL, 0 ) * NVL( MTP.JUMLAH_CICILAN, 0 ) ) OUTSTANDING_P,
                                    0 OUTSTANDING_B,
                                    SUM( NVL( MTP.NOMINAL, 0 ) ) HARGA_CICILAN_P,
                                    0 HARGA_CICILAN_B
                            FROM    MJ.MJ_T_PINJAMAN MTP
                            WHERE   JUMLAH_CICILAN > 0
                            AND     TIPE = 'PINJAMAN PERSONAL'
                            AND     TINGKAT = 5
                            AND     STATUS_DOKUMEN = 'Validate'
                            AND     ADD_MONTHS( TRUNC( 
                                        TO_DATE( MTP.START_POTONGAN_TAHUN || LPAD( MTP.START_POTONGAN_BULAN, 2, '00' ) || '01', 'YYYYMMDD' )
                                        , 'MM' ), -1 ) <= TO_DATE( SUBSTR( '$vPeriode2', 1, 4 ) || SUBSTR( '$vPeriode2', 6, 2 ) || '01', 'YYYYMMDD' )
                            GROUP   BY MTP.PERSON_ID
                        )
                        UNION
                        (
                            SELECT  MTP.PERSON_ID,
                                    SUM( NVL( MTP.NOMINAL, 0 ) * NVL( MTP.JUMLAH_CICILAN, 0 ) ) OUTSTANDING_P,
                                    0 OUTSTANDING_B,
                                    SUM( NVL( MTP.NOMINAL, 0 ) ) HARGA_CICILAN_P,
                                    0 HARGA_CICILAN_B
                            FROM    MJ.MJ_T_PINJAMAN MTP
                            WHERE   JUMLAH_CICILAN > 0
                            AND     TIPE = 'PINJAMAN PENGGANTI INVENTARIS'
                            AND     TINGKAT = 4
                            AND     STATUS_DOKUMEN = 'Approved'
                            AND     ADD_MONTHS( TRUNC( 
                                        TO_DATE( MTP.START_POTONGAN_TAHUN || LPAD( MTP.START_POTONGAN_BULAN, 2, '00' ) || '01', 'YYYYMMDD' )
                                        , 'MM' ), -1 ) <= TO_DATE( SUBSTR( '$vPeriode2', 1, 4 ) || SUBSTR( '$vPeriode2', 6, 2 ) || '01', 'YYYYMMDD' )
                            GROUP   BY MTP.PERSON_ID
                        )
                    )
                    GROUP BY PERSON_ID
                ) POT ON PPF.PERSON_ID = POT.PERSON_ID
WHERE MLG.PERIODE_GAJI = '$periodegaji' 
$queryfilter
--AND PPF.PERSON_ID = 1389
AND PPF.ATTRIBUTE2 = 'Ya'
and EGM.status = 'Y' and MLG.status = 'Y'
ORDER BY PPF.PERSON_ID";
	//echo $queryPerson;exit;
$resultPerson = oci_parse($con,$queryPerson);
oci_execute($resultPerson);
while($row = oci_fetch_row($resultPerson)){
	
	$vPersonId = $row[0];
	$vEmpNumber = $row[1];
	$vIdFinger = $row[2];
	$vFullName = str_replace("'", "''", $row[3]);
	$vOrgid = $row[4];
	$vLocid = $row[5];
	$vJobid = $row[6];
	$vPositionid = $row[7];
	$vGradeid = $row[8];
	$hireDate = $row[9];
	$vPotongan = $row[10];
	$vBS = $row[11];
	$vTglResign = $row[12];
	$vNoReg = $row[13];
	$vAtasNama = $row[14];
	
	//echo $vPersonId;exit;
	$eGajiHarian =  0;
	$gajiMingguan = 0;
	$gajiHarian = 0;
	$totalUangLembur = 0;
	$tunMasukAwal = 0;
	$uangMakan = 0;
	$tunHariLibur = 0;
	$tunHariKerja = 0;
	$premi = 0;
	$tunJabatan = 0;
	$tunTidakIstirahat = 0;
	$BPJSKS = 0;
	$BPJSTK = 0;
	$revisi = 0;
	
	//Query untuk mengecek bahwa karyawan tersebut masuk tengah periode apa tidak
	$queryNew = "SELECT CASE WHEN TO_DATE('$hireDate', 'YYYY-MM-DD') BETWEEN TO_DATE('$vPeriode1', 'YYYY-MM-DD') AND TO_DATE('$vPeriode2', 'YYYY-MM-DD') 
	THEN 'YES' ELSE 'NO' END FROM DUAL";
	//echo $queryJumHari;
	$resultNew = oci_parse($con,$queryNew);
	oci_execute($resultNew);
	$rowNew = oci_fetch_row($resultNew);
	$vNew = $rowNew[0]; 
	if($vNew=='YES'){
		$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
		(SELECT LEVEL AS DNUM FROM DUAL
		CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$hireDate', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
		WHERE TO_CHAR((TO_DATE('$hireDate', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
		(SELECT COUNT(-1)
		FROM APPS.HXT_HOLIDAY_DAYS A
		, APPS.HXT_HOLIDAY_CALENDARS B
		WHERE A.HCL_ID=B.ID 
		AND B.EFFECTIVE_END_DATE>SYSDATE 
		AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$vPeriode1'
		AND TRIM(TO_CHAR(A.HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
		FROM DUAL";
		//echo $queryJumHari;
		$resultJumHari = oci_parse($con,$queryJumHari);
		oci_execute($resultJumHari);
		while($rowJumHari = oci_fetch_row($resultJumHari))
		{
			$vHariMasuk = $rowJumHari[0]; 
		}
	} else {
		//Query untuk mengecek Hari Kerja karyawan resign tengah periode
		$queryNew = "SELECT CASE WHEN TO_DATE('$vTglResign', 'YYYY-MM-DD') BETWEEN TO_DATE('$vPeriode1', 'YYYY-MM-DD') AND TO_DATE('$vPeriode2', 'YYYY-MM-DD') 
		THEN 'YES' ELSE 'NO' END FROM DUAL";
		//echo $queryJumHari;
		$resultNew = oci_parse($con,$queryNew);
		oci_execute($resultNew);
		$rowNew = oci_fetch_row($resultNew);
		$vNew = $rowNew[0]; 
		
		if($vNew=='YES'){
			if($periodegaji == 'BULANAN'){
				$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
				(SELECT LEVEL AS DNUM FROM DUAL
				CONNECT BY ((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1) - ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), -1)) - LEVEL >= 0) S
				WHERE TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), -1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') 
				AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD')
				AND TRIM(TO_CHAR(A.HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($con,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
			}else{
				$queryJumHari = "SELECT CASE WHEN ((SELECT (TO_DATE('$vTglResign', 'YYYY-MM-DD')-1) - (TO_DATE('$vPeriode2', 'YYYY-MM-DD') -7) FROM DUAL)-
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR((TO_DATE('$vPeriode2', 'YYYY-MM-DD') -7), 'YYYY-MM-DD') 
					AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD')
				)) > 0 THEN ((SELECT (TO_DATE('$vTglResign', 'YYYY-MM-DD')-1) - (TO_DATE('$vPeriode2', 'YYYY-MM-DD') -7) FROM DUAL)-
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR((TO_DATE('$vPeriode2', 'YYYY-MM-DD') -7), 'YYYY-MM-DD') 
					AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD')
				)) ELSE 0 END TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($con,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
			}
		} else {
			//Hari kerja normal
			$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
			WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2'
			AND TRIM(TO_CHAR(A.HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
			FROM DUAL";
			//echo $queryJumHari;
			$resultJumHari = oci_parse($con,$queryJumHari);
			oci_execute($resultJumHari);
			while($rowJumHari = oci_fetch_row($resultJumHari))
			{
				$vHariMasuk = $rowJumHari[0]; 
			}
		}
	}
	
	//Query menghitung jumlah hari masuk karyawan
	$queryJumMasuk = "SELECT COUNT(-1) JumMasuk FROM
	(
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('MASUK')
		AND MTT.PERSON_ID=$vPersonId
		UNION 
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
		AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
		AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
		AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('ALPHA','ABSEN TIDAK LENGKAP')
		AND MTT.PERSON_ID=$vPersonId
	)";
	//echo $queryJumHari;
	$resultJumMasuk = oci_parse($con,$queryJumMasuk);
	oci_execute($resultJumMasuk);
	$rowJumMasuk = oci_fetch_row($resultJumMasuk);
	$vJumMasuk = $rowJumMasuk[0]; 
	
	//Query menghitung jumlah hari Cuti karyawan
	$queryJumCuti = "SELECT COUNT(-1) JumCuti FROM
	(
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('CUTI')
		AND MTT.PERSON_ID=$vPersonId
		UNION
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
		AND (MTS.KATEGORI='Cuti' OR (MTS.KATEGORI='Ijin' 
		AND NVL(MTS.IJIN_KHUSUS, 'Kosong') NOT IN ('Kosong', 'LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')))
		AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
		AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('ALPHA', 'IJIN KHUSUS')
		AND MTT.PERSON_ID=$vPersonId
	)";
	//echo $queryJumHari;
	$resultJumCuti = oci_parse($con,$queryJumCuti);
	oci_execute($resultJumCuti);
	while($rowJumCuti = oci_fetch_row($resultJumCuti))
	{
		$vHariCuti = $rowJumCuti[0]; 
	}
	
	//Query menghitung jumlah hari sakit karyawan
	$queryJumSakit = "SELECT COUNT(-1) JumSakit FROM
	(
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('SAKIT')
		AND MTT.PERSON_ID=$vPersonId
		UNION
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Sakit') 
		AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
		AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('ALPHA')
		AND MTT.PERSON_ID=$vPersonId
	)";
	//echo $queryJumHari;
	$resultJumSakit = oci_parse($con,$queryJumSakit);
	oci_execute($resultJumSakit);
	while($rowJumSakit = oci_fetch_row($resultJumSakit))
	{
		$vHariSakit = $rowJumSakit[0]; 
	}
	
	//Query menghitung jumlah hari ijin karyawan
	$queryHariIjin = "SELECT COUNT(-1) JumIjin FROM
	(
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('IJIN')
		AND MTT.PERSON_ID=$vPersonId
		UNION
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NULL 
		AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
		AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('ALPHA')
		AND MTT.PERSON_ID=$vPersonId
	)";
	//echo $queryJumHari;
	$resultHariIjin = oci_parse($con,$queryHariIjin);
	oci_execute($resultHariIjin);
	while($rowHariIjin = oci_fetch_row($resultHariIjin))
	{
		$vHariIjin = $rowHariIjin[0]; 
	}
	
	//Query menghitung jumlah hari alpha karyawan
	$queryHariAlpha = "SELECT COUNT(-1) JumIjin FROM
	(
		SELECT DISTINCT MTT.TANGGAL
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
		AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
		AND MTS.STATUS=1 AND KATEGORI<>'Terlambat'
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('ALPHA')
		AND MTS.ID IS NULL
		AND MTT.PERSON_ID=$vPersonId
	)";
	//echo $queryJumHari;
	$resultHariAlpha = oci_parse($con,$queryHariAlpha);
	oci_execute($resultHariAlpha);
	while($rowHariAlpha = oci_fetch_row($resultHariAlpha))
	{
		$vHariAlpha = $rowHariAlpha[0]; 
	}
	
	//Query Insert Gaji (Header)
	$querySeq = "SELECT MJHR.MJ_M_GAJI_PLANT_SEQ.NEXTVAL from dual";
	$resultSeq = oci_parse($con,$querySeq);
	oci_execute($resultSeq);
	$rowSeq = oci_fetch_row($resultSeq);
	$idHeader = $rowSeq[0]; 
	if($periodegaji=='MINGGUAN'){
		$queryInsertData = "INSERT INTO MJHR.MJ_M_GAJI_PLANT (ID, PERIODE_GAJI, PERIODE_START, PERIODE_END, COMPANY_ID, PLANT_ID, REVISI, PERSON_ID, EMPLOYEE_NUMBER, FINGER_ID, FULL_NAME, DEPARTMENT_ID, JABATAN_ID, GRADE_ID, TGL_HIRE, HARI_MASUK, HARI_SAKIT, HARI_CUTI, HARI_IJIN, HARI_ALPHA, TERLAMBAT1, TERLAMBAT2, TERLAMBAT3, TERLAMBAT4, PINJAMAN, BS, ABSEN, TELAT, CREATED_BY, CREATED_DATE) VALUES ($idHeader, '$periodegaji', to_date('$periode_start', 'YYYY-MM-DD'), to_date('$periode_end', 'YYYY-MM-DD'), $vOrgid, $vLocid, $revisiKe, $vPersonId, $vEmpNumber , $vIdFinger, '$vFullName', $vJobid, $vPositionid, $vGradeid, TO_DATE('$hireDate', 'YYYY-MM-DD'), $vJumMasuk, $vHariSakit, $vHariCuti, $vHariIjin, $vHariAlpha, 0, 0, 0, 0, $vPotongan, $vBS, 0, 0, $user_id, sysdate)";
		//echo $queryInsertData;exit;
		$resultInsertData = oci_parse($conHR,$queryInsertData);
		oci_execute($resultInsertData);
	}else{
		$queryInsertData = "INSERT INTO MJHR.MJ_M_GAJI_PLANT (ID, PERIODE_GAJI, BULAN, TAHUN, COMPANY_ID, PLANT_ID, REVISI, PERSON_ID, EMPLOYEE_NUMBER, FINGER_ID, FULL_NAME, DEPARTMENT_ID, JABATAN_ID, GRADE_ID, TGL_HIRE, HARI_MASUK, HARI_SAKIT, HARI_CUTI, HARI_IJIN, HARI_ALPHA, TERLAMBAT1, TERLAMBAT2, TERLAMBAT3, TERLAMBAT4, PINJAMAN, BS, ABSEN, TELAT, CREATED_BY, CREATED_DATE) VALUES ($idHeader, '$periodegaji', $bulanAngka, $tahun, $vOrgid, $vLocid, $revisiKe, $vPersonId, $vEmpNumber , $vIdFinger, '$vFullName', $vJobid, $vPositionid, $vGradeid, TO_DATE('$hireDate', 'YYYY-MM-DD'), $vJumMasuk, 0, 0, 0, 0, 0, 0, 0, 0, $vPotongan, $vBS, 0, 0, $user_id, sysdate)";
		$resultInsertData = oci_parse($conHR,$queryInsertData);
		oci_execute($resultInsertData);
	}
	
	//Query Perhitungan Element Gaji (Detail)
	$queryGaji = "SELECT DISTINCT EGM.ID ID_ELEMENT
	, EGM.NAMA_ELEMENT
    , EGM.komponen
    , MGED.SATUAN
	, decode(MGED.DEFAULT_VALUE, 0, MLGD.VALUE, MGED.DEFAULT_VALUE) VALUE
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
							from MJ.mj_t_potongan
							where status='A') POT ON PPF.PERSON_ID = POT.PERSON_ID
	WHERE PPF.PERSON_ID = ".$vPersonId." and EGM.status = 'Y' and MLG.status = 'Y'
	ORDER BY EGM.NAMA_ELEMENT";
		//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($rowGaji = oci_fetch_row($resultGaji)){
		$nominal = 0;
		$id_element = $rowGaji[0];
		$element = $rowGaji[1];
		$komponen = $rowGaji[2];
		$satuan = $rowGaji[3];
		$value = $rowGaji[4];
		
		if($element == 'E_GAJI_HARIAN'){
			$eGajiHarian = $value;
		}
		
		switch($element){
			case 'E_GAJI_MINGGUAN':
				$gajiMingguan = $value;
				$nominal = $gajiMingguan;
				
				break;
			case 'E_GAJI_HARIAN':
				//$nominalGajiHarian = $value;
				$gajiHarian = $value * $vJumMasuk;
				$nominal = $gajiHarian;
				
				break;
			case 'E_LEMBUR':
				//Query untuk menghitung lembur
				$totalUangLembur = 0;
				$queryJamLembur = "SELECT MTSD.TOTAL_JAM, CASE WHEN TO_NUMBER(REPLACE(MTSD.JAM_FROM, ':', '.')) >= TO_NUMBER(REPLACE(NVL(MTT.JAM_MASUK, '16:00'), ':', '.')) THEN MTSD.JAM_FROM ELSE NVL(MTT.JAM_MASUK, '16:00') END X
				, NVL(REPLACE(MTT.JAM_KELUAR, '24:', '00:'), NVL(MTSI.JAM_TO, NVL(REPLACE(MTT2.JAM_KELUAR, '24:', '00:'), '23:59'))), MTS.TANGGAL_SPL,  TO_CHAR(MTT.DATE_IN, 'DD-MON-YYYY') DATE_IN, TO_CHAR(MTT.DATE_OUT, 'DD-MON-YYYY') DATE_OUT                
				FROM MJ.MJ_T_SPL MTS
				INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
				LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
				LEFT JOIN MJ.MJ_T_TIMECARD MTT2 ON MTT2.TANGGAL=MTS.TANGGAL_SPL AND MTT2.STATUS=248 AND MTT2.PERSON_ID=$vPersonId
				LEFT JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
				AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
				AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
				AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
				WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
				AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
				AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
				AND MTSD.PERSON_ID=$vPersonId
				AND MTT.ID IS NOT NULL
				ORDER BY MTS.TANGGAL_SPL
				";
				//echo $queryJamLembur;
				$resultJamLembur = oci_parse($con,$queryJamLembur);
				oci_execute($resultJamLembur);
				while($rowJamLembur = oci_fetch_row($resultJamLembur)){
					$vTotSPL = 0;
					$vJamLembur = $rowJamLembur[0]; 
					$ArrTempMasuk = explode(":", $vJamLembur);
					$vTempJamMasuk = $ArrTempMasuk[0];
					$vTempMenitMasuk = $ArrTempMasuk[1];
					$vTempJamMasuk = ($vTempJamMasuk*60) + $vTempMenitMasuk;
					
					$vJamLemburMasuk = $rowJamLembur[1]; 
					$ArrTempMasukSpl = explode(":", $vJamLemburMasuk);
					$vTempJamMasukSpl = $ArrTempMasukSpl[0];
					$vTempMenitMasukSpl = $ArrTempMasukSpl[1];
					$vTempJamMasukSpl = ($vTempJamMasukSpl*60) + $vTempMenitMasukSpl;
					
					$vJamLemburKeluar = $rowJamLembur[2]; 
					$ArrTempKeluarSpl = explode(":", $vJamLemburKeluar);
					$vTempJamKeluarSpl = $ArrTempKeluarSpl[0];
					$vTempMenitKeluarSpl = $ArrTempKeluarSpl[1];
					$vTempJamKeluarSpl = ($vTempJamKeluarSpl*60) + $vTempMenitKeluarSpl;
					
					$vDateIn = $rowJamLembur[4]; 
					$vDateOut = $rowJamLembur[5]; 
					
					if($vTempJamKeluarSpl < $vTempJamMasukSpl || $vDateOut != $vDateIn){
						$vTempJamKeluarSpl = $vTempJamKeluarSpl + 1440;
					}
					
					// if($vPersonId == 12119|| $vPersonId == 20079){
						// if($vDateOut != $vDateIn){
							// $vTempJamKeluarSpl = $vTempJamKeluarSpl + 1440;
						// }
					// }
					
					$vTotSPL = $vTempJamKeluarSpl - $vTempJamMasukSpl;
					
					//Mencari yg terkecil
					if($vTotSPL > $vTempJamMasuk){
						if($vTempJamMasuk > 0){
							if($satuan == 'IDR'){
								$uangLembur = $value*$vTempJamMasuk;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
							}else{
								$uangLembur = $eGajiHarian*($value/100)*$vTempJamMasuk;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
							}
						}
					} else {
						if($vTotSPL > 0){
							if($satuan == 'IDR'){
								$uangLembur = $value*$vTotSPL;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
							}else{
								$uangLembur = $eGajiHarian*($value/100)*$vTotSPL;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
							}
						}
					}
				}
				$nominal = $totalUangLembur;
				
				break;
			case 'E_TUNJANGAN_MASUK_AWAL':
				//Query menghitung hari masuk Lebih Awal < 5:30
				$queryJumMasukAwal = "SELECT COUNT(-1) JumMasuk FROM
				(
					SELECT DISTINCT MTT.TANGGAL
					FROM MJ.MJ_T_TIMECARD MTT
					INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
					INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
					WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
					AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
					AND MME.ELEMENT_NAME IN ('MASUK')
					AND MTT.PERSON_ID=$vPersonId
					AND MTT.JAM_MASUK < '05:30'
					UNION 
					SELECT DISTINCT MTT.TANGGAL
					FROM MJ.MJ_T_TIMECARD MTT
					INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
					INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
					INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
					AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
					AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
					AND MTS.STATUS=1
					WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
					AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
					AND MME.ELEMENT_NAME IN ('ALPHA')
					AND MTT.PERSON_ID=$vPersonId
					AND MTT.JAM_MASUK < '05:30'
				)";
				//echo $queryJumHari;
				$resultJumMasukAwal = oci_parse($con,$queryJumMasukAwal);
				oci_execute($resultJumMasukAwal);
				$rowJumMasukAwal = oci_fetch_row($resultJumMasukAwal);
				$vJumMasukAwal = $rowJumMasukAwal[0]; 
				
				$tunMasukAwal = $value * $vJumMasukAwal;
				$nominal = $tunMasukAwal;
				
				break;
			case 'E_UANG_MAKAN':
				$uangMakan = $value * $vJumMasuk;
				$nominal = $uangMakan;
				
				break;
			case 'E_TUNJANGAN_HARI_LIBUR':
				//$tunHariLibur = 0;
				$queryJumHariLibur = "SELECT COUNT(-1) jumTunLibur FROM(
					SELECT TANGGAL FROM (
						SELECT DISTINCT MTT.TANGGAL
						FROM MJ.MJ_T_TIMECARD MTT
						INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
						INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
						WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
						AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
						AND MME.ELEMENT_NAME IN ('MASUK', 'LEMBUR')
						AND MTT.PERSON_ID=$vPersonId
						--AND (TO_DATE(MTT.TANGGAL||' '||MTT.JAM_KELUAR, 'DD-MON-YY HH24:MI') - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60 > 240
						and case when TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') > TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI') and TO_CHAR(MTT.DATE_OUT, 'DDMMYYYY') = TO_CHAR(MTT.DATE_IN, 'DDMMYYYY')
							then (TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60
							else ((TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI')+1) - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60 
							end > 240
						UNION
						SELECT DISTINCT MTT.TANGGAL
						FROM MJ.MJ_T_TIMECARD MTT
						INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
						INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
						INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
						AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
						AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
						AND MTS.STATUS=1
						WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
						AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
						AND MME.ELEMENT_NAME IN ('ALPHA')
						AND MTT.PERSON_ID=$vPersonId
						--AND (TO_DATE(MTT.TANGGAL||' '||MTT.JAM_KELUAR, 'DD-MON-YY HH24:MI') - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60 > 240
						and case when TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') > TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI') and TO_CHAR(MTT.DATE_OUT, 'DDMMYYYY') = TO_CHAR(MTT.DATE_IN, 'DDMMYYYY')
							then (TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60
							else ((TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI')+1) - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60 
							end > 240
					) WHERE TANGGAL IN (
						SELECT (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM LIBUR FROM 
						(SELECT LEVEL AS DNUM FROM DUAL
						CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
						WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') IN ('SUN')
						UNION
						SELECT holiday_date LIBUR
						FROM APPS.HXT_HOLIDAY_DAYS A
						, APPS.HXT_HOLIDAY_CALENDARS B
						WHERE A.HCL_ID=B.ID 
						AND B.EFFECTIVE_END_DATE>SYSDATE 
						AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2'
					)
				)";
				//echo $queryJumHari;
				$resultJumHariLibur = oci_parse($con,$queryJumHariLibur);
				oci_execute($resultJumHariLibur);
				$rowJumHariLibur = oci_fetch_row($resultJumHariLibur);
				$vHariLibur = $rowJumHariLibur[0]; 
					
				if($satuan == 'IDR'){
					//$tunHariLibur = ($eGajiHarian + $value)*$vHariLibur; //BACKUP
					$tunHariLibur = $value*$vHariLibur;
					// $tunLibur = $nominalGajiHarian + $value;
					// $tunHariLibur = $tunHariLibur + $tunLibur;
				}else{
					//$tunHariLibur = ($eGajiHarian * ($value/100))*$vHariLibur; //BACKUP 
					$tunHariLibur = ($eGajiHarian * (($value-100)/100))*$vHariLibur;
					// $tunLibur = $nominalGajiHarian * ($value/100);
					// $tunHariLibur = $tunHariLibur + $tunLibur;
				}
				$nominal = $tunHariLibur;
				
				break;
			case 'E_TUNJANGAN_HARI_KERJA':
				$queryJumHariKerja = "SELECT COUNT(-1) jumTunKerja FROM(
					SELECT TANGGAL FROM (
						SELECT DISTINCT MTT.TANGGAL
						FROM MJ.MJ_T_TIMECARD MTT
						INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
						INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
						WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
						AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
						AND MME.ELEMENT_NAME IN ('MASUK')
						AND MTT.PERSON_ID=$vPersonId
						and case when TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') > TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI')
							then (TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60
							else ((TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI')+1) - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60 
							end >= 720
						UNION
						SELECT DISTINCT MTT.TANGGAL
						FROM MJ.MJ_T_TIMECARD MTT
						INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
						INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
						INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
						AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
						AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
						AND MTS.STATUS=1
						WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
						AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
						AND MME.ELEMENT_NAME IN ('ALPHA')
						AND MTT.PERSON_ID=$vPersonId
						and case when TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') > TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI')
							then (TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI') - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60
							else ((TO_DATE(MTT.TANGGAL||' '||REPLACE(MTT.JAM_KELUAR, '24:', '00:'), 'DD-MON-YY HH24:MI')+1) - TO_DATE(MTT.TANGGAL||' '||MTT.JAM_MASUK, 'DD-MON-YY HH24:MI'))*24*60 
							end >= 720
					)
				)";
				//echo $queryJumHari;
				$resultJumHariKerja = oci_parse($con,$queryJumHariKerja);
				oci_execute($resultJumHariKerja);
				$rowJumHariKerja = oci_fetch_row($resultJumHariKerja);
				$vHariKerja = $rowJumHariKerja[0]; 
				
				$tunHariKerja = $value * $vHariKerja;
				$nominal = $tunHariKerja;
				
				break;
			case 'E_PREMI':
				$premi = $value;
				if($vHariMasuk > $vJumMasuk){
					$premi = 0;
				}
				$nominal = $premi;
				
				break;
			case 'E_BPJS_KESEHATAN':
				$queryCekTgl1 = "SELECT COUNT(-1) FROM (               
					SELECT (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM TGL FROM (
						SELECT LEVEL AS DNUM FROM DUAL
						CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
					WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DD') = '01'
				)";
				//echo $queryCekTgl1;exit;
				$resultCekTgl1 = oci_parse($con,$queryCekTgl1);
				oci_execute($resultCekTgl1);
				$rowCekTgl1 = oci_fetch_row($resultCekTgl1);
				$cekTgl1 = $rowCekTgl1[0]; 
				//Request mbak Isa per tgl 14-Nov-2018 | request mbak isa di pot per tgl 1 sejak 30-Nov-18
				if($cekTgl1 == 1){
					$queryCekSatuan = "select EGM.ID, EGM.NAMA_ELEMENT, EGM.KOMPONEN, EGM.TYPE_ELEMENT
							, CASE WHEN MGED.DEFAULT_VALUE <> 0 THEN MGED.DEFAULT_VALUE
									ELSE MLGD.VALUE END VALUE
							, MGED.DEFAULT_VALUE, MGED.SATUAN
							, NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
							, NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
							, MGED.ID
							, CASE WHEN MGED.DEFAULT_VALUE != 0 THEN 1
								ELSE 0 
							  END CEK
							FROM MJ.MJ_M_LINK_GROUP MLG
							INNER JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MLG.ID = MLGD.ID_LINK_GROUP
							INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MLGD.ID_ELEMENT = EGM.ID
							INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGED ON MLGD.ID_GROUP_DETAIL = MGED.ID
							INNER JOIN MJ.MJ_M_USER MMU ON MGED.CREATED_BY = MMU.ID
							LEFT JOIN MJ.MJ_M_USER MMU2 ON MGED.LAST_UPDATED_BY = MMU2.ID
						WHERE 1=1 and EGM.STATUS = 'Y' 
						AND MLG.PERSON_ID = $vPersonId
						AND EGM.ID = 9";
					//echo $queryCekSatuan;exit;
					$resultCekSatuan = oci_parse($con,$queryCekSatuan);
					oci_execute($resultCekSatuan);
					$rowCekSatuan = oci_fetch_row($resultCekSatuan);
					$BPJSKS = $rowCekSatuan[4]; 
					$cekSatuan = $rowCekSatuan[6]; 
					
					if($cekSatuan != 'IDR'){
						$queryBpjsKS = "select nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
						from (select  peef.assignment_id
										,peef.element_type_id
										,pet.element_name
										,pivf.name
										,peevf.screen_entry_value
								from    pay_element_entries_f peef
										,paybv_element_type pet
										,pay_element_entry_values_f peevf
										,pay_input_values_f pivf
								where   peef.element_type_id=pet.element_type_id
								AND     peevf.element_entry_id = peef.element_entry_id
								and     pivf.input_value_id=peevf.input_value_id
								and     pivf.name<>'Pay Value'
								and     pet.element_name LIKE 'E_Bpjs_Ks%'
								and     pet.processing_type='Recurring'
								and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
								order by peef.assignment_id) bpjsks, FF_GLOBALS_F FGFKS, PER_ASSIGNMENTS_F PAF
						where TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))
						AND bpjsks.assignment_id(+) = PAF.ASSIGNMENT_ID
						and PAF.PERSON_ID = $vPersonId";
						//echo $queryBpjsKS;exit;
						$resultBpjsKS = oci_parse($con,$queryBpjsKS);
						oci_execute($resultBpjsKS);
						$rowBpjsKS = oci_fetch_row($resultBpjsKS);
						$vBPJSKS = $rowBpjsKS[0]; 
						
						$BPJSKS = ($value/100)*$vBPJSKS;
					}
				}
				$nominal = $BPJSKS;
				
				break;
			case 'E_BPJS_KETENAGAKERJAAN':
				$queryCekTgl1 = "SELECT COUNT(-1) FROM (               
					SELECT (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM TGL FROM (
						SELECT LEVEL AS DNUM FROM DUAL
						CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
					WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DD') = '01'
				)";
				//echo $queryJumHari;
				$resultCekTgl1 = oci_parse($con,$queryCekTgl1);
				oci_execute($resultCekTgl1);
				$rowCekTgl1 = oci_fetch_row($resultCekTgl1);
				$cekTgl1 = $rowCekTgl1[0]; 
				
				if($cekTgl1 == 1){
					$queryCekSatuan = "select EGM.ID, EGM.NAMA_ELEMENT, EGM.KOMPONEN, EGM.TYPE_ELEMENT
							, CASE WHEN MGED.DEFAULT_VALUE <> 0 THEN MGED.DEFAULT_VALUE
									ELSE MLGD.VALUE END VALUE
							, MGED.DEFAULT_VALUE, MGED.SATUAN
							, NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
							, NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
							, MGED.ID
							, CASE WHEN MGED.DEFAULT_VALUE != 0 THEN 1
								ELSE 0 
							  END CEK
							FROM MJ.MJ_M_LINK_GROUP MLG
							INNER JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MLG.ID = MLGD.ID_LINK_GROUP
							INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MLGD.ID_ELEMENT = EGM.ID
							INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGED ON MLGD.ID_GROUP_DETAIL = MGED.ID
							INNER JOIN MJ.MJ_M_USER MMU ON MGED.CREATED_BY = MMU.ID
							LEFT JOIN MJ.MJ_M_USER MMU2 ON MGED.LAST_UPDATED_BY = MMU2.ID
						WHERE 1=1 and EGM.STATUS = 'Y' 
						AND MLG.PERSON_ID = $vPersonId
						AND EGM.ID = 10";
					//echo $queryCekTgl1;exit;
					$resultCekSatuan = oci_parse($con,$queryCekSatuan);
					oci_execute($resultCekSatuan);
					$rowCekSatuan = oci_fetch_row($resultCekSatuan);
					$BPJSTK = $rowCekSatuan[4]; 
					$cekSatuan = $rowCekSatuan[6]; 
					
					if($cekSatuan != 'IDR'){
						$queryBpjsTK = "SELECT nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
						FROM PER_ASSIGNMENTS_F PAF, (select  peef.assignment_id
												,peef.element_type_id
												,pet.element_name
												,pivf.name
												,peevf.screen_entry_value
										from    pay_element_entries_f peef
												,paybv_element_type pet
												,pay_element_entry_values_f peevf
												,pay_input_values_f pivf
										where   peef.element_type_id=pet.element_type_id
										AND     peevf.element_entry_id = peef.element_entry_id
										and     pivf.input_value_id=peevf.input_value_id
										and     pivf.name<>'Pay Value'
										and     pet.element_name LIKE 'E_Bpjs_Tk%'
										and     pet.processing_type='Recurring'
										and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
										order by peef.assignment_id) bpjstk, FF_GLOBALS_F FGFTK
						WHERE bpjstk.assignment_id(+)=PAF.assignment_id  
						AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
						AND PAF.PERSON_ID = $vPersonId";
						//echo $queryJumHari;
						$resultBpjsTK = oci_parse($con,$queryBpjsTK);
						oci_execute($resultBpjsTK);
						$rowBpjsTK = oci_fetch_row($resultBpjsTK);
						$vBPJSTK = $rowBpjsTK[0]; 
						
						$BPJSTK = ($value/100)*$vBPJSTK;
					}
				}
				$nominal = $BPJSTK;
				
				break;
			case 'E_REVISI':
				if($komponen == '-'){
					$revisi = $komponen.$value;
				}else{
					$revisi = $value;
				}
				//$revisi = $komponen.$value;
				$nominal = $value;
				
				break;
			case 'E_TUNJANGAN_JABATAN':
				$queryCekTgl1 = "SELECT COUNT(-1) FROM (               
					SELECT (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM TGL FROM (
						SELECT LEVEL AS DNUM FROM DUAL
						CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
					WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DD') = '01'
				)";
				//echo $queryJumHari;
				$resultCekTgl1 = oci_parse($con,$queryCekTgl1);
				oci_execute($resultCekTgl1);
				$rowCekTgl1 = oci_fetch_row($resultCekTgl1);
				$cekTgl1 = $rowCekTgl1[0]; 
				
				if($cekTgl1 == 1){
					$tunJabatan = $value;
				}
				$nominal = $tunJabatan;
				
				break;
			case 'E_TUNJANGAN_TIDAK_ISTIRAHAT':
				$tunTidakIstirahat = 0;//$value;
				$nominal = $tunTidakIstirahat;
				
				break;
		}
		
		$queryInsertDataDt = "INSERT INTO MJHR.MJ_M_GAJI_PLANT_DT (ID, HDID, ELEMENT_GAJI, NOMINAL, CREATED_BY, CREATED_DATE) VALUES (MJHR.MJ_M_GAJI_PLANT_DT_SEQ.NEXTVAL, $idHeader, $id_element, $nominal, $user_id, sysdate)";
		$resultInsertDataDt = oci_parse($conHR,$queryInsertDataDt);
		oci_execute($resultInsertDataDt);
	}
	// echo 
	// 'eGajiHarian =  '.$eGajiHarian.'<br>'.
	// 'gajiMingguan = '.$gajiMingguan.'<br>'.
	// 'gajiHarian =  '.$gajiHarian.'<br>'.
	// 'totalUangLembur = '.$totalUangLembur.'<br>'.
	// 'tunMasukAwal = '.$tunMasukAwal.'<br>'.
	// 'uangMakan =  '.$uangMakan.'<br>'.
	// 'tunHariLibur =  '.$tunHariLibur.'<br>'.
	// 'tunHariKerja =  '.$tunHariKerja.'<br>'.
	// 'premi =  '.$premi.'<br>'.
	// 'tunJabatan =  '.$tunJabatan.'<br>'.
	// 'tunTidakIstirahat =  '.$tunTidakIstirahat.'<br>'.
	// 'BPJSKS =  '.$BPJSKS.'<br>'.
	// 'BPJSTK =  '.$BPJSTK.'<br>'.
	// 'revisi =  '.$revisi.'<br>';exit;
	//echo $gajiHarian.' - '.$uangLembur;exit;
	$vTotGajiPlus = $gajiMingguan + $gajiHarian + $totalUangLembur + $tunMasukAwal + $uangMakan + $tunHariLibur + $tunHariKerja + $premi + $tunJabatan + $tunTidakIstirahat;
	$vTotGajiMinus = $BPJSKS + $BPJSTK;
	$vTotGajiSkr = ($gajiMingguan+$gajiHarian+$totalUangLembur+$tunMasukAwal+$uangMakan+$tunHariLibur+$tunHariKerja+$premi+$tunJabatan+$tunTidakIstirahat)-($BPJSKS+$BPJSTK)+($revisi);
	//$vTotGajiTransfer = $vTotGajiSkr + $revisi;
	
	if($vNoReg==''){
		$queryUpdData = "UPDATE MJHR.MJ_M_GAJI_PLANT SET TOTAL_GAJI = $vTotGajiSkr, TRANS_TUNAI = 1 where id = $idHeader";
		$resultUpdData = oci_parse($conHR,$queryUpdData);
		oci_execute($resultUpdData);
	}else{
		$queryUpdData = "UPDATE MJHR.MJ_M_GAJI_PLANT SET TOTAL_GAJI = $vTotGajiSkr, TRANS_TUNAI = 0 where id = $idHeader";
		$resultUpdData = oci_parse($conHR,$queryUpdData);
		oci_execute($resultUpdData);
	}
	$data = "sukses";
}
$result = array('success' => true,
				'results' => '',
				'rows' => $data
			);
echo json_encode($result);
?>