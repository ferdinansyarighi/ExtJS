<?php
require('../../pdf/pdfmctable.php');
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
  $queryfilter = "";
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND PPF.PERSON_ID = '$nama'";
	}
	if($plant!=''){
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '$plant')";
		
		$queryHariIni = "SELECT  DISTINCT HOU.NAME LOC_NAME
		FROM    APPS.HR_ORGANIZATION_UNITS HOU
		WHERE   HOU.ORGANIZATION_ID = '$plant' ";
		$resultHariIni = oci_parse($con,$queryHariIni);
		oci_execute($resultHariIni);
		$rowPlant = oci_fetch_row($resultHariIni);
		$namaPlant = $rowPlant[0]; 
		$namaPlant = strtoupper($namaPlant);

		$buatPlant = " PLANT ".$namaPlant;
	}
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
		$tgl_awal = $_GET['tgl_awal'];
		$tgl_akhir = $_GET['tgl_akhir'];
		
		$this->SetFont('Arial', '',9);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
			
		if($this->PageNo() == 1)
		{
			$this->SetFont('Arial','',11);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$this->Cell(100, 16, "Rekap Gaji Ritasi Driver", 0, 0, 'L', 0);					
			$this->Ln(7);
			$this->Cell(70, 16, 'Periode '. $tgl_awal .' s/d '. $tgl_akhir , 0, 0, 'L', 0);	
			$this->Ln(16);	
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

$queryExcel = "SELECT DISTINCT PPF.PERSON_ID
, PPF.FULL_NAME
FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD ON TRD.ID = TRLD.TRANSAKSI_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRLD.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
WHERE TRLD.STATUS = 'Y'
$queryfilter
";
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
// echo $queryExcel; 
while($rowExcel = oci_fetch_row($resultExcel))
	{
		$vPerson = $rowExcel[0];
		$vNama = $rowExcel[1];
		$vSumTotal = 0;
		
// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->Cell(100, 16, "Nama Karyawan : " . $vNama, 0, 0, 'L', 0);			
		$pdf->Ln(16);	
		$pdf->SetFont('Arial','B',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->Cell(30, 15, 'Tanggal SJ', 1, 0, 'C', 0);	
		$pdf->Cell(20, 15, 'Ritasi Ke-', 1, 0, 'C', 0);				
		$pdf->Cell(50, 15, 'Nomor LHO', 1, 0, 'C', 0);					
		$pdf->Cell(30, 7.5, 'Variabel', 1, 0, 'C', 0);						
		$pdf->Cell(45, 7.5, 'Sum of', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, 'Sum of', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, 'Sum of', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, 'Sum of', 1, 0, 'C', 0);						
		$pdf->Cell(50, 15, 'Jumlah', 1, 0, 'C', 0);						
		$pdf->Ln(7.5);		
		$pdf->Cell(30, 15, '', 0, 0, 'C', 0);	
		$pdf->Cell(20, 15, '', 0, 0, 'C', 0);				
		$pdf->Cell(50, 15, '', 0, 0, 'C', 0);					
		$pdf->Cell(30, 7.5, 'Solar', 1, 0, 'C', 0);						
		$pdf->Cell(45, 7.5, 'Nominal Ritasi', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, 'Total Jam Kerja', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, 'Uang Lembur', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, 'Uang Makan', 1, 0, 'C', 0);						
		$pdf->Cell(50, 15, '', 0, 0, 'C', 0);
		$pdf->Ln(7.5);	
		
		$query = "SELECT PPF.PERSON_ID
		, PPF.FULL_NAME
		, TRLD.TGL
		, TRLD.RITASI_KE
		, TRLD.NOMOR_LHO
		, TRLD.VARIABEL
		, CASE WHEN TRLD.TIPE = 'BP' THEN SUM(TRLD.VARIABEL * TRLD.NOMINAL)
		ELSE SUM(TRLD.NOMINAL) END NOMINAL
		, NVL(TRLD.JAM_IN, '-') JAM_IN
		, NVL(TRLD.JAM_OUT, '-') JAM_OUT
		, SUM(TRLD.LEMBUR) LEMBUR
		, SUM(TRLD.UANG_MAKAN) UANG_MAKAN
		, CASE WHEN TRLD.TIPE = 'BP' THEN SUM(TRLD.VARIABEL * TRLD.NOMINAL)
		ELSE SUM(TRLD.NOMINAL) END + SUM(TRLD.LEMBUR) + SUM(TRLD.UANG_MAKAN) TOTAL
		FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
		INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD ON TRD.ID = TRLD.TRANSAKSI_ID
		LEFT JOIN APPS.HZ_PARTIES HP ON HP.PARTY_ID = TRLD.CUSTOMER_ID
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRLD.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
		WHERE TRLD.STATUS = 'Y' AND PPF.PERSON_ID = $vPerson
		$queryfilter
		GROUP BY PPF.FULL_NAME, TRLD.NOMINAL, PPF.PERSON_ID, TRLD.TGL, TRLD.RITASI_KE, TRLD.NOMOR_LHO, TRLD.VARIABEL
		, TRLD.TIPE, TRLD.JAM_IN, TRLD.JAM_OUT
		ORDER BY PPF.FULL_NAME, TRLD.TGL, TRLD.NOMOR_LHO
		";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		$count = 0;
		while($row = oci_fetch_row($result))
		{
			if($row[7] != '-'){
				$jamInSplit = explode(":",$row[7]);
				$jamOutSplit = explode(":",$row[8]);
				if($jamOutSplit[1] >= $jamInSplit[1]){
					$totalMenit = $jamOutSplit[1] - $jamInSplit[1];
					$totalJam = $jamOutSplit[0] - $jamInSplit[0];
				} else {
					$totalMenit = $jamOutSplit[1] - $jamInSplit[1] + 60;
					$totalJam = $jamOutSplit[0] - $jamInSplit[0] - 1;
				}
			} else {
				$totalJam = 0;
				$totalMenit = 0;
			}


			$jamTotal = $totalJam + ($totalMenit / 60);
			$jamTotal = round($jamTotal, 2);
			
			$xNama=$row[1];
			$xTgl=$row[2];
			$xRitasi=$row[3];
			$xLho=$row[4];
			$xVariabel=$row[5];
			$xNominal=$row[6];
			$xJam=$jamTotal;
			$xLembur=$row[9];
			$xUM=$row[10];
			$xTotal=$row[11];
			$vSumTotal = $vSumTotal + $xTotal;
			
			$pdf->SetFont('Arial','',11);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.3);
			$pdf->Cell(30, 7.5, $xTgl, 1, 0, 'C', 0);	
			$pdf->Cell(20, 7.5, $xRitasi, 1, 0, 'C', 0);				
			$pdf->Cell(50, 7.5, $xLho, 1, 0, 'C', 0);					
			$pdf->Cell(30, 7.5, $xVariabel, 1, 0, 'C', 0);						
			$pdf->Cell(45, 7.5, number_format($xNominal, 2, ',', '.'), 1, 0, 'R', 0);						
			$pdf->Cell(35, 7.5, $xJam, 1, 0, 'C', 0);						
			$pdf->Cell(35, 7.5, number_format($xLembur, 2, ',', '.'), 1, 0, 'R', 0);						
			$pdf->Cell(35, 7.5, number_format($xUM, 2, ',', '.'), 1, 0, 'R', 0);						
			$pdf->Cell(50, 7.5, number_format($xTotal, 2, ',', '.'), 1, 0, 'R', 0);		
			$pdf->Ln(7.5);	
		}	
		$pdf->Cell(30, 7.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(20, 7.5, '', 1, 0, 'C', 0);				
		$pdf->Cell(50, 7.5, '', 1, 0, 'C', 0);					
		$pdf->Cell(30, 7.5, '', 1, 0, 'C', 0);						
		$pdf->Cell(45, 7.5, '', 1, 0, 'R', 0);						
		$pdf->Cell(35, 7.5, '', 1, 0, 'C', 0);						
		$pdf->Cell(35, 7.5, '', 1, 0, 'R', 0);						
		$pdf->Cell(35, 7.5, 'Total', 1, 0, 'R', 0);						
		$pdf->Cell(50, 7.5, number_format($vSumTotal, 2, ',', '.'), 1, 0, 'R', 0);
		$pdf->Ln(15);	
	}
	
//$pdf->SetFont('','B');

$pdf->Output();
?>
