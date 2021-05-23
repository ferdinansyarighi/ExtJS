<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Rekap_Lembur_Karyawan.xls';
$vPeriode1="";
$vPeriode2="";
$queryfilter = "";
if (isset($_GET['periode']))
{	
	$periode = $_GET['periode'];
	$vPeriode1 = substr($periode, 0, 10);
	$vPeriode2 = substr($periode, 15, 10);
	// $nama = $_GET['nama'];
	// $dept = $_GET['dept'];
	// if($nama!='' AND $nama!='null'){
		// $queryfilter .= " AND PERSON_ID='$nama'";
	// }
	// if($dept!='' AND $dept!='null'){
		// $queryfilter .= " AND DEPT='$dept'";
	// }
}
// header file excel
header("Status: 200");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header("Pragma: hack");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Description: File Transfer");
	header("Content-Type: application/force-download");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment; filename=\"".$namaFile."\""); 
	header("Content-Transfer-Encoding: binary");

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../excel/PHPExcel.php';

// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("MJB")
							 ->setLastModifiedBy("MJB")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");

// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Report Rekap Lembur Karyawan')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'Nama Karyawan')
            ->setCellValue('C3', 'Dept')
            ->setCellValue('D3', 'Jabatan')
            ->setCellValue('E3', 'Tgl Lembur')
            ->setCellValue('F3', 'Jumlah Jam Lembur')
            ->setCellValue('G3', 'No SPL')
            ->setCellValue('H3', 'Keterangan');

$xlsRow = 3;
$countHeader = 0;

// $vPeriode1 = '2017-03-21';
// $vPeriode2 = '2017-04-20';

$queryKaryawan = "SELECT DISTINCT PPF.PERSON_ID
, PPF.FULL_NAME
, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) AS DEPT
, REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS JABATAN
, HL.LOCATION_CODE
--, MTT.TANGGAL TANGGAL_LEMBUR
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
LEFT JOIN APPS.PER_PERSON_TYPES PPT ON PPT.PERSON_TYPE_ID=PPF.PERSON_TYPE_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID=HL.LOCATION_ID
INNER JOIN MJ.MJ_T_TIMECARD MTT ON MTT.PERSON_ID=PPF.PERSON_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
AND PAF.EFFECTIVE_END_DATE > SYSDATE
AND PAF.PEOPLE_GROUP_ID <> 3061
AND PAF.PRIMARY_FLAG='Y'
AND PAF.PAYROLL_ID IS NOT NULL
AND PPT.USER_PERSON_TYPE <> 'Ex-employee'
AND MTT.STATUS = 253
AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$vPeriode1'
AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$vPeriode2'
UNION
SELECT DISTINCT PPF.PERSON_ID
, PPF.FULL_NAME
, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) AS DEPT
, REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS JABATAN
, HL.LOCATION_CODE
--, MTT.TANGGAL TANGGAL_LEMBUR
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
LEFT JOIN APPS.PER_PERSON_TYPES PPT ON PPT.PERSON_TYPE_ID=PPF.PERSON_TYPE_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID=HL.LOCATION_ID
INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.PERSON_ID=PPF.PERSON_ID
INNER JOIN MJ.MJ_T_SPL MTS ON MTS.ID=MTSD.MJ_T_SPL_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
AND PAF.EFFECTIVE_END_DATE > SYSDATE
AND PAF.PEOPLE_GROUP_ID <> 3061
AND PAF.PRIMARY_FLAG='Y'
AND PAF.PAYROLL_ID IS NOT NULL
AND PPT.USER_PERSON_TYPE <> 'Ex-employee'
AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
ORDER BY FULL_NAME
";
//echo $queryKaryawan;
$resultKaryawan = oci_parse($con,$queryKaryawan);
oci_execute($resultKaryawan);
while($rowKaryawan = oci_fetch_row($resultKaryawan))
{
		$vPersonId = $rowKaryawan[0];
		$vFullName = $rowKaryawan[1];
		$vDept = $rowKaryawan[2];
		$vJabatan = $rowKaryawan[3];
		$vLokasi = $rowKaryawan[4];
		$vTotJamLembur = 0;
		$vBatasTotJamLembur = 0;
		
		$queryTotalJamLembur = "SELECT JUMLAH_LEMBUR 
		FROM MJ.MJ_M_JAMLEMBUR MMJ
		INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMJ.PLANT_ID
		WHERE HL.LOCATION_CODE = '$vLokasi'";
		//echo $queryTotalJamLembur;
		$resultTotalJamLembur = oci_parse($con,$queryTotalJamLembur);
		oci_execute($resultTotalJamLembur);
		$rowTotalJamLembur = oci_fetch_row($resultTotalJamLembur);
		$vBatasTotJamLembur = $rowTotalJamLembur[0]; 
		
		$queryJamLembur = "SELECT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, NVL(MTSI.JAM_TO, NVL(MTT2.JAM_KELUAR, '23:59')))
		, MTS.TANGGAL_SPL, MTS.NOMOR_SPL, MTSD.PEKERJAAN          
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
		--AND MTS.MANAGER = 'Catur Susilo, Mr. Didik DCS'
		ORDER BY MTS.TANGGAL_SPL
		";
		//echo $queryJamLembur;
		$resultJamLembur = oci_parse($con,$queryJamLembur);
		oci_execute($resultJamLembur);
		while($rowJamLembur = oci_fetch_row($resultJamLembur))
		{
			
			$vTotJamLembur = 0;
			$vTotSPL = 0;
			$vTotJamLemburTemp = 0;
			$vTglSPL = $rowJamLembur[3];
			$vNoSPL = $rowJamLembur[4];
			$vKet = $rowJamLembur[5];
			$vJamLembur = $rowJamLembur[0]; 
			$ArrTempMasuk = explode(":", $vJamLembur);
			$vTempJamMasuk = $ArrTempMasuk[0];
			$vTempMenitMasuk = $ArrTempMasuk[1];
			$vTempJamMasuk = $vTempJamMasuk + ($vTempMenitMasuk/60);
			
			$vJamLemburMasuk = $rowJamLembur[1]; 
			$ArrTempMasukSpl = explode(":", $vJamLemburMasuk);
			$vTempJamMasukSpl = $ArrTempMasukSpl[0];
			$vTempMenitMasukSpl = $ArrTempMasukSpl[1];
			$vTempJamMasukSpl = $vTempJamMasukSpl + ($vTempMenitMasukSpl/60) + 1;
			
			$vJamLemburKeluar = $rowJamLembur[2]; 
			$ArrTempKeluarSpl = explode(":", $vJamLemburKeluar);
			$vTempJamKeluarSpl = $ArrTempKeluarSpl[0];
			$vTempMenitKeluarSpl = $ArrTempKeluarSpl[1];
			$vTempJamKeluarSpl = $vTempJamKeluarSpl + ($vTempMenitKeluarSpl/60);
			
			$vTotSPL = $vTempJamKeluarSpl - $vTempJamMasukSpl;
			//echo $vTotJamLembur;
			if($vTotJamLembur < $vBatasTotJamLembur){
				if($vTotSPL > $vTempJamMasuk){
					if($vTempJamMasuk > 0){
						$vTotJamLemburTemp = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						if($vTotJamLemburTemp > $vBatasTotJamLembur){
							$vTotJamLemburTemp = $vBatasTotJamLembur - $vTotJamLembur;
							//$vTotUangLembur = $vTotUangLembur + ($vTotJamLemburTemp * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + $vTotJamLemburTemp;
							//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						} else {
							//$vTotUangLembur = $vTotUangLembur + (round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
							//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						}
					}
				} else {
					if($vTotSPL > 0){
						$vTotJamLemburTemp = $vTotJamLembur + round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
						if($vTotJamLemburTemp > $vBatasTotJamLembur){
							$vTotJamLemburTemp = $vBatasTotJamLembur - $vTotJamLembur;
							//$vTotUangLembur = $vTotUangLembur + ($vTotJamLemburTemp * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + $vTotJamLemburTemp;
							//echo '<br> 1 : ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
						} else {
							//$vTotUangLembur = $vTotUangLembur + (round($vTotSPL, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
							//echo '<br> 1 : ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
						}
					}
				}
			}
			$countHeader++;
			$xlsRow++;
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vFullName)
						->setCellValue('C' . $xlsRow, $vDept)
						->setCellValue('D' . $xlsRow, $vJabatan)
						->setCellValue('E' . $xlsRow, $vTglSPL)
						->setCellValue('F' . $xlsRow, $vTotJamLembur)
						->setCellValue('G' . $xlsRow, $vNoSPL)
						->setCellValue('H' . $xlsRow, $vKet);
		}
		
		$queryJamLembur1 = "SELECT DISTINCT MTSD.TOTAL_JAM, MTS.TANGGAL_SPL, REPLACE(MTSD.JAM_FROM, ':', '') JAM_FROM, MTS.NOMOR_SPL, MTSD.PEKERJAAN          
		FROM MJ.MJ_T_SPL MTS
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
		INNER JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
		AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
		AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
		AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
		WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
		AND MTSD.PERSON_ID=$vPersonId
		AND MTT.ID IS NULL
		--AND MTS.MANAGER = 'Catur Susilo, Mr. Didik DCS'
		ORDER BY MTS.TANGGAL_SPL
		";
		//echo $queryJamLembur;
		$resultJamLembur1 = oci_parse($con,$queryJamLembur1);
		oci_execute($resultJamLembur1);
		while($rowJamLembur1 = oci_fetch_row($resultJamLembur1))
		{
			$vHours = 0;
			$vTotJamLembur = 0;
			$vHoursSPL = $rowJamLembur1[2];
			$vTglSPL = $rowJamLembur1[1];
			$vNoSPL = $rowJamLembur1[3];
			$vKet = $rowJamLembur1[4];
			$vHoursPenguranan = 0;
			$vTotSPL = 0;
			$vTotJamLemburTemp = 0;
			
			$queryHours = "SELECT F.STANDARD_STOP
				FROM APPS.PER_PEOPLE_F A
				, APPS.PER_ALL_ASSIGNMENTS_F B
				, APPS.HXT_ADD_ASSIGN_INFO_F C
				, APPS.HXT_ROTATION_SCHEDULES D
				, APPS.HXT_WORK_SHIFTS_FMV E
				, APPS.HXT_SHIFTS F
				WHERE A.PERSON_ID=$vPersonId AND A. EFFECTIVE_END_DATE>SYSDATE
				AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE>SYSDATE
				AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. EFFECTIVE_END_DATE>SYSDATE
				AND C.ROTATION_PLAN=D.RTP_ID AND D.START_DATE < SYSDATE 
				AND D.START_DATE=(SELECT MAX(START_DATE) FROM APPS.HXT_ROTATION_SCHEDULES WHERE START_DATE < SYSDATE)
				AND D.TWS_ID=E.TWS_ID 
				AND UPPER(E.MEANING)=(SELECT TRIM(TO_CHAR(TO_DATE('".$rowJamLembur1[1]."', 'YYYY-MM-DD'), 'DAY')) FROM DUAL)-- Hari absen
				AND E.SHT_ID=F.ID
			";
			$resultHours = oci_parse($con,$queryHours);
			oci_execute($resultHours);
			while($rowHours = oci_fetch_row($resultHours))
			{
				$vHours = $rowHours[0];
				if(strlen($vHours) == 3){
					$vHours = $vHours . "0";
				}
				if(strlen($vHoursSPL) == 3){
					$vHoursSPL = $vHoursSPL . "0";
				}
				//echo $vHours ." dan ". $vHoursSPL;
				if($vHours == $vHoursSPL){
					$vHoursPenguranan = 1;
				}
			}
			//echo $vHoursPenguranan;
			$vJamLembur = $rowJamLembur1[0]; 
			$ArrTempMasuk = explode(":", $vJamLembur);
			$vTempJamMasuk = $ArrTempMasuk[0];
			$vTempMenitMasuk = $ArrTempMasuk[1];
			$vTempJamMasuk = $vTempJamMasuk + ($vTempMenitMasuk/60) - $vHoursPenguranan;
			
			if($vTotJamLembur < $vBatasTotJamLembur){
				if($vTempJamMasuk > 0){
					$vTotJamLemburTemp = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
					if($vTotJamLemburTemp > $vBatasTotJamLembur){
						$vTotJamLemburTemp = $vBatasTotJamLembur - $vTotJamLembur;
						//$vTotUangLembur = $vTotUangLembur + ($vTotJamLemburTemp * $vUangLembur);
						$vTotJamLembur = $vTotJamLembur + $vTotJamLemburTemp;
						//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
					} else {
						//$vTotUangLembur = $vTotUangLembur + (round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
						$vTotJamLembur = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
					}
				}
			}
			$countHeader++;
			$xlsRow++;
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vFullName)
						->setCellValue('C' . $xlsRow, $vDept)
						->setCellValue('D' . $xlsRow, $vJabatan)
						->setCellValue('E' . $xlsRow, $vTglSPL)
						->setCellValue('F' . $xlsRow, $vTotJamLembur)
						->setCellValue('G' . $xlsRow, $vNoSPL)
						->setCellValue('H' . $xlsRow, $vKet);
			
		}
		
		
}
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>