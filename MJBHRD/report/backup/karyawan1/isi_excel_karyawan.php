<?php
include 'E:/dataSource/MJBHRD/main/koneksi.php';

$periode1="";
$periode2="";
$queryfilter = "";
if (isset($_GET['periode']) || isset($_GET['nama']))
{	
	$periode = $_GET['periode'];
	$periode1 = substr($periode, 0, 10);
	$periode2 = substr($periode, 15, 10);
	$nama = $_GET['nama'];
	if($nama!=''){
		$queryfilter .= " AND PPF.FULL_NAME='$nama'";
	}
} 


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../excel/PHPExcel.php';

// Create new PHPExcel object
echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("MJB")
							 ->setLastModifiedBy("MJB")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");


// Add some data
echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'List SO dengan harga 1 rupiah')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'Perusahaan')
            ->setCellValue('C3', 'No SO')
            ->setCellValue('D3', 'Customer')
            ->setCellValue('E3', 'Tanggal SO')
            ->setCellValue('F3', 'No PO')
            ->setCellValue('G3', 'Plant')
            ->setCellValue('H3', 'Line No')
            ->setCellValue('I3', 'Mutu')
            ->setCellValue('J3', 'Volume');
			

	$xlsRow = 3;
	$countHeader = 0;

	$queryHeader = "SELECT HOU.NAME PERUSAHAAN
	, OOH.ORDER_NUMBER NO_SO
	, HP.PARTY_NAME CUST
	, OOH.ORDERED_DATE TGL_SO
	, OOH.CUST_PO_NUMBER NO_PO
	, HOU1.NAME PLANT
	, OOL.LINE_NUMBER||'.'||OOL.SHIPMENT_NUMBER LINE_NO
	, MSI.DESCRIPTION MUTU 
	, OOL.ORDERED_QUANTITY QTY
	FROM APPS.OE_ORDER_HEADERS_ALL OOH
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON OOH.ORG_ID = HOU.ORGANIZATION_ID 
	INNER JOIN APPS.HZ_CUST_ACCOUNTS_ALL HCAA ON OOH.SOLD_TO_ORG_ID = HCAA.CUST_ACCOUNT_ID
	INNER JOIN APPS.HZ_PARTIES HP ON HCAA.PARTY_ID = HP.PARTY_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU1 ON OOH.SHIP_FROM_ORG_ID = HOU1.ORGANIZATION_ID
	INNER JOIN APPS.OE_ORDER_LINES_ALL OOL ON OOH.HEADER_ID = OOL.HEADER_ID
	INNER JOIN APPS.MTL_SYSTEM_ITEMS_B MSI ON OOL.INVENTORY_ITEM_ID = MSI.INVENTORY_ITEM_ID AND OOL.SHIP_FROM_ORG_ID = MSI.ORGANIZATION_ID
	WHERE OOL.UNIT_SELLING_PRICE = 1
	AND (ROUND((SYSDATE - OOH.BOOKED_DATE), 0) - 1) >= 2
	AND OOH.ORG_ID IN (81, 201)
	AND OOL.CANCELLED_FLAG = 'N'
	AND OOH.FLOW_STATUS_CODE = 'BOOKED'
	AND TO_CHAR(OOH.ORDERED_DATE, 'YYYY-MM-DD') >= '2016-01-01'
	ORDER BY OOH.ORDER_NUMBER, LINE_NO";
	//echo $queryHeader;
	$resultHeader = oci_parse($con,$queryHeader);
	oci_execute($resultHeader);
	while($row = oci_fetch_row($resultHeader))
	{
		$xlsRow++;
		$countHeader++;
		$vPerusahaan = $row[0];
		$vNoSO = $row[1];
		$vCust = $row[2];
		$vTglSO = $row[3];
		$vNoPO = $row[4];
		$vPlant = $row[5];
		$vLineNo = $row[6];
		$vMutu = $row[7];
		$vQty = $row[8];

		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $xlsRow, $countHeader)
            ->setCellValue('B' . $xlsRow, $vPerusahaan)
            ->setCellValue('C' . $xlsRow, $vNoSO)
            ->setCellValue('D' . $xlsRow, $vCust)
            ->setCellValue('E' . $xlsRow, $vTglSO)
            ->setCellValue('F' . $xlsRow, $vNoPO)
            ->setCellValue('G' . $xlsRow, $vPlant)
            ->setCellValue('H' . $xlsRow, $vLineNo)
            ->setCellValue('I' . $xlsRow, $vMutu)
            ->setCellValue('J' . $xlsRow, $vQty);
	}
	
			
			
			

// Rename worksheet
echo date('H:i:s') , " Rename worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 95 file
echo date('H:i:s') , " Write to Excel5 format" , EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('SOHARGA1.xls');
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s') , " File written to " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
// Echo memory usage
echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing files" , EOL;
echo 'Files have been created in ' , getcwd() , EOL;


?>