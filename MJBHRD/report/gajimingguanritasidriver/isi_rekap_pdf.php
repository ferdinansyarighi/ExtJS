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
			$this->SetFont('Arial','B',11);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->Cell(100, 16, "Rekap Ritasi Driver", 0, 0, 'L', 0);					
			$this->Ln(7);
			$this->Cell(70, 16, 'Periode '. $tgl_awal .' s/d '. $tgl_akhir , 0, 0, 'L', 0);	
			$this->Ln(16);	
			$this->Cell(10, 11, 'No. ' , 1, 0, 'C', 0);	
			$this->Cell(80, 11, 'Nama Driver' , 1, 0, 'C', 0);	
			$this->Cell(50, 11, 'No Surat Jalan' , 1, 0, 'C', 0);	
			$this->Cell(30, 11, 'Notes' , 1, 0, 'C', 0);	
			$this->Cell(30, 11, 'No LHO' , 1, 0, 'C', 0);	
			$this->Cell(20, 11, 'Variabel' , 1, 0, 'C', 0);	
			$this->Cell(20, 11, 'Ritasi Ke-' , 1, 0, 'C', 0);	
			$this->Cell(30, 11, 'Nominal' , 1, 0, 'C', 0);	
			$this->Cell(40, 11, 'Jml Nominal' , 1, 0, 'C', 0);	
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

$queryExcel = "SELECT DISTINCT PPF.FULL_NAME
, NVL(MSB.SURAT_JALAN_NO, 'STANDBY') SURAT_JALAN_NO
, TO_CHAR(NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY), 'DD-MON-YYYY') TANGGL_SJ
, MSB.NOTES
, HOU.NAME PLANT
, MSB.NAMA_CUST
, MSB.LOKASI_PROYEK
, MSB.VOLUME_KIRIM
, NVL(MRS.RETURN_QTY, 0) VOL_RETUR
, MSB.SOPIR
, MSB.NOMOR_TRUK
, REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
, TRDD.NOMINAL
, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN ROUND((TRDD.NOMINAL * TRDD.VARIABEL), 2)
	ELSE ROUND(TRDD.NOMINAL, 2) END TOTAL
, TRDD.NOMOR_LHO
, TRDD.KM_AWAL
, TRDD.KM_AKHIR
, TRDD.SOLAR
, TRDD.VARIABEL
, TRDD.RITASI_KE
FROM MJ.MJ_T_RITASI_DRIVER TRD
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRD.NAMA_DRIVER AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
LEFT JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID AND MSB.ORG_ID = 81
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = HOU.INTERNAL_ADDRESS_LINE
LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
WHERE TRD.STATUS = 'Submit'
$queryfilter
ORDER BY HOU.NAME, SURAT_JALAN_NO
";
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
// echo $queryExcel; 
$noTran = 0;
while($rowExcel = oci_fetch_row($resultExcel))
	{
		$vNama = $rowExcel[0];
		$vSJNo = $rowExcel[1];
		$vTglSJ = $rowExcel[2];
		$vNotes = $rowExcel[3];
		$vPlant = $rowExcel[4];
		$vCust = $rowExcel[5];
		$vLokasi = $rowExcel[6];
		$vVol = $rowExcel[7];
		$vRetur = $rowExcel[8];
		$vSopir = $rowExcel[9];
		$vTruk = $rowExcel[10];
		$vPlantCode = $rowExcel[11];
		$vNominal = $rowExcel[12];
		$vTotal = $rowExcel[13];
		$vLHO = $rowExcel[14];
		$vKMAwal = $rowExcel[15];
		$vKMAkhir = $rowExcel[16];
		$vSolar = $rowExcel[17];
		$vVariabel = $rowExcel[18];
		$vRitasi = $rowExcel[19];
		$noTran++;
		
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B');
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->Ln(11);	
		$pdf->Cell(10, 11, $noTran , 1, 0, 'C', 0);	
		$pdf->Cell(80, 11, $vNama , 1, 0, 'L', 0);	
		$pdf->Cell(50, 11, $vSJNo , 1, 0, 'C', 0);	
		$pdf->Cell(30, 11, $vNotes , 1, 0, 'C', 0);	
		$pdf->Cell(30, 11, $vLHO , 1, 0, 'C', 0);	
		$pdf->Cell(20, 11, $vVariabel , 1, 0, 'C', 0);	
		$pdf->Cell(20, 11, $vRitasi , 1, 0, 'C', 0);	
		$pdf->Cell(30, 11, number_format($vNominal, 2, ',', '.') , 1, 0, 'R', 0);	
		$pdf->Cell(40, 11, number_format($vTotal, 2, ',', '.'), 1, 0, 'R', 0);	
	}
	
//$pdf->SetFont('','B');

$pdf->Output();
?>
