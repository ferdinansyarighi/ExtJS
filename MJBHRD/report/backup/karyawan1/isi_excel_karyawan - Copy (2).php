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


 $namaFile ="Report_Karyawan.xls";

// Function penanda awal file (Begin Of File) Excel

function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

// Function penanda akhir file (End Of File) Excel

function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}

// Function untuk menulis data (angka) ke cell excel

function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

// Function untuk menulis data (text) ke cell excel

function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

// header file excel
// header("Status: 200");
	// header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	// header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	// header("Pragma: hack");
	// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	// header("Cache-Control: private", false);
	// header("Content-Description: File Transfer");
	// header("Content-Type: application/force-download");
	// header("Content-Type: application/download");
	// header("Content-Disposition: attachment; filename=\"".$namaFile."\""); 
	// header("Content-Transfer-Encoding: binary");
	
	xlsBOF();
	
	xlsWriteLabel(0,0,"List SO dengan harga 1 rupiah");
	
	xlsWriteLabel(2,0,"No.");
	xlsWriteLabel(2,1,"Perusahaan");
	xlsWriteLabel(2,2,"No SO");
	xlsWriteLabel(2,3,"Customer");
	xlsWriteLabel(2,4,"Tanggal SO");
	xlsWriteLabel(2,5,"No PO");
	xlsWriteLabel(2,6,"Plant");
	xlsWriteLabel(2,7,"Line No");
	xlsWriteLabel(2,8,"Mutu");
	xlsWriteLabel(2,9,"Volume");
	
	$xlsRow = 2;
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
		
		xlsWriteLabel($xlsRow,0,$countHeader);
		xlsWriteLabel($xlsRow,1,$vPerusahaan);
		xlsWriteLabel($xlsRow,2,$vNoSO);
		xlsWriteLabel($xlsRow,3,$vCust);
		xlsWriteLabel($xlsRow,4,$vTglSO);
		xlsWriteLabel($xlsRow,5,$vNoPO);
		xlsWriteLabel($xlsRow,6,$vPlant);
		xlsWriteLabel($xlsRow,7,$vLineNo);
		xlsWriteLabel($xlsRow,8,$vMutu);
		xlsWriteNumber($xlsRow,9,$vQty);
	}

// $isiExcel = "<table border=\"0\">
// <tr>
    // <td colspan=\"7\"><div align=\"center\">Report Karyawan</div></td>
// </tr>
// <tr>
    // <td><div align=\"center\">No</div></td>
    // <td><div align=\"center\">Nama Karyawan</div></td>
    // <td><div align=\"center\">Tanggal Absen</div></td>
    // <td><div align=\"center\">Jam Masuk</div></td>
    // <td><div align=\"center\">Jam Keluar</div></td>
    // <td><div align=\"center\">Keterangan</div></td>
    // <td><div align=\"center\">SIK / SPL</div></td>
    // <td><div align=\"center\">Ijin Khusus</div></td>
    // <td><div align=\"center\">Tgl Approved</div></td>
 // </tr>
 
 // ";
 
 //$isiExcel = xlsWriteLabel(1, 1, 'Report Karyawan');
 

// $isiExcel .= " </table>";
	


// header('Content-Length: ' . filesize($namaFile));
// readfile($namaFile);
xlsEOF();
$fp = fopen($namaFile, "w");
fwrite($fp, $isiExcel);

fclose($fp);
exit();

?>