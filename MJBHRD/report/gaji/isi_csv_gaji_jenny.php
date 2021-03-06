<?php
include '../../main/koneksi.php';


$nama=""; 
$plant=""; 
$periode=""; 
$periode1="";
$periode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
if (isset($_GET['rekening']) || isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	$rekening = trim($_GET['rekening']);
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	
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
	
	$periode = $tahun . $bulanBesok . '01';
}


 $namaFile ="Report_Gaji_" . $bulan ."_" . $tahun . ".csv";

$dataArray = array();

// $arrlength = count($recordHeader);
// for($x = 0; $x < $arrlength; $x++) {
    // echo $recordHeader[$x];
    // echo "<br>";
// }

$countData = 0;
$sumGaji = 0;
$isiKaryawan = ""; 

	$queryGaji = "SELECT  DISTINCT 
        PPF.FULL_NAME
        ,PPF.EMPLOYEE_NUMBER
        ,PPF.HONORS AS FINGER_ID
        ,TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) AS NO_REKENING
        ,TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AS ATAS_NAMA
        ,(CAST(NVL(UTJ.UANG_TUNJAB, 0) AS DECIMAL)
        + (CAST(NVL(UM.UANG_MAKAN, 0) AS DECIMAL) * CAST(NVL(TM.TOTAL_MASUK, 0) AS DECIMAL))
        + (CAST(NVL(UT.UANG_TRANSPORT, 0) AS DECIMAL) * CAST(NVL(TM.TOTAL_MASUK, 0) AS DECIMAL)) 
        + CAST(NVL(UTK.UANG_TUNKER, 0) AS DECIMAL)
        + CAST(NVL(UTL.UANG_TUNLOK, 0) AS DECIMAL)
        + CAST(NVL(UL.UANG_TOTAL, 0) AS DECIMAL)
        + CAST(NVL(UTG.UANG_TUNGRADE, 0) AS DECIMAL)) - (CAST(NVL(UTOT.UANG_TOTAL, 0) AS DECIMAL)
        + CAST(NVL(PT.UANG_TOTAL, 0) AS DECIMAL)
        + CAST(NVL(BKS.BPJS_KESEHATAN, 0) AS DECIMAL)
        + CAST(NVL(BTK.BPJS_TENAGAKERJA, 0) AS DECIMAL)
        + CAST(NVL(PPH.POTONGAN_PPH, 0) AS DECIMAL)
        + CAST(NVL(MTP.HARGA_CICILAN_P, 0) AS DECIMAL)
        + CAST(NVL(MTP.HARGA_CICILAN_B, 0) AS DECIMAL)) 
        + (CAST(NVL(URG.UANG_REVGAJ, 0) AS DECIMAL)) AS TOTAL_GAJI
        ,TO_CHAR(PPF.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
        ,GP.GAJI_POKOK
FROM    APPS.PER_PEOPLE_F PPF
        ,APPS.PER_ASSIGNMENTS_F PAF
        ,APPS.PER_JOBS PJ
        ,APPS.PER_POSITIONS PP
        ,APPS.HR_LOCATIONS HL
        ,MJ.MJ_T_POTONGAN MTP
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS GAJI_POKOK
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Gaji_Pokok' AND PIV.NAME='Gaji Pokok') GP
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_MAKAN
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Uang_Makan' AND PIV.NAME='Uang Makan') UM 
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_TRANSPORT
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Uang_Transport' AND PIV.NAME='Uang Transport') UT 
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_TUNJAB
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Tunjangan_Jabatan' AND PIV.NAME='Tunjangan Jabatan') UTJ 
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_TUNLOK
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Tunjangan_Lokasi' AND PIV.NAME='Tunjangan Lokasi') UTL 
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_TUNGRADE
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Tunjangan_Grade' AND PIV.NAME='Tunjangan Grade') UTG 
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS BPJS_KESEHATAN
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME LIKE 'E_Bpjs_Ks%' AND PIV.NAME='Pay Value') BKS 
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS BPJS_TENAGAKERJA
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME LIKE 'E_Bpjs_Tk%' AND PIV.NAME='Pay Value') BTK
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS POTONGAN_PPH
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_PPH_21' AND PIV.NAME='Pay Value') PPH
        ,(SELECT DISTINCT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_TUNKER
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Tunjangan_Kerajinan' AND PIV.NAME='Pay Value') UTK
        ,(SELECT PAPF.PERSON_ID, SUM(PRRV.RESULT_VALUE) AS UANG_TOTAL
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME IN ('E_Alpha', 'E_Ijin') AND PIV.NAME='Potongan'
        GROUP BY PAPF.PERSON_ID) UTOT
        ,(SELECT PAPF.PERSON_ID, SUM(PRRV.RESULT_VALUE) AS UANG_TOTAL
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME ='E_Terlambat' AND PIV.NAME='Potongan'
        GROUP BY PAPF.PERSON_ID) PT
        ,(SELECT PAPF.PERSON_ID, SUM(PRRV.RESULT_VALUE) AS UANG_TOTAL
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Lembur' AND PIV.NAME='Pay Value'
        GROUP BY PAPF.PERSON_ID) UL
        ,(SELECT PAPF.PERSON_ID, COUNT(-1) AS TOTAL_MASUK
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE   
        PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Masuk' AND PIV.NAME='Hours'
        GROUP BY PAPF.PERSON_ID) TM
        ,(SELECT PAPF.PERSON_ID, COUNT(-1) AS TOTAL_CUTI
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE   
        PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Cuti' AND PIV.NAME='Hours'
        GROUP BY PAPF.PERSON_ID) TC
        ,(SELECT PAPF.PERSON_ID, COUNT(-1) AS TOTAL_SAKIT
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE   
        PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Sakit' AND PIV.NAME='Hours'
        GROUP BY PAPF.PERSON_ID) TS
        ,(SELECT PAPF.PERSON_ID, PRRV.RESULT_VALUE AS UANG_REVGAJ
         FROM APPS.PAY_PAYROLL_ACTIONS PPA
        ,APPS.PAY_ASSIGNMENT_ACTIONS PAA
        ,APPS.PAY_PAYROLLS_F PP
        ,APPS.PAY_RUN_RESULTS PRR
        ,APPS.PAY_RUN_RESULT_VALUES PRRV
        ,APPS.PAY_INPUT_VALUES_F PIV
        ,APPS.PAY_ELEMENT_TYPES_F PET
        ,APPS.PER_ALL_ASSIGNMENTS_F PAAF
        ,APPS.PER_ALL_PEOPLE_F PAPF
        WHERE  PPA.PAYROLL_ACTION_ID = PAA.PAYROLL_ACTION_ID
        AND PPA.PAYROLL_ID = PP.PAYROLL_ID
        AND PAA.ASSIGNMENT_ACTION_ID = PRR.ASSIGNMENT_ACTION_ID
        AND PRR.RUN_RESULT_ID= PRRV.RUN_RESULT_ID
        AND PRRV.INPUT_VALUE_ID = PIV.INPUT_VALUE_ID
        AND PIV.ELEMENT_TYPE_ID = PET.ELEMENT_TYPE_ID
        AND PAAF.ASSIGNMENT_ID = PAA.ASSIGNMENT_ID
        AND PAAF.PERSON_ID = PAPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PP.EFFECTIVE_START_DATE AND PP.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PET.EFFECTIVE_START_DATE AND PET.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PIV.EFFECTIVE_START_DATE AND PIV.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAAF.EFFECTIVE_START_DATE AND PAAF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAPF.EFFECTIVE_START_DATE AND PAPF.EFFECTIVE_END_DATE
        AND TRIM(TO_CHAR(PPA.EFFECTIVE_DATE,'Month')) || TO_CHAR(PPA.EFFECTIVE_DATE,'-YYYY')='$bulan-$tahun'
        AND PET.ELEMENT_NAME='E_Revisi_Gaji' AND PIV.NAME='Pay Value'
        ORDER BY EMPLOYEE_NUMBER) URG
WHERE   PAF.PERSON_ID=PPF.PERSON_ID
        AND PAF.JOB_ID=PJ.JOB_ID
        AND PAF.POSITION_ID=PP.POSITION_ID
        AND PAF.LOCATION_ID=HL.LOCATION_ID
        AND GP.PERSON_ID=PPF.PERSON_ID
        AND UM.PERSON_ID=PPF.PERSON_ID
        AND UT.PERSON_ID=PPF.PERSON_ID
        AND UTJ.PERSON_ID=PPF.PERSON_ID
        AND UTL.PERSON_ID(+)=PPF.PERSON_ID
        AND UTG.PERSON_ID=PPF.PERSON_ID
        AND BKS.PERSON_ID(+)=PPF.PERSON_ID
        AND BTK.PERSON_ID(+)=PPF.PERSON_ID
        AND PPH.PERSON_ID(+)=PPF.PERSON_ID
        AND UTK.PERSON_ID(+)=PPF.PERSON_ID
        AND UTOT.PERSON_ID(+)=PPF.PERSON_ID
        AND TM.PERSON_ID(+)=PPF.PERSON_ID
        AND TC.PERSON_ID(+)=PPF.PERSON_ID
        AND TS.PERSON_ID(+)=PPF.PERSON_ID
        AND MTP.PERSON_ID(+)=PPF.PERSON_ID
        AND PT.PERSON_ID(+)=PPF.PERSON_ID
        AND UL.PERSON_ID(+)=PPF.PERSON_ID
        AND URG.PERSON_ID(+)=PPF.PERSON_ID
        AND TRUNC(SYSDATE) BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
        AND TRUNC(SYSDATE) BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE
        AND TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) IS NOT NULL
		AND NVL(PAF.PEOPLE_GROUP_ID,0) = 1061
		AND PAF.PAYROLL_ID IS NOT NULL
		AND (CAST(NVL(UTJ.UANG_TUNJAB, 0) AS DECIMAL)
        + (CAST(NVL(UM.UANG_MAKAN, 0) AS DECIMAL) * CAST(NVL(TM.TOTAL_MASUK, 0) AS DECIMAL))
        + (CAST(NVL(UT.UANG_TRANSPORT, 0) AS DECIMAL) * CAST(NVL(TM.TOTAL_MASUK, 0) AS DECIMAL)) 
        + CAST(NVL(UTK.UANG_TUNKER, 0) AS DECIMAL)
        + CAST(NVL(UTL.UANG_TUNLOK, 0) AS DECIMAL)
        + CAST(NVL(UL.UANG_TOTAL, 0) AS DECIMAL)
        + CAST(NVL(UTG.UANG_TUNGRADE, 0) AS DECIMAL)) - (CAST(NVL(UTOT.UANG_TOTAL, 0) AS DECIMAL)
        + CAST(NVL(PT.UANG_TOTAL, 0) AS DECIMAL)
        + CAST(NVL(BKS.BPJS_KESEHATAN, 0) AS DECIMAL)
        + CAST(NVL(BTK.BPJS_TENAGAKERJA, 0) AS DECIMAL)
        + CAST(NVL(PPH.POTONGAN_PPH, 0) AS DECIMAL)
        + CAST(NVL(MTP.HARGA_CICILAN_P, 0) AS DECIMAL)
        + CAST(NVL(MTP.HARGA_CICILAN_B, 0) AS DECIMAL)) 
        + (CAST(NVL(URG.UANG_REVGAJ, 0) AS DECIMAL)) + GP.GAJI_POKOK > 0";
		//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$fullName = $row[0];
		$employeeNumber = $row[1];
		$fingerId = $row[2];
		$noRekening = $row[3];
		$atasNama = $row[4];
		$totalGaji = $row[5];
		$hireDate = $row[6];
		$gajiPokokAsli = $row[7];
		
		$queryJumHari = "SELECT CASE WHEN TO_DATE('$hireDate', 'YYYY-MM-DD') BETWEEN ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) AND TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') 
		THEN (((SELECT COUNT(*) FROM 
		(SELECT LEVEL AS DNUM FROM DUAL
		CONNECT BY (TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') - TO_DATE('$hireDate', 'YYYY-MM-DD') + 1) - LEVEL >= 0) S
		WHERE TO_CHAR(SYSDATE + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
		(SELECT COUNT(-1)
		FROM APPS.HXT_HOLIDAY_DAYS A
		, APPS.HXT_HOLIDAY_CALENDARS B
		WHERE A.HCL_ID=B.ID 
		AND B.EFFECTIVE_END_DATE>SYSDATE 
		AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$tahun-$bulanAngka-21')) * ($gajiPokokAsli/25))
		ELSE $gajiPokokAsli 
		END
		FROM DUAL";
		//echo $queryJumHari;
		$resultJumHari = oci_parse($con,$queryJumHari);
		oci_execute($resultJumHari);
		$rowJumHari = oci_fetch_row($resultJumHari);
		$JumHari = $rowJumHari[0]; 
		
		// echo $JumHari;
		// echo "<BR>";
		
		$sumGaji = $sumGaji + $totalGaji + $JumHari;
		$totalGaji = $totalGaji + $JumHari;
		$recordDetail = array($noRekening, $atasNama, $rekening, '', '', 'IDR', $totalGaji, '', '', 'IBU', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'extended payment detail1');
		$dataArray[$countData] = $recordDetail;
		$countData++;
	}
	
	
$fp = fopen($namaFile, "w");

$recordHeader = array('P', $periode, $rekening, $countData, $sumGaji);
fputcsv($fp, $recordHeader);

for($x = 0; $x < $countData; $x++){
	fputcsv($fp, $dataArray[$x]);
}

fclose($fp);

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $namaFile .'');
readfile($namaFile);

exit();

?>