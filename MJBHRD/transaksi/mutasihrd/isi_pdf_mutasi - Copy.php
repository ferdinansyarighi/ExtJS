<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

$hdid = "";
$hari="";
$bulan="";
$tahun=""; 
$queryfilter=""; 
if (isset($_GET['hdid']))
{	
	$hdid = $_GET['hdid'];
	
	$queryfilter=" WHERE ID=$hdid "; 
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
		$this->SetFont('Arial', 'B', 15);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		
		if($this->PageNo() == 1)
		{
			$this->SetFont('Arial', '', 15);
			$this->Cell(200, 8, 'PENGAJUKAN ( MUTASI/PROMOSI/DEMOSI ) KARYAWAN MERAK', 0, 0, 'C', 0);					
			$this->Ln(8);
			$this->Cell(200, 8, 'JAYA GROUP', 0, 0, 'C', 0);						
			$this->Ln(8);
			$this->Cell(200, 8, "__________________________________________________________", 0, 0, 'C', 0);			
			$this->Ln(8);
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
$pdf=new myPdf('P','mm','legal');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('P','A4','legal');				
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '',8);

$query = "SELECT MTM.ID
, MTM.NO_REQUEST
, MTM.TIPE
, PPF1.FULL_NAME PEMBUAT
, PPF.FULL_NAME KARYAWAN
, PJ.NAME DEPT_LAMA
, PP.NAME POSISI_LAMA
, HL.LOCATION_CODE LOKASI_LAMA
, MTM.GAJI_LAMA
, PJ1.NAME DEPT_BARU
, PP1.NAME POSISI_BARU
, HL1.LOCATION_CODE LOKASI_BARU
, MTM.GAJI_BARU
, MTM.KETERANGAN
, MTM.ALASAN
, MTM.DEPT_LAMA_ID
, MTM.DEPT_BARU_ID
, PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME DIREKSI
FROM MJ.MJ_T_MUTASI MTM
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTM.KARYAWAN_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.CREATED_BY AND PPF1.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.DIREKSI AND PPF2.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID 
LEFT JOIN APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
LEFT JOIN APPS.PER_POSITIONS PP1 ON PP1.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN APPS.HR_LOCATIONS HL1 ON HL1.LOCATION_ID = MTM.LOKASI_BARU_ID 
WHERE 1=1 AND MTM.ID = $hdid";
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$hd_id=$row[0];
	$xNoReq=$row[1];
	$xTipe=$row[2];
	$xPembuat=$row[3];
	$xKaryawan=$row[4];
	$xDeptLama=$row[5];
	$xPosisiLama=$row[6];
	$xLokasiLama=$row[7];
	$xGajiLama=$row[8];
	$xDept=$row[9];
	$xPosisi=$row[10];
	$xLokasi=$row[11];
	$xGaji=$row[12];
	$xKeterangan=$row[13];
	$xAlasan=$row[14];
	$xDeptLamaID=$row[15];
	$xDeptBaruID=$row[16];
	$xDireksi=$row[17];
}

$queryPem = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND (PP.NAME LIKE '%MGR%')
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
AND PAF.JOB_ID='$xDeptLamaID'";
//echo $queryPemohon;
$resultPem = oci_parse($con,$queryPem);
oci_execute($resultPem);
$rowPem = oci_fetch_row($resultPem);
$managerKaryawan = $rowPem[0];

$queryDept = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND (PP.NAME LIKE '%MGR%')
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
AND PAF.JOB_ID='$xDeptBaruID'";
//echo $queryPemohon;
$resultDept = oci_parse($con,$queryDept);
oci_execute($resultDept);
$rowDept = oci_fetch_row($resultDept);
$managerDept = $rowDept[0];

$queryHRD = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%' AND (PP.NAME LIKE '%HRD.MGR.HRD%') 
AND PAF.JOB_ID = (SELECT JOB_ID FROM APPS.PER_JOBS WHERE NAME = 'HRD.Human Resource Department')";
//echo $queryPemohon;
$resultHRD = oci_parse($con,$queryHRD);
oci_execute($resultHRD);
$rowHRD = oci_fetch_row($resultHRD);
$managerHRD = $rowHRD[0];

	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'NOMOR', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xNoReq, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'NAMA KARYAWAN', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xKaryawan, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'DEPARTEMEN', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xDeptLama, 0, 0, 'L', 0);									
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'POSISI', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xPosisiLama, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'GRADE', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, '', 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'LOKASI', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xLokasiLama, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'GAJI', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, number_format($xGajiLama, 2, ',', '.'), 0, 0, 'L', 0);							
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'DEPARTEMEN BARU', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xDept, 0, 0, 'L', 0);									
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'POSISI BARU', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xPosisi, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'GRADE BARU', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, '', 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'LOKASI BARU', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xLokasi, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'GAJI BARU', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, number_format($xGaji, 2, ',', '.'), 0, 0, 'L', 0);							
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'ALASAN', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xAlasan, 0, 0, 'L', 0);							
	$pdf->Ln(8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'KETERANGAN', 0, 0, 'L', 0);	
	$pdf->Cell(5, 10, ':', 0, 0, 'L', 0);	
	$pdf->Cell(130, 10, $xKeterangan, 0, 0, 'L', 0);							
	$pdf->Ln(20);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(40, 30, '', 1, 0, 'L', 0);	
	$pdf->Cell(40, 30, '', 1, 0, 'L', 0);	
	$pdf->Cell(40, 30, '', 1, 0, 'L', 0);	
	$pdf->Cell(40, 30, '', 1, 0, 'L', 0);							
	$pdf->Ln(1);
	$pdf->Cell(15, 5, '', 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, 'Manager Pembuat', 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, 'Manager Penerima', 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, 'Manager HRG', 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, 'Direksi', 0, 0, 'C', 0);							
	$pdf->Ln(24);
	$pdf->Cell(15, 5, '', 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, $managerKaryawan, 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, $managerDept, 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, $managerHRD, 0, 0, 'C', 0);	
	$pdf->Cell(40, 5, $xDireksi, 0, 0, 'C', 0);		
 
$pdf->SetFont('','B');
$pdf->Ln(15);

$pdf->Output('CetakFPR'. '.pdf', 'F');
$pdf->Output();
?>
