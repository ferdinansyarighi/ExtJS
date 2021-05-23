<?php
include '../../main/koneksi.php';


$nama=""; 
$plant=""; 
$periode=""; 
$periode1="";
$periode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun="";


$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');

if (!$conn) {
	echo "Connection MSSQL Black failed.";
	
	exit;
} 
if (isset($_GET['nama']) || isset($_GET['periode']) || isset($_GET['plant']))
{	
	$nama = trim($_GET['nama']);
	$periode = $_GET['periode'];
	$plant = $_GET['plant'];
	//echo $periode;exit;
	// $tanngalFrom = $_GET['tanngalFrom'];
	// $tanggalTo = $_GET['tanggalTo'];
	// $tangalFromTime = 0;
	// $tanggalToTime = 0;
	
	// if($tanngalFrom != 'null' && $tanggalTo != 'null'){
		// $hari=substr($tanngalFrom, 0, 2);
		// $bulan=substr($tanngalFrom, 3, 2);
		// $tahun=substr($tanngalFrom, 6, 2);
		// if (strlen($tahun)==2)
		// {
			// $tahun = $tahunbaru . "" . $tahun;
		// }
		// $tanngalFrom = $tahun . "-" . $bulan . "-" . $hari;
		// $tangalFromTime = strtotime($tanngalFrom);
		// $hari=substr($tanggalTo, 0, 2);
		// $bulan=substr($tanggalTo, 3, 2);
		// $tahun=substr($tanggalTo, 6, 2);
		// if (strlen($tahun)==2)
		// {
			// $tahun = $tahunbaru . "" . $tahun;
		// }
		// $tanggalTo = $tahun . "-" . $bulan . "-" . $hari;
		// $tanggalToTime = strtotime($tanggalTo);
		
		// $queryfilterDetail .= " AND CONVERT(VARCHAR(10), CIO.TranDate,120) >= '$tanngalFrom'
						// AND CONVERT(VARCHAR(10), CIO.TranDate,120) <= '$tanggalTo' ";
	// }
	
	if ($nama != "" && $nama != 'null'){
		$queryfilter .= " AND MK.KaryaCode='$nama' ";
	}
	if ($plant != "" && $plant != 'null'){
		$queryfilter .= " AND MK.DEPARTCODE='$plant' ";
	}
	
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
	<td><div align=\"center\">Id Finger</div></td>
    <td><div align=\"center\">Nama Karyawan</div></td>
    <td colspan=\"62\"><div align=\"center\">Tanggal</div></td>
 </tr>
<tr>
    <td><div align=\"center\"></div></td>
    <td><div align=\"center\"></div></td>
    <td><div align=\"center\"></div></td>
 ";
$isiTambahan = "<tr>
    <td><div align=\"center\"></div></td>
    <td><div align=\"center\"></div></td>
    <td><div align=\"center\"></div></td>"; 
  $x = 21;
	$count=0;
	$queryDetail="SELECT tgl.tglTransaksi, msk.TranDate, msk.StateCode
	FROM ( ";
	$tahunlooping=substr($periode, 0, 4);
	$bulanlooping=substr($periode, 5, 2);
	$harilooping=substr($periode, 8, 2);
	for($i=0; $i<=$selisih; $i++){
		if($i>=11 && $selisih==30 && $count==0){
			$count=1;
			$x=1;
			$bulanlooping=$bulanlooping + 1; 
			if(strlen($bulanlooping)==1)
				$bulanlooping="0" . $bulanlooping;
			if($bulanlooping=='13'){
				$bulanlooping='01'; 
				$tahunlooping=$tahunlooping + 1; 
			}
		} elseif ($i>=10 && $selisih==29 && $count==0){
			$count=1;
			$x=1;
			$bulanlooping=$bulanlooping + 1; 
			if(strlen($bulanlooping)==1)
				$bulanlooping="0" . $bulanlooping;
			if($bulanlooping=='13'){
				$bulanlooping='01'; 
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
					if($bulanlooping=='13'){
						$bulanlooping='01'; 
						$tahunlooping=$tahunlooping + 1; 
					}
				}
			} else {
				$count=1;
				$x=1;
				$bulanlooping=$bulanlooping + 1;  
				if(strlen($bulanlooping)==1)
					$bulanlooping="0" . $bulanlooping;
				if($bulanlooping=='13'){
					$bulanlooping='01'; 
					$tahunlooping=$tahunlooping + 1; 
				}
			}
		}  
		if(strlen($x)==1)
			$x="0" . $x;
		$tglTransaksi = $tahunlooping . "-" . $bulanlooping . "-" . $x;
		if($i == 0){
			$queryDetail .= " SELECT '$tglTransaksi' tglTransaksi";
		} else {
			$queryDetail .= " UNION
			SELECT '$tglTransaksi' tglTransaksi ";
		}
		
		//echo $tglTransaksi;
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
	$queryDetail .= " ) tgl 
	";
	
	$isiExcel .= $isiTambahan;
	//, '10249'
	$countHeader = 0;
	$queryHeader = "SELECT DISTINCT MK.KaryaCode, MK.KaryaName
	FROM MastKarya MK WHERE 1=1 $queryfilter -- AND MK.KaryaCode IN ('10038', '10249') -- ";
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
		$isiExcel .= "
		<tr>
			<td><div align=\"center\">$countHeader</div></td>
			<td><div align=\"left\">$dataId</div></td>
			<td><div align=\"left\">$dataName</div></td>
		 ";
		$count=0;
		$tmpTgl = '';
		$queryLeft = "  LEFT JOIN
		(SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
		FROM CardInOut CIO 
		WHERE CIO.KaryaCode=$dataId
		$queryfilterDetail) 
		msk ON CONVERT(VARCHAR(10), msk.TranDate,120) = tgl.tglTransaksi
		ORDER BY tgl.tglTransaksi ";
		$queryGabungan = $queryDetail . $queryLeft;
		//echo $queryGabungan;exit;
		$result = odbc_exec($conn, $queryGabungan);
		while(odbc_fetch_row($result)){
			$Tgl = odbc_result($result, 1);
			$TglTime = odbc_result($result, 2);
			$Status = odbc_result($result, 3);
			
			if($count==0){
				$tmpTgl = $Tgl;
			}
			
			if($tmpTgl != $Tgl){
				$isiExcel .= "
					<td><div align=\"center\">$JamMasukAsli</div></td>
					<td><div align=\"center\">$JamKeluarAsli</div></td>
				 ";			
				$countIn = 0;
				$JamMasukAsli = '';
				$JamKeluarAsli = '';
				$JamMasuk = '';
				$JamKeluar = '';	
			} 
			
			if($Status == 0 && $countIn == 0){
				$JamMasukAsli = substr($TglTime, 11, 5);
				$JamMasuk = str_replace(':', '', $JamMasukAsli);
				$countIn++;
			}
			if($Status == 1){
				$JamKeluarAsli = substr($TglTime, 11, 5);
				$JamKeluar = str_replace(':', '', $JamKeluarAsli);
			}
			
			$tmpTgl = $Tgl;
			$count++;
		}
		$isiExcel .= "
					<td><div align=\"center\">$JamMasukAsli</div></td>
					<td><div align=\"center\">$JamKeluarAsli</div></td>
					</tr>";
	}
$isiExcel .= " </table>";
	
$fp = fopen($namaFile, "w");
fwrite($fp, $isiExcel);

fclose($fp);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);

exit();

?>