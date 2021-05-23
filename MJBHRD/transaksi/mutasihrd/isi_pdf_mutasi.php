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
		include '../../main/koneksi3.php';
		$hdid = $_GET['hdid'];
		$query = "SELECT MTM.NO_REQUEST
		FROM MJ.MJ_T_MUTASI MTM
		WHERE 1=1 AND MTM.ID = $hdid";
		$result = oci_parse($con,$query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$xNoReq=$row[0];
		}
		$this->SetFont('Arial', 'B', 15);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		
		if($this->PageNo() == 1)
		{
			$this->SetFont('Arial', 'U', 15);
			$this->Cell(200, 8, 'SURAT MUTASI', 0, 0, 'C', 0);					
			$this->Ln(6);
			$this->SetFont('Arial', '', 12);
			$this->Cell(200, 8, $xNoReq, 0, 0, 'C', 0);						
			$this->Ln(15);
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
$pdf->SetFont('Arial', '', 8);

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
, TO_CHAR(MTM.TGL_EFFECTIVE, 'DD') || ' ' || TRIM(TO_CHAR(MTM.TGL_EFFECTIVE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || TO_CHAR(MTM.TGL_EFFECTIVE, 'YYYY') TGL
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
	$xTglEfektif=$row[18];
}

$queryTgl = "SELECT TO_CHAR(SYSDATE, 'DD') || ' ' || 
TRIM(TO_CHAR(SYSDATE, 'MONTH','nls_date_language = INDONESIAN')) || ' ' || 
TO_CHAR(SYSDATE, 'YYYY') TGL 
FROM dual";
//echo $queryPemohon;
$resultTgl = oci_parse($con,$queryTgl);
oci_execute($resultTgl);
$rowTgl = oci_fetch_row($resultTgl);
$tglskr = $rowTgl[0];

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

	$pdf->Cell(15, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 5, 'Menunjuk :', 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '1.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Pentingnya personil yang capabel di ' . $xDept . ' Plan ' . $xLokasi, 0, 0, 'L', 0);						
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '2.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Adanya kebutuhan karyawan di PT. Merak Jaya Beton', 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 5, 'Menimbang :', 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '1.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Peraturan Perusahaan di lingkungan PT. Merak Jaya Group', 0, 0, 'L', 0);						
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '2.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Kelancaran  operasional di bagian Staff Logistik Plan ' . $xLokasiLama, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 5, 'Memutuskan :', 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '1.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Memutusi ' . $xKaryawan . ' dari ' . $xDeptLama . ' Plan ' . $xLokasiLama . ' menjadi ', 0, 0, 'L', 0);						
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, $xDept . ' Plan ' . $xLokasi . ', bertanggung jawab kepada ' . $managerDept, 0, 0, 'L', 0);						
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'sebagai Manager Area.', 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '2.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Gaji mengikuti kebijakan Perusahaan.', 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '3.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Segera melakukan serah terima pekerjaan sebelumnya kepada pejabat yang ditunjuk oleh', 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Atasan Langsung Saudara.', 0, 0, 'L', 0);								
	$pdf->Ln(5);
	$pdf->Cell(20, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(5, 5, '4.', 0, 0, 'L', 0);	
	$pdf->Cell(130, 5, 'Surat Mutasi ini berlaku effktif mulai ' . $xTglEfektif, 0, 0, 'L', 0);								
	$pdf->Ln(8);
	$pdf->Cell(15, 5, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 5, 'Demikian Surat Mutasi ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.', 0, 0, 'L', 0);
	$pdf->Ln(13);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'Surabaya, ' . $tglskr, 0, 0, 'L', 0);							
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'PT. MERAK JAYA BETON', 0, 0, 'L', 0);					
	$pdf->Ln(25);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, $managerHRD, 0, 0, 'L', 0);						
	$pdf->Ln(5);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(15, 10, '', 0, 0, 'L', 0);	
	$pdf->Cell(35, 10, 'Manager HRD', 0, 0, 'L', 0);	
 
$pdf->SetFont('','B');
$pdf->Ln(15);

$pdf->Output('CetakFPR'. '.pdf', 'F');
$pdf->Output();
?>
