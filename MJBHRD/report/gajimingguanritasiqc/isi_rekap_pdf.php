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
	$queryfilter .= " AND TO_CHAR(TRQ.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRQ.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND TRQ.NAMA_QC = '$nama'";
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
			$this->Cell(100, 16, "Rekap Ritasi QC", 0, 0, 'L', 0);					
			$this->Ln(7);
			$this->Cell(70, 16, 'Periode '. $tgl_awal .' s/d '. $tgl_akhir , 0, 0, 'L', 0);	
			$this->Ln(16);	
			$this->Cell(10, 11, 'No. ' , 1, 0, 'C', 0);	
			$this->Cell(70, 11, 'Nama QC' , 1, 0, 'C', 0);	
			$this->Cell(40, 11, 'No Surat Jalan' , 1, 0, 'C', 0);	
			$this->Cell(20, 11, 'Notes' , 1, 0, 'C', 0);	
			$this->Cell(17, 11, 'No LHO' , 1, 0, 'C', 0);	
			$this->Cell(30, 11, 'SJ Date' , 1, 0, 'C', 0);	
			$this->Cell(80, 11, 'Customer' , 1, 0, 'C', 0);	
			$this->Cell(12, 11, 'Vol' , 1, 0, 'C', 0);	
			$this->Cell(25, 11, 'Nominal' , 1, 0, 'C', 0);	
			$this->Cell(30, 11, 'Jml Nominal' , 1, 0, 'C', 0);	
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
    , MSB.SURAT_JALAN_NO
    , TO_CHAR(MSB.TANGGAL_SJ, 'DD-MON-YYYY') || ' ' || MSB.JAM_BERANGKAT TANGGL_SJ
	, MSB.NOTES
    , REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
    , HOU.NAME PLANT
    , MSB.NAMA_CUST
    , MSB.LOKASI_PROYEK
    , MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0) VOL
    , NVL(MRS.RETURN_QTY, 0) VOL_RETUR
    , MSB.SOPIR
    , MSB.NOMOR_TRUK
    , TRQD.NOMOR_LHO
    , TRQD.NOMINAL
    , (MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0)) * TRQD.NOMINAL TOTAL
	FROM MJ.MJ_T_RITASI_QC TRQ
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRQ.NAMA_QC AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQ.ID = TRQD.MJ_T_RITASI_QC_ID
	INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRQD.SJ_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = HOU.INTERNAL_ADDRESS_LINE
	LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
	WHERE MSB.ORG_ID = 81
	AND TRQ.STATUS = 'Submit'
	--AND PPF.FULL_NAME = 'Yudis Syahrul Putra, Mr. Mukhamad YSP'
	$queryfilter
	ORDER BY HOU.NAME, MSB.SURAT_JALAN_NO
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
		$vPlantCode = $rowExcel[4];
		$vPlant = $rowExcel[5];
		$vCust = $rowExcel[6];
		$vLokasi = $rowExcel[7];
		$vVol = $rowExcel[8];
		$vRetur = $rowExcel[9];
		$vSopir = $rowExcel[10];
		$vTruk = $rowExcel[11];
		$vLHO = $rowExcel[12];
		$vNominal = $rowExcel[13];
		$vTotal = $rowExcel[14];
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
		$pdf->Cell(70, 11, $vNama , 1, 0, 'L', 0);	
		$pdf->Cell(40, 11, $vSJNo , 1, 0, 'C', 0);	
		$pdf->Cell(20, 11, $vNotes , 1, 0, 'C', 0);	
		$pdf->Cell(17, 11, $vLHO , 1, 0, 'C', 0);	
		$pdf->Cell(30, 11, $vTglSJ , 1, 0, 'C', 0);	
		$pdf->Cell(80, 11, $vCust , 1, 0, 'L', 0);	
		$pdf->Cell(12, 11, $vVol , 1, 0, 'C', 0);	
		$pdf->Cell(25, 11, number_format($vNominal, 2, ',', '.') , 1, 0, 'R', 0);	
		$pdf->Cell(30, 11, number_format($vTotal, 2, ',', '.') , 1, 0, 'R', 0);	
	}
	
//$pdf->SetFont('','B');

$pdf->Output();
?>
