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
if (isset($_GET['periodegaji']))
{	
	$periodegaji = $_GET['periodegaji'];
	$periode_start = $_GET['periode_start'];
	$periode_end = $_GET['periode_end'];
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	$company = $_GET['company'];
	$plant = $_GET['plant'];
	$dept = $_GET['dept'];
	$grade = $_GET['grade'];
	$revisiKe = $_GET['revisi'];
	
	if($company != 'All'){
		$queryfilter .= " AND PAF.ORGANIZATION_ID = $company";
	}
	if($plant != ''){
		$queryfilter .= " AND HL.LOCATION_ID = $plant";
	}
	if($dept != ''){
		$queryfilter .= " AND PJ.JOB_ID = $dept";
	}
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
		$queryfilter .= " and bulan = $bulanAngka and tahun = $tahun";
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
		$queryfilter .= " and TO_CHAR(mjgp.periode_start, 'YYYY-MM-DD') = '$periode_start' and TO_CHAR(mjgp.periode_end, 'YYYY-MM-DD') = '$periode_end'";
	}	
	
	$queryfilter .= " and mjgp.revisi = $revisiKe";
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

$queryGaji = "select DISTINCT mjgp.id, PPF.PERSON_ID, PPF.employee_number, NVL(PPF.honors, 0) id_finger, PPF.FULL_NAME
	, REGEXP_SUBSTR(pj.name, '[^.]+', 1, 2) as dept, REGEXP_SUBSTR(pps.name, '[^.]+', 1, 3) as jabatan, HL.LOCATION_code
	--, PG.name grade
	, mjgp.HARI_MASUK, mjgp.HARI_SAKIT, mjgp.HARI_CUTI, mjgp.HARI_IJIN, mjgp.HARI_ALPHA
	, mjgp.TERLAMBAT1, mjgp.TERLAMBAT2, mjgp.TERLAMBAT3, mjgp.TERLAMBAT4, mjgp.PINJAMAN, MJGP.BS
	, MJGP.ABSEN, MJGP.TELAT, MJGP.PENDING, MJGP.TOTAL_GAJI, DECODE(MJGP.TRANS_TUNAI, 0, 'TRANSFER', 'TUNAI') TRANSFER
	, HOU.NAME org
	from mjhr.mj_m_gaji_plant mjgp
		inner join APPS.PER_PEOPLE_F PPF on mjgp.person_id = ppf.person_id
		AND (TRUNC(SYSDATE) BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE) AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		inner JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		AND (TRUNC(SYSDATE) BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE) AND PAF.PRIMARY_FLAG='Y'
	inner join APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
	inner join APPS.HR_LOCATIONS HL ON PAF.location_id = hl.location_id
	INNER JOIN APPS.per_positions pps ON paf.position_id = pps.position_id
	INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
	inner join APPS.HR_ORGANIZATION_UNITS HOU on PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID  
	where mjgp.periode_gaji = '$periodegaji' 
	$queryfilter
	order by mjgp.id asc";
	//echo $queryGaji;exit;
	$resultGaji = oci_parse($conHR,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$hdid = $row[0];
		$vPersonId = $row[1];
		$vEmpNumber = $row[2];
		$vIdFinger = $row[3];
		$vFullName = str_replace("'", "''", $row[4]);
		//$vOrg = $row[4];
		$vDept = $row[5];
		$vJabatan = $row[6];
		$vLokasi = $row[7];
		$vJumMasuk = $row[8];
		$vHariSakit = $row[9];
		$vHariCuti = $row[10];
		$vHariIjin = $row[11];
		$vHariAlpha = $row[12];
		$vTerlambat1 = $row[13];
		$vTerlambat2 = $row[14];
		$vTerlambat3 = $row[15];
		$vTerlambat4 = $row[16];
		$vPotongan = $row[17];
		$vBS = $row[18];
		$absen = $row[19];
		$telat = $row[20];
		$vPending = $row[21];
		$vTotGajiTransfer = $row[22];
		$vTransTunai = $row[23];
		$vPerusahaan = $row[24];
		
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
		$trpGajiPokok = 0;
		$trpUangMakan = 0;
		
		//Query Perhitungan Element Gaji (Detail)
		$queryGajiDt = "select mjgp.person_id, egm.nama_element, egm.komponen, mjgpd.nominal from mjhr.mj_m_gaji_plant mjgp
		inner join mjhr.mj_m_gaji_plant_dt mjgpd on mjgp.id = mjgpd.hdid
		INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON mjgpd.ELEMENT_gaji = EGM.ID
		where mjgp.id = ".$hdid."
		ORDER BY EGM.NAMA_ELEMENT";
			//echo $queryGaji;
		$resultGajiDt = oci_parse($con,$queryGajiDt);
		oci_execute($resultGajiDt);
		while($rowGaji = oci_fetch_row($resultGajiDt)){
			$nominal = 0;
			//$id_element = $rowGaji[0];
			$element = $rowGaji[1];
			$komponen = $rowGaji[2];
			$value = $rowGaji[3];
			
			switch($element){
				case 'E_GAJI_MINGGUAN':
					$gajiMingguan = $value;
					break;
				case 'E_GAJI_HARIAN':
					$gajiHarian = $value;
					break;
				case 'E_LEMBUR':
					$totalUangLembur = $value;
					break;
				case 'E_TUNJANGAN_MASUK_AWAL':
					$tunMasukAwal = $value;
					break;
				case 'E_UANG_MAKAN':
					$uangMakan = $value;
					break;
				case 'E_TUNJANGAN_HARI_LIBUR':
					$tunHariLibur = $value;
					break;
				case 'E_TUNJANGAN_HARI_KERJA':
					$tunHariKerja = $value;
					break;
				case 'E_PREMI':
					$premi = $value;
					break;
				case 'E_BPJS_KESEHATAN':
					$BPJSKS = $value;
					break;
				case 'E_BPJS_KETENAGAKERJAAN':
					$BPJSTK = $value;
					break;
				case 'E_REVISI':
					if($komponen == '-'){
						$revisi = $komponen.$value;
					}else{
						$revisi = $value;
					}
					//$revisi = $komponen.$value;
					break;
				case 'E_TUNJANGAN_JABATAN':
					$tunJabatan = $value;
					break;
				case 'E_TUNJANGAN_TIDAK_ISTIRAHAT':
					$tunTidakIstirahat = 0;//$value;
					break;
			}
		}
		
		if($vJumMasuk != 0){
			$trpGajiPokok = $gajiHarian/$vJumMasuk;
			$trpUangMakan = $uangMakan/$vJumMasuk;
		}
		
		$vTotGajiPlus = $gajiMingguan + $gajiHarian + $totalUangLembur + $tunMasukAwal + $uangMakan + $tunHariLibur + $tunHariKerja + $premi + $tunJabatan + $tunTidakIstirahat;
		$vTotGajiMinus = $BPJSKS - $BPJSTK;
		$vTotGajiGross = ($gajiMingguan+$gajiHarian+$totalUangLembur+$tunMasukAwal+$uangMakan+$tunHariLibur+$tunHariKerja+$premi+$tunJabatan+$tunTidakIstirahat)-($BPJSKS-$BPJSTK);
		
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
		$pdf->Cell(30, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(70, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(50, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(10, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 90.5, '', 1, 0, 'C', 0);	
		$pdf->Ln(0.5);	
		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);	
		$pdf->Cell(30, 7.5, 'Nama', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vFullName, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Gaji Pokok', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($trpGajiPokok, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($gajiHarian, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BPJS Kesehatan', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($BPJSKS, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Plant', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vLokasi, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Tetap', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BPJS TK', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($BPJSTK, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Department', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vDept, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Kerajinan', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Koperasi/BS', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBS, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Jabatan', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vJabatan, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Jabatan', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($tunJabatan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Claim', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Premi', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($premi, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Absen', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($absen, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Hari Libur', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($tunHariLibur, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Telat', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($telat, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Masuk Awal', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($tunMasukAwal, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Revisi', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($revisi, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Kerja >12 Jam', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($tunHariKerja, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'PPH21', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Lain-lain', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Uang Makan', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($trpUangMakan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($uangMakan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Uang Transport', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format(0, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Overtime', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($totalUangLembur, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);
		//$pdf->Cell(30, 7.5, '', 1, 0, 'L', 0);	
		$pdf->Cell(150, 7.5, 'Total', 1, 0, 'C', 0);	
		$pdf->Cell(10, 7.5, '', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPlus, 2, ',', '.'), 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 1, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiMinus, 2, ',', '.'), 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiTransfer, 2, ',', '.'), 1, 0, 'R', 0);	
		$pdf->Ln(7.5);	
		//$pdf->Cell(30, 7.5, 'Ditransfer', 1, 0, 'L', 0);	
		$pdf->Cell(300, 7.5, 'Dibulatkan', 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiTransfer, 2, ',', '.'), 1, 0, 'R', 0);	
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
		$pdf->Cell(50, 7.5, number_format($revisi, 2, ',', '.'), 0, 0, 'R', 0);	
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
