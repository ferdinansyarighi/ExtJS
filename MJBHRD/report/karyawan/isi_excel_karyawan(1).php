<?php
include '../../main/koneksi.php';

$periode="";
$periode1="";
$periode2="";
$queryfilter = "";
if (isset($_GET['periode']) || isset($_GET['nama']))
{	
	$periode1 = $_GET['periode1'];
	$periode2 = $_GET['periode2'];
	$nama = $_GET['nama'];
	$dept = $_GET['dept'];
	if($nama!='' AND $nama!='null'){
		$queryfilter .= " AND PERSON_ID='$nama'";
	}
	if($dept!='' AND $dept!='null'){
		$queryfilter .= " AND DEPT='$dept'";
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
header("Status: 200");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header("Pragma: hack");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Description: File Transfer");
	header("Content-Type: application/force-download");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment; filename=\"".$namaFile."\""); 
	header("Content-Transfer-Encoding: binary");


$isiExcel = "<table border=\"0\">
<tr>
    <td colspan=\"7\"><div align=\"center\">Report Karyawan</div></td>
</tr>
<tr>
    <td><div align=\"center\">No</div></td>
    <td><div align=\"center\">Nama Karyawan</div></td>
    <td><div align=\"center\">Tanggal Absen</div></td>
    <td><div align=\"center\">Jam Masuk</div></td>
    <td><div align=\"center\">Jam Keluar</div></td>
    <td><div align=\"center\">Keterangan</div></td>
    <td><div align=\"center\">SIK / SPL</div></td>
    <td><div align=\"center\">Ijin Khusus</div></td>
    <td><div align=\"center\">Tgl Approved</div></td>
 </tr>
 
 ";
 
	$countHeader = 0;
	$queryHeader = "SELECT DISTINCT PPF.FULL_NAME AS NAMA_KARYAWAN
	, TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') AS TANGGAL_ABSEN
	, MTT.JAM_MASUK
	, MTT.JAM_KELUAR
	, MME.ELEMENT_NAME AS KETERANGAN
	, CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' AND MME.ELEMENT_NAME<>'MASUK' THEN MTS.KATEGORI
	WHEN MME.ELEMENT_NAME='LEMBUR' THEN (CASE WHEN NVL(SPL.ID, 0)<>0 THEN 'ADA SPL' ELSE '' END) END AS SIK_SPL 
	, MTS.IJIN_KHUSUS
	, CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' AND MME.ELEMENT_NAME<>'MASUK' THEN (CASE WHEN NVL(MTS.ID, 0)<>0 AND MTS.STATUS_DOK='Approved' THEN (SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SIK'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=1 AND TRANSAKSI_KODE='SIK')) ELSE '' END)
	WHEN MME.ELEMENT_NAME='LEMBUR' THEN (CASE WHEN NVL(SPL.ID, 0)<>0 AND SPL.STATUS_DOK='Approved' THEN (SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
	FROM MJ.MJ_T_APPROVAL MTA 
	WHERE MTA.TRANSAKSI_ID=SPL.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SPL'
	AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=SPL.ID AND APP_ID=1 AND TRANSAKSI_KODE='SPL')) ELSE '' END)
	ELSE '' END AS TGL_APPROVED
	FROM MJ.MJ_T_TIMECARD MTT
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
	LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO
	LEFT JOIN 
	(
		SELECT MTSPD.ID, MTSP.TANGGAL_SPL, MTSPD.NAMA, MTSPD.STATUS_DOK
		FROM MJ.MJ_T_SPL MTSP
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSPD ON MTSP.ID=MTSPD.MJ_T_SPL_ID
		WHERE MTSP.STATUS=1
	) SPL ON SPL.TANGGAL_SPL=MTT.TANGGAL AND SPL.NAMA=PPF.FULL_NAME
	WHERE  TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
	AND  TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2'
	AND (MME.ELEMENT_NAME <> 'LEMBUR' OR NVL(SPL.ID, 0)<>0)
	$queryfilter
	ORDER BY PPF.FULL_NAME, TANGGAL_ABSEN";
	echo $queryHeader;
	$resultHeader = oci_parse($con,$queryHeader);
	oci_execute($resultHeader);
	while($row = oci_fetch_row($resultHeader))
	{
		$countHeader++;
		$namaKaryawan = $row[0];
		$tanggalAbsen = $row[1];
		$jamMasuk = $row[2];
		$jamKeluar = $row[3];
		$keterangan = $row[4];
		$sikspl = $row[5];
		$ijinKhusus = $row[6];
		$tglapp = $row[7];
		$isiExcel .= "
		<tr>
			<td><div align=\"center\">$countHeader</div></td>
			<td><div align=\"left\">$namaKaryawan</div></td>
			<td><div align=\"left\">$tanggalAbsen</div></td>
			<td><div align=\"left\">$jamMasuk</div></td>
			<td><div align=\"left\">$jamKeluar</div></td>
			<td><div align=\"left\">$keterangan</div></td>
			<td><div align=\"left\">$sikspl</div></td>
			<td><div align=\"left\">$ijinKhusus</div></td>
			<td><div align=\"left\">$tglapp</div></td>
		 </tr>
		 ";		
	}
$isiExcel .= " </table>";
	
$fp = fopen($namaFile, "w");
fwrite($fp, $isiExcel);

fclose($fp);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);

exit();

?>