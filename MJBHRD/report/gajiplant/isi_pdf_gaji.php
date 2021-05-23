<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

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
if (isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	$revisi = trim($_GET['revisi']);
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	$plant = $_GET['plant'];
	$dept = $_GET['dept'];
	
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
	if($plant!=''){
		$queryfilter .= " AND HL.LOCATION_CODE='$plant'";
	}
	if($dept!=''){
		$queryfilter .= " AND upper(REGEXP_SUBSTR(PJ.name, '[^.]+', 1, 2))=upper('$dept')";
	}
	
	$queryPeriode = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') PERIODE1 
	, TO_CHAR(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 'YYYY-MM-DD') PERIODE2
	FROM DUAL ";
	//echo $queryJumHari;
	$resultPeriode = oci_parse($conHR,$queryPeriode);
	oci_execute($resultPeriode);
	$rowPeriode = oci_fetch_row($resultPeriode);
	$vPeriode1 = $rowPeriode[0]; 
	$vPeriode2 = $rowPeriode[1]; 
}

$vHariAktif=0;
$queryAktif = "SELECT ((SELECT COUNT(*) FROM 
(SELECT LEVEL AS DNUM FROM DUAL
CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - TO_DATE('$vPeriode1', 'YYYY-MM-DD')) - LEVEL >= 0) S
WHERE TO_CHAR(TO_DATE('$vPeriode1', 'YYYY-MM-DD') + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
(SELECT COUNT(-1)
FROM APPS.HXT_HOLIDAY_DAYS A
, APPS.HXT_HOLIDAY_CALENDARS B
WHERE A.HCL_ID=B.ID 
AND B.EFFECTIVE_END_DATE>SYSDATE 
AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2') + 1) TES
FROM DUAL";
//echo $queryJumHari;
$resultAktif = oci_parse($con,$queryAktif);
oci_execute($resultAktif);
while($rowAktif = oci_fetch_row($resultAktif))
{
	$vHariAktif = $rowAktif[0]; 
}
	
class myPdf extends pdfmctable
{
	var $title;
	var $numtitle;
	
	function setTitle($title, $numtitle){
		$this->title	= $title;
		$this->numtitle	= $numtitle;
	}
		
	function Header()
	{
		$this->SetFont('Arial', '',9);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
			
		if($this->PageNo() == 1)
		{
			
		}	

	}			

	function Footer()
	{
		$tglskr=date('d F Y'); 
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',8);
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	    $this->Cell(0,10,'Tanggal Cetak : '.$tglskr,0,0,'R');
	}
}		

// Out of the class
// Legal paper size: 355.6 mm x 215.9 mm
$pdf=new myPdf('L','mm','legal');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('L','A4','legal');				
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '',9);

$queryGaji = "SELECT DISTINCT MMG.PERSON_ID
	, PPF.EMPLOYEE_NUMBER
	, MMG.FINGER_ID
	, PPF.FULL_NAME
	,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPARTMENT
	,REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) JABATAN
	, MMG.LOKASI
	, TO_CHAR(PPF.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
	, NVL(TO_CHAR(PPF.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '4712-12-31') AS TGL_RESIGN
	, MMG.HARI_MASUK
	, MMG.HARI_SAKIT
	, MMG.HARI_CUTI
	, MMG.HARI_IJIN
	, MMG.HARI_ALPHA
	, MMG.TERLAMBAT1
	, MMG.TERLAMBAT2
	, MMG.TERLAMBAT3
	, MMG.TERLAMBAT4
	, MMG.GAJI_POKOK
	, MMG.TUNJ_JABATAN
	, MMG.TUNJ_LOKASI
	, MMG.TUNJ_GRADE
	, MMG.UANG_MASUK
	, MMG.UANG_TRANSPORT
	, MMG.PREMI_HADIR
	, MMG.PPH21
	, MMG.LEMBUR
	, MMG.BPJS_KESEHATAN
	, MMG.BPJS_TK
	, MMG.PINJAMAN
	, MMG.BS
	, MMG.ABSEN
	, MMG.TELAT
	, MMG.TOTAL_GAJIAN
	, MMG.REVISI_GAJI
	, MMG.TOTAL_DITRANSFER
	, TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) AS NO_REKENING
	, TRIM(REGEXP_SUBSTR(PPF.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AS ATAS_NAMA
	, UPPER(TL.DESCRIPTION) PERUSAHAAN
	FROM MJHR.MJ_M_GAJI MMG
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
	LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	LEFT JOIN APPS.FND_FLEX_VALUES_TL TL ON REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING
	LEFT JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
	LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
	WHERE REVISI=$revisi AND BULAN='$bulan' AND TAHUN='$tahun'
	and PAF.primary_flag = 'Y'
	--AND REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) = 'Information Technology'
	$queryfilter
	--AND PPF.PERSON_ID = 19926
	ORDER BY MMG.PERSON_ID";
	//echo $queryGaji;exit;
	$resultGaji = oci_parse($conHR,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vPersonId = $row[0];
		$vEmpNumber = $row[1];
		$vIdFinger = $row[2];
		$vFullName = $row[3];
		$vDept = $row[4];
		$vJabatan = $row[5];
		$vLokasi = $row[6];
		$hireDate = $row[7];
		$vTglResign = $row[8];
		$vJumMasuk = $row[9];
		$vHariSakit = $row[10];
		$vHariCuti = $row[11]; 
		$vHariIjin = $row[12]; 
		$vHariAlpha = $row[13]; 
		$vTerlambat1 = $row[14]; 
		$vTerlambat2 = $row[15];
		$vTerlambat3 = $row[16];
		$vTerlambat4 = $row[17];
		$vTotGajiPokok = $row[18];
		$vTunJab = $row[19];
		$vTunLok = $row[20];
		$vTunGrade = $row[21];
		$vTotUangMakan = $row[22];
		$vTotUangTrans = $row[23];
		$vTotTunKer = $row[24];
		$vPPH21 = $row[25];
		$vTotUangLembur = $row[26];
		$vBPJSKS = $row[27];
		$vBPJSTK = $row[28];
		$vPotongan = $row[29];
		$vBS = $row[30];
		$vTotUangAbsen = $row[31];
		$vTotUangTerlambat = $row[32];
		$vTotGajiSkr = $row[33];
		$vRevisiGaji = $row[34];
		$vTotGajiTransfer = $row[35];
		$vNoReg = $row[36];
		$vAtasNama = $row[37];
		$vPerusahaan = $row[38];
		$vHariMasuk = 0;
		$vHariMasukStandart = 0;
		$vUangMakan = 0;
		
		$queryNew = "SELECT CASE WHEN TO_DATE('$hireDate', 'YYYY-MM-DD') BETWEEN ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) AND TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') 
		THEN 'YES' ELSE 'NO' END FROM DUAL";
		//echo $queryJumHari;
		$resultNew = oci_parse($conHR,$queryNew);
		oci_execute($resultNew);
		$rowNew = oci_fetch_row($resultNew);
		$vNew = $rowNew[0]; 
		//echo $vNew;
		if($vNew=='YES'){
			$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') - (TO_DATE('$hireDate', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
			WHERE TO_CHAR((TO_DATE('$hireDate', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$tahun-$bulanAngka-21')) TES
			FROM DUAL";
			//echo $queryJumHari;
			$resultJumHari = oci_parse($conHR,$queryJumHari);
			oci_execute($resultJumHari);
			while($rowJumHari = oci_fetch_row($resultJumHari))
			{
				$vHariMasuk = $rowJumHari[0]; 
			}
			
			$vHariMasukStandart = $vHariMasuk;
			$vTotGajiPokokTRP = $vTotGajiPokok / $vHariMasuk;
		} else {
			$queryNew = "SELECT CASE WHEN TO_DATE('$vTglResign', 'YYYY-MM-DD') BETWEEN ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) AND TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') 
			THEN 'YES' ELSE 'NO' END FROM DUAL";
			//echo $queryJumHari;
			$resultNew = oci_parse($conHR,$queryNew);
			oci_execute($resultNew);
			$rowNew = oci_fetch_row($resultNew);
			$vNew = $rowNew[0]; 
			
			//echo $vNew;
			if($vNew=='YES'){
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
				AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD'))) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($conHR,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
				//echo $vHariMasuk . " dan " . $vTotGajiPokok;
				//echo $vFullName .", ";
				//echo $vHariMasuk;
				$vHariMasukStandart = $vHariMasuk;
				$vTotGajiPokokTRP = $vTotGajiPokok / $vHariMasuk;
			} else {
				$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
				(SELECT LEVEL AS DNUM FROM DUAL
				CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - TO_DATE('$vPeriode1', 'YYYY-MM-DD')) - LEVEL >= 0) S
				WHERE TO_CHAR(TO_DATE('$vPeriode1', 'YYYY-MM-DD') + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) + 1 -
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2')) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($conHR,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
				
				$vHariMasuk = 25;
				$vHariMasukStandart = 25;
				$vTotGajiPokokTRP = $vTotGajiPokok / $vHariMasukStandart;
			}
		}
		if ($vJumMasuk >= 1){
			$vUangMakan = $vTotUangMakan / $vJumMasuk;
			$vUangTrans = $vTotUangTrans / $vJumMasuk;
		} else {
			$vUangMakan = 0;
			$vUangTrans = 0;
		}
		
		
		$vTotGajiPlus = $vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur;
		$vTotGajiMinus = $vPotongan + $vBS + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK;
		//$vTotGajiSkr = ($vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur) - ($vPotongan + $vBS + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK);
		//$vTotGajiTransfer = $vTotGajiSkr + $vRevisiGaji;
		$vTotGajiGross = 0;
		$vTunKer = 0; 
		$queryTunker = "SELECT nvl(tk.screen_entry_value,0) tunj_ker
		from    APPS.per_people_f p, APPS.PER_ALL_ASSIGNMENTS_F a,(select  peef.assignment_id
				,peef.element_type_id
				,pet.element_name
				,pivf.name
				,peevf.screen_entry_value
		from    APPS.pay_element_entries_f peef
				,APPS.paybv_element_type pet
				,APPS.pay_element_entry_values_f peevf
				,APPS.pay_input_values_f pivf
		where   peef.element_type_id=pet.element_type_id
		AND     peevf.element_entry_id = peef.element_entry_id
		and     pivf.input_value_id=peevf.input_value_id
		and     pivf.name<>'Pay Value'
		and     pet.element_name='E_Tunjangan_Kerajinan'
		and     pet.processing_type='Recurring'
		and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
		order by peef.assignment_id)tk
		WHERE p.person_id=a.person_id
		and sysdate between p.effective_start_date and p.effective_end_date
		and (sysdate-5) between nvl(a.effective_start_date,sysdate) and nvl(a.effective_end_date,sysdate)
		and     lower(p.full_name) not like '%salah%'
		and     lower(p.last_name) not like '%trial%'
		and     tk.assignment_id(+)=a.assignment_id
		AND a.PEOPLE_GROUP_ID <> 3061
		AND p.person_id = $vPersonId
		";
		//echo $queryTunker;
		$resultTunker = oci_parse($conHR,$queryTunker);
		oci_execute($resultTunker);
		while($rowTunker = oci_fetch_row($resultTunker))
		{
			$vTunKer = $rowTunker[0]; 
		}
		//echo $vTunKer;
		$vTotGajiGross = ($vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + ($vUangMakan * 25) + ($vUangTrans * 25) + $vTunKer) - ($vBPJSKS + $vBPJSTK);
		
// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','U');
		$pdf->Cell(335, 11, $vPerusahaan, 0, 0, 'C', 0);					
		$pdf->Ln(1);
		$pdf->Cell(70, 16, 'Slip Gaji Karyawan Periode '. $vPeriode1 .' s/d '. $vPeriode2 , 0, 0, 'L', 0);	
		$pdf->Cell(198, 16, 'Readymix & Precast Concrete Product', 0, 0, 'C', 0);	
		$pdf->Ln(13);	
		$pdf->SetFont('Arial','B',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->Cell(100, 15, 'Data Karyawan', 1, 0, 'C', 0);	
		$pdf->Cell(130, 7.5, 'Penghasilan', 1, 0, 'C', 0);				
		$pdf->Cell(70, 7.5, 'Potongan', 1, 0, 'C', 0);					
		$pdf->Cell(35, 7.5, 'Total', 1, 0, 'C', 0);						
		$pdf->Ln(7.5);		
		$pdf->Cell(100, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(50, 7.5, 'Uraian', 1, 0, 'C', 0);	
		$pdf->Cell(10, 7.5, 'Hr', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Trp', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Rp', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Uraian', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Rp', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Gaji', 1, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(70, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(50, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(10, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Ln(0.5);	
		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);	
		$pdf->Cell(30, 7.5, 'Nama', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vFullName, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Gaji Pokok', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vHariMasukStandart, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPokokTRP, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPokok, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'PPH21', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '0', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'NIK', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vEmpNumber, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Jab', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTunJab, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BPJS Kesehatan', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBPJSKS, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'No. Face Attd', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vIdFinger, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Lokasi', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTunLok, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BPJS TK', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBPJSTK, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Department', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vDept, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Grade', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTunGrade, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Pinjaman', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vPotongan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Jabatan', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vJabatan, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Uang Makan', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($vUangMakan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangMakan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BS', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBS, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Location / Plant', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vLokasi, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Uang Transport', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($vUangTrans, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangTrans, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Absen', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangAbsen, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Hari Aktif', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vHariAktif, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Premi Hadir', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotTunKer, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Telat', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangTerlambat, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Gaji Gross', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, number_format($vTotGajiGross, 2, ',', '.'), 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Lembur', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangLembur, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Lain-lain', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 1, 0, 'L', 0);	
		$pdf->Cell(120, 7.5, 'Total', 1, 0, 'C', 0);	
		$pdf->Cell(10, 7.5, '', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPlus, 2, ',', '.'), 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 1, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiMinus, 2, ',', '.'), 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiSkr, 2, ',', '.'), 1, 0, 'R', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Ditransfer', 1, 0, 'L', 0);	
		$pdf->Cell(270, 7.5, 'No. Rek : ' . $vNoReg . " - " . $vAtasNama, 1, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiSkr, 2, ',', '.'), 1, 0, 'R', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(40, 7.5, 'Surabaya, ', 0, 0, 'R', 0);	
		$pdf->Cell(40, 7.5, $tglskrdipakai, 0, 0, 'R', 0);	
		$pdf->Ln(2);	
		$pdf->Cell(150, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(100, 7.5, 'Catatan :', 0, 0, 'L', 0);	
		$pdf->Ln(1);	
		$pdf->Cell(150, 33, '', 0, 0, 'C', 0);	
		$pdf->Cell(170, 33, '', 1, 0, 'L', 0);
		$pdf->Ln(5.5);	
		$pdf->Cell(20, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(40, 7.5, 'Bagian Personalia', 1, 0, 'C', 0);	
		$pdf->Cell(40, 7.5, 'Karyawan :', 1, 0, 'C', 0);			
		$pdf->Ln(7.5);	
		$pdf->Cell(20, 20, '', 0, 0, 'C', 0);	
		$pdf->Cell(40, 20, '', 1, 0, 'C', 0);	
		$pdf->Cell(40, 20, '', 1, 0, 'C', 0);	
		$pdf->Ln(2);	
		$pdf->Cell(150, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(120, 7.5, 'Revisi Gaji Bulan Lalu : ', 0, 0, 'L', 0);
		$pdf->Cell(50, 7.5, number_format($vRevisiGaji, 2, ',', '.'), 0, 0, 'R', 0);	
		$pdf->Ln(2);
		$pdf->SetFont('Arial','B',16);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);		
		$pdf->Cell(150, 15, '', 0, 0, 'C', 0);	
		$pdf->Cell(120, 15, 'Total ditransfer : ', 0, 0, 'L', 0);	
		$pdf->Cell(50, 15, number_format($vTotGajiTransfer, 2, ',', '.'), 0, 0, 'R', 0);
		$pdf->Ln(100);
	}
	
//$pdf->SetFont('','B');

$pdf->Output();
?>
