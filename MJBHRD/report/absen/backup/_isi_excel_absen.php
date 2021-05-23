<?php
include '../../main/koneksi.php';

$tgldcl = "";
$collector = "";
$hari="";
$bulan="";
$tahun=""; 
$noinv=""; 
$plant=""; 
$cust=""; 
$periode1="";
$periode2="";
$queryfilter=""; 
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tglskr=date('Y-m-d'); 
$tahunskr=date('Y'); 
$bulanskr=date('m'); 
$hariskr=date('d'); 
$tgldblama='2015-02-01';
$tgldblamasd=strtotime($tgldblama);
if (isset($_GET['nama']) || isset($_GET['periode']) || isset($_GET['plant']))
{	
	$nama = trim($_GET['nama']);
	$periode = $_GET['periode'];
	$plant = $_GET['plant'];
	$tanngalFrom = $_GET['tanngalFrom'];
	$tanggalTo = $_GET['tanggalTo'];
	$tangalFromTime = 0;
	$tanggalToTime = 0;
	
	if($tanngalFrom != 'null' && $tanggalTo != 'null'){
		$hari=substr($tanngalFrom, 0, 2);
		$bulan=substr($tanngalFrom, 3, 2);
		$tahun=substr($tanngalFrom, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tanngalFrom = $tahun . "-" . $bulan . "-" . $hari;
		$tangalFromTime = strtotime($tanngalFrom);
		$hari=substr($tanggalTo, 0, 2);
		$bulan=substr($tanggalTo, 3, 2);
		$tahun=substr($tanggalTo, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tanggalTo = $tahun . "-" . $bulan . "-" . $hari;
		$tanggalToTime = strtotime($tanggalTo);
	}
	
	if ($nama != "" && $nama != 'null'){
		$queryfilter .= " AND MK.KaryaCode='$nama' ";
	}
	if ($plant != "" && $plant != 'null'){
		$queryfilter .= " AND MK.DEPARTCODE='$plant' ";
	}
	
	$tahunsebelum=$tahunskr - 1; 
	$bulansebelum=$bulanskr - 1; 
	$harisebelum=$hariskr - 1; 
	
	$tahunsesudah=$tahunskr + 1; 
	$bulansesudah=$bulanskr + 1; 
	$harisesudah=$hariskr + 1; 
	
	$tahundipakai=substr($periode, 0, 4);
	$bulandipakai=substr($periode, 5, 2);
	$haridipakai=substr($periode, 8, 2);
	
	$periode1 = substr($periode, 0, 10);
	$periode2 = substr($periode, 15, 10);
	$selisih=round((strtotime($periode2)-strtotime($periode1))/(60*60*24));
	$modTahun = $tahundipakai % 4;
	//echo $periode2;
	
	
}


 $namaFile ="Report_Absen.xls";

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
    <td colspan=\"65\"><div align=\"center\">Report Absen</div></td>
</tr>
<tr>
    <td><div align=\"center\">No</div></td>
    <td><div align=\"center\">Nama Karyawan</div></td>
    <td colspan=\"62\"><div align=\"center\">Tanggal</div></td>
 </tr>
<tr>
    <td><div align=\"center\"></div></td>
    <td><div align=\"center\"></div></td>
 ";
$isiTambahan = "<tr>
    <td><div align=\"center\"></div></td>
    <td><div align=\"center\"></div></td>"; 
  $x = 21;
	$count=0;
	for($i=0; $i<=$selisih; $i++){
		if($i>=11 && $selisih==30 && $count==0){
			$count=1;
			$x=1;
		} elseif ($i>=10 && $selisih==29 && $count==0){
			$count=1;
			$x=1;
		} elseif ($i>=8 && $selisih<29 && $count==0){
			if($modTahun==0){
				if($i==9){
					$count=1;
					$x=1;
				}
			} else {
				$count=1;
				$x=1;
			}
		} 
		$isiExcel .= "
			<td colspan=\"2\"><div align=\"center\">$x</div></td>
		 ";		 
		$isiTambahan .= "
			<td><div align=\"center\">In</div></td>
			<td><div align=\"center\">Out</div></td>"; 
		$x++;
	}
	$isiExcel .= " </tr>";
	$isiTambahan .= " </tr>";
	
	$isiExcel .= $isiTambahan;
	
	$countHeader = 0;
	$queryHeader = "SELECT DISTINCT MK.KaryaCode, MK.KaryaName
	FROM MastKarya MK WHERE 1=1 AND MK.KaryaCode IN ('10038', '10249') --$queryfilter ";
	$resultHeader = odbc_exec($conn, $queryHeader);
	while(odbc_fetch_row($resultHeader)){
		$countHeader++;
		$dataId = odbc_result($resultHeader, 1);
		$dataName = odbc_result($resultHeader, 2);
		$JamMasukAsli = '';
		$JamKeluarAsli = '';
		$JamMasuk = '';
		$JamKeluar = '';	
		$tahunlooping=substr($periode, 0, 4);
		$bulanlooping=substr($periode, 5, 2);
		$harilooping=substr($periode, 8, 2);
		$countIn = 0;
		$query = "";
		$isiExcel .= "
		<tr>
			<td><div align=\"center\">$countHeader</div></td>
			<td><div align=\"left\">$dataName</div></td>
		 ";
		 $count=0;
		for($i=0; $i<=$selisih; $i++){
			if($i>=11 && $selisih==30 && $count==0){
				$count=1;
				$x=1;
				$bulanlooping=$bulanlooping + 1; 
				if(strlen($bulanlooping)==1)
					$bulanlooping="0" . $bulanlooping;
				if($bulanlooping=='01'){
					$tahunlooping=$tahunlooping + 1; 
				}
			} elseif ($i>=10 && $selisih==29 && $count==0){
				$count=1;
				$x=1;
				$bulanlooping=$bulanlooping + 1; 
				if(strlen($bulanlooping)==1)
					$bulanlooping="0" . $bulanlooping;
				if($bulanlooping=='01'){
					$tahunlooping=$tahunlooping + 1; 
				}
			} elseif ($i>=8 && $selisih<29 && $count==0){
				if($modTahun==0){
					if($i==9){
						$count=1;
						$x=1;
						$bulanlooping=$bulanlooping + 1; 
						if(strlen($bulanlooping)==1)
							$bulanlooping="0" . $bulanlooping;
						if($bulanlooping=='01'){
							$tahunlooping=$tahunlooping + 1; 
						} 
					}
				} else {
					$count=1;
					$x=1;
					$bulanlooping=$bulanlooping + 1;  
					if(strlen($bulanlooping)==1)
						$bulanlooping="0" . $bulanlooping;
					if($bulanlooping=='01'){
						$tahunlooping=$tahunlooping + 1;
					}
				}
			} 
			if(strlen($x)==1)
				$x="0" . $x;
			$tglTransaksi = $tahunlooping . "-" . $bulanlooping . "-" . $x;
			//echo $tglTransaksi;
			$tglTransaksiTime = strtotime($tglTransaksi);
			if(($tglTransaksiTime >= $tangalFromTime && $tglTransaksiTime <= $tanggalToTime) OR ($tangalFromTime == 0 && $tanggalToTime == 0)){
				if ($i == 0){
					$query .= "
					SELECT MIN(CIO.TranDate) AS tanggalFrom, MAX(CIO.TranDate) AS tanggalTo
					FROM CardInOut CIO 
					WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) = '$tglTransaksi'
					AND CIO.KaryaCode=$dataId ";
				} else {
					$query .= "UNION
					SELECT MIN(CIO.TranDate) AS tanggalFrom, MAX(CIO.TranDate) AS tanggalTo
					FROM CardInOut CIO 
					WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) = '$tglTransaksi'
					AND CIO.KaryaCode=$dataId ";
				}
				//echo $query;
			}
			$x++;
		}
		$result = odbc_exec($conn, $query);
		while(odbc_fetch_row($result)){
			$TglFrom = odbc_result($result, 1);
			$TglTo = odbc_result($result, 2);
			$JamMasukAsli = substr($TglFrom, 11, 5);
			$JamKeluarAsli = substr($TglTo, 11, 5);
						
			$isiExcel .= "
				<td><div align=\"center\">$JamMasukAsli</div></td>
				<td><div align=\"center\">$JamKeluarAsli</div></td>
			 ";
			$JamMasukAsli = '';
			$JamKeluarAsli = '';
		}
		$isiExcel .= " </tr>";
		
	}
$isiExcel .= " </table>";
	
$fp = fopen($namaFile, "w");
fwrite($fp, $isiExcel);

fclose($fp);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);

exit();

?>