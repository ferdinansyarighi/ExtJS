<?php
require('pdf/pdfmctable.php');

include '../../main/koneksi.php';
include('barcode128.php');

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
		$this->SetFont('Arial', '',20);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$hdid = $_GET['hdid'];
		
		$query = "SELECT DISTINCT PPR.ID AS HD_ID
		, PPR.NO_REQUEST AS DATA_NOREQ
		, PPR.CREATED_BY AS DATA_PEMBUAT
		, PPF.FULL_NAME AS DATA_NAMAKARYAWAN
		, PJ.NAME AS DATA_DEPT
		, PP.NAME AS DATA_POS
		, PL.LOCATION_CODE AS DATA_LOKASI
		, PPR.AKTIF AS DATA_STATUS
		, PPR.ALASAN AS ALASAN
		, PPR.STATUS_REQUEST AS DATA_STATUSREQUEST
		FROM MJ.MJ_PERMINTAAN_REKENING PPR
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPR.EMP_ID = PPF.PERSON_ID
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID and PAF.PRIMARY_FLAG = 'Y' and PAF.effective_end_date > sysdate
		INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
		INNER JOIN APPS.HR_LOCATIONS PL ON PL.LOCATION_ID=PAF.LOCATION_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID  
		WHERE 1=1 
		AND PPR.STATUS_REQUEST = '0'
		AND PPR.ID = $hdid";
		//AND STATUS_DOK <> 'Approved' $queryfilter";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);

		while($row = oci_fetch_row($result))
		{
			$hd_id=$row[0];
			$noreq=$row[1];
			$pembuat=$row[2];
			$namakaryawan=$row[3];
			$departemen=$row[4];
			$posisi=$row[5];
			$lokasi=$row[6];
			$status=$row[7];
			$alasan=$row[8];
			$statreq=$row[9];
		}
		if($this->PageNo() == 1)
		{
			//$this->Cell(100, 8, http://192.168.0.40/MJBPP/images/header.jpg, 1, 0, 'C', 0);	
			$this->Cell(200, 8, 'Form Permintaan Rekening', 0, 0, 'C', 0);					
			$this->Ln(8);
			$this->SetFont('Arial', '',12);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);	
			$this->SetFont('Arial', '',20);
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0);			
			$this->Cell(190, 8, "______________________________________________", 0, 0, 'C', 0);			
			$this->Ln(12);
			
			$this->SetFont('Arial','',11);
			//$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->Ln(5);
			//$this->SetFont('','B');
			$this->Cell(37,10,'Nomor',0,0,'L',1);
			$this->Cell(10,10,':',0,0,'C',1);
			$this->Cell(45,10,$noreq,0,0,'C',1);
			$this->Ln(10);
			$this->Cell(37,10,'Nama Karyawan',0,0,'L',1);
			$this->Cell(10,10,':',0,0,'C',1);
			$this->Cell(80,10,$namakaryawan,0,0,'L',1);
			$this->Ln(10);			
			$this->Cell(37,10,'Departemen',0,0,'L',1);
			$this->Cell(10,10,':',0,0,'C',1);
			$this->Cell(80,10,$departemen,0,0,'L',1);
			$this->Ln(10);			
			$this->Cell(37,10,'Posisi',0,0,'L',1);
			$this->Cell(10,10,':',0,0,'C',1);
			$this->Cell(45,10,$posisi,0,0,'L',1);
			$this->Ln(10);			
			$this->Cell(37,10,'Lokasi',0,0,'L',1);
			$this->Cell(10,10,':',0,0,'C',1);
			$this->Cell(45,10,$lokasi,0,0,'L',1);
			$this->Ln(10);			
			$this->Cell(37,10,'Alasan',0,0,'L',1);
			$this->Cell(10,10,':',0,0,'C',1);
			$this->Cell(150,10,$alasan,0,0,'L',1);
			$this->Ln(10);
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
$pdf->SetFont('Arial', '',11);
/*
	$countibase = 0;
	$query = "SELECT INVENTORY_ITEM_ID, KODE_ITEM, NAMA_ITEM, KETERANGAN_DETAIL, MERK, KATEGORI, SATUAN, QUANTITY, TO_CHAR(TGL_KEBUTUHAN, 'DD-MM-YYYY'), KETERANGAN FROM MJ.MJ_T_PP_DETAIL $queryfilter ORDER BY ID";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$KodeItem = str_replace("*", "'", $row[1]);
		$NamaItem = str_replace("*", "'", $row[2]);
		$KetDet = str_replace("*", "'", $row[3]);
		$countibase++;
		$pdf->SetWidths(array(10, 45, 60, 35, 30, 30, 30, 15, 30, 50));
		$pdf->SetAligns(array('C','L', 'L', 'L', 'L', 'C', 'C', 'R', 'C', 'L'));
		$pdf->Row(array(
			$countibase, $KodeItem, $NamaItem, $KetDet, $row[4],
			$row[5], $row[6], $row[7], $row[8], $row[9]), 0
		);
	}
	*/
	
	/* for ($i=0; $countibase < 10; $countibase++){
		$pdf->SetWidths(array(10, 45, 60, 35, 30, 30, 30, 15, 30, 50));
		$pdf->SetAligns(array('C','L', 'L', 'L', 'L', 'C', 'C', 'R', 'C', 'L'));
		$pdf->Row(array(
			'', '', '','', '',
			'', '', '', '', ''), 0
		);
	} */
$query = "SELECT DISTINCT PPR.ID AS HD_ID
		, PPR.NO_REQUEST AS DATA_NOREQ
		, PPR.CREATED_BY AS DATA_PEMBUAT
		, PPF.FULL_NAME AS DATA_NAMAKARYAWAN
		, PJ.NAME AS DATA_DEPT
		, PP.NAME AS DATA_POS
		, PL.LOCATION_CODE AS DATA_LOKASI
		, PPR.AKTIF AS DATA_STATUS
		, PPR.ALASAN AS ALASAN
		, PPR.STATUS_REQUEST AS DATA_STATUSREQUEST
		FROM MJ.MJ_PERMINTAAN_REKENING PPR
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPR.EMP_ID = PPF.PERSON_ID
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID and PAF.PRIMARY_FLAG = 'Y' and PAF.effective_end_date > sysdate
		INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
		INNER JOIN APPS.HR_LOCATIONS PL ON PL.LOCATION_ID=PAF.LOCATION_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID  
		WHERE 1=1 
		AND PPR.STATUS_REQUEST = '0'
		AND PPR.ID = $hdid";
		//AND STATUS_DOK <> 'Approved' $queryfilter";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);

		while($row = oci_fetch_row($result))
		{
			$hd_id=$row[0];
			$noreq=$row[1];
			$pembuat=$row[2];
			$namakaryawan=$row[3];
			$departemen=$row[4];
			$posisi=$row[5];
			$lokasi=$row[6];
			$status=$row[7];
			$alasan=$row[8];
			$statreq=$row[9];
		}

$queryPemohon = "SELECT PJ.JOB_ID 
    FROM APPS.PER_PEOPLE_F PPF
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
    INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
    WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME = '$namakaryawan'";
$resultPemohon = oci_parse($con,$queryPemohon);
oci_execute($resultPemohon);
$rowPemohon = oci_fetch_row($resultPemohon);
$dataKaryawan = $rowPemohon[0];

$queryPem = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND (PP.NAME LIKE '%MGR%')
AND PAF.JOB_ID='$dataKaryawan'";
//echo $queryPemohon;
$resultPem = oci_parse($con,$queryPem);
oci_execute($resultPem);
$rowPem = oci_fetch_row($resultPem);
$managerKaryawan = $rowPem[0];

$managerHRD = "SELECT DISTINCT PPF.FIRST_NAME || ' ' || PPF.LAST_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND (PP.NAME LIKE '%HRD.MGR.HRD%') 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
AND PAF.JOB_ID = '26066'";
//echo $queryPemohon;
$resultManager = oci_parse($con,$managerHRD);
oci_execute($resultManager);
$rowManager = oci_fetch_row($resultManager);
$dataManager = $rowManager[0];

	$pdf->Ln(20);
	$pdf->SetFont('Arial','',7);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.3);
	//$pdf->Cell(120, 20, ',', 0, 0, 'R', 0);	
	$pdf->Cell(47, 10, 'Pemohon', 1, 0, 'C', 0);	
	$pdf->Cell(47, 10, 'Manager', 1, 0, 'C', 0);			
	$pdf->Cell(47, 10, 'HRD', 1, 0, 'C', 0);	
	$pdf->Cell(47, 10, 'Manager HRD', 1, 0, 'C', 0);								
	$pdf->Ln(10);
	$pdf->Cell(47, 30, '', 1, 0, 'C', 0);	
	$pdf->Cell(47, 30, '', 1, 0, 'C', 0);		
	$pdf->Cell(47, 30, '', 1, 0, 'C', 0);
	$pdf->Cell(47, 30, '', 1, 0, 'C', 0);						
	$pdf->Ln(20);
	$pdf->Cell(47, 10, bar128($namakaryawan), 0, 0, 'C', 0);
	$pdf->Cell(47, 10, $managerKaryawan, 0, 0, 'C', 0);		
	$pdf->Cell(47, 10, 'Louis Hananta Kusuma', 0, 0, 'C', 0);
	$pdf->Cell(47, 10, $dataManager, 0, 0, 'C', 0);		
 
$pdf->SetFont('','B');
$pdf->Ln(15);
/* 
$query = "SELECT PPH.PP_NO FROM MJ.MJ_T_PP_HEADER PPH INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PPH.LOCATION_ID WHERE ID=$hdid";
$result = oci_parse($con, $query);
oci_execute($result);
$row = oci_fetch_row($result);
$pp_no = $row[0]; */

$pdf->Output('CetakFPR'. '.pdf', 'F');
$pdf->Output();
?>
