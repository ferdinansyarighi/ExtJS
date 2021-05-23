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
	$nama = $_GET['nama'];
	$periode = $_GET['periode'];
	$plant = $_GET['plant'];
	$tanngalFrom = $_GET['tanngalFrom'];
	$tanggalTo = $_GET['tanggalTo'];
	$tanngalFromTime = 0;
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
		$tanngalFromTime = strtotime($tanngalFrom);
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
	
	echo $periode;
	if ($nama != "" && $nama != 'null'){
		$queryfilter .= " AND MMK.ID='$nama' ";
	}
	if ($plant != "" && $plant != 'null'){
		$queryfilter .= " AND UPPER(HP.PARTY_ID)='$plant' ";
	}
	
	$tahunsebelum=$tahunskr - 1; 
	$bulansebelum=$bulanskr - 1; 
	$harisebelum=$hariskr - 1; 
	
	$tahunsesudah=$tahunskr + 1; 
	$bulansesudah=$bulanskr + 1; 
	$harisesudah=$hariskr + 1; 
	
	$tahundipakai=0; 
	$bulandipakai=0; 
	$haridipakai=0; 
	
	//echo $tahunsebelum . " - " . $bulansebelum . " - " . $harisebelum ;
	
	if($hariskr >= 21){
		if(strlen($bulansesudah)==1)
			$bulansesudah="0" . $bulansesudah;
		$periode1 = $tahunskr . "-" . $bulanskr . "-21";
		$tahundipakai=$tahunskr; 
		$bulandipakai=$bulanskr; 
		$haridipakai=21; 
		if($bulanskr=='12'){
			$periode2 = $tahunsesudah . "-" . $bulansesudah . "-20";
		} else {
			$periode2 = $tahunskr . "-" . $bulansesudah . "-20";
		}
	} else {
		if(strlen($bulansebelum)==1)
			$bulansebelum="0" . $bulansebelum;
		if($bulanskr=='01'){
			$periode1 = $tahunsebelum . "-" . $bulansebelum . "-21";
			$tahundipakai=$tahunsebelum; 
			$bulandipakai=$bulansebelum; 
			$haridipakai=21;
		} else {
			$periode1 = $tahunskr . "-" . $bulansebelum . "-21";
			$tahundipakai=$tahunskr; 
			$bulandipakai=$bulansebelum; 
			$haridipakai=21;
		}
		$periode2 = $tahunskr . "-" . $bulanskr . "-20";
	}
	
	$selisih=round((strtotime($periode2)-strtotime($periode1))/(60*60*24));
	$modTahun = $tahunsesudah % 4;
	//echo $modTahun;
	
	
}


 // $namaFile ="Report_Absen.xls";

// // Function penanda awal file (Begin Of File) Excel

// function xlsBOF() {
// echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
// return;
// }

// // Function penanda akhir file (End Of File) Excel

// function xlsEOF() {
// echo pack("ss", 0x0A, 0x00);
// return;
// }

// // Function untuk menulis data (angka) ke cell excel

// function xlsWriteNumber($Row, $Col, $Value) {
// echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
// echo pack("d", $Value);
// return;
// }

// // Function untuk menulis data (text) ke cell excel

// function xlsWriteLabel($Row, $Col, $Value ) {
// $L = strlen($Value);
// echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
// echo $Value;
// return;
// }

// // header file excel
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


// $isiExcel = "<table border=\"0\">
// <tr>
    // <td colspan=\"65\"><div align=\"center\">Report Absen</div></td>
// </tr>
// <tr>
    // <td><div align=\"center\">No</div></td>
    // <td><div align=\"center\">Nama Karyawan</div></td>
    // <td colspan=\"62\"><div align=\"center\">Tanggal</div></td>
 // </tr>
// <tr>
    // <td><div align=\"center\"></div></td>
    // <td><div align=\"center\"></div></td>
 // ";
// $isiTambahan = "<tr>
    // <td><div align=\"center\"></div></td>
    // <td><div align=\"center\"></div></td>"; 
  // $x = 21;
	// $count=0;
	// for($i=0; $i<=$selisih; $i++){
		// if($i>=11 && $selisih==30 && $count==0){
			// $count=1;
			// $x=1;
		// } elseif ($i>=10 && $selisih==29 && $count==0){
			// $count=1;
			// $x=1;
		// } elseif ($i>=8 && $selisih<29 && $count==0){
			// if($modTahun==0){
				// if($i==9){
					// $count=1;
					// $x=1;
				// }
			// } else {
				// $count=1;
				// $x=1;
			// }
		// } 
		// $isiExcel .= "
			// <td colspan=\"2\"><div align=\"center\">$x</div></td>
		 // ";		 
		// $isiTambahan .= "
			// <td><div align=\"center\">In</div></td>
			// <td><div align=\"center\">Out</div></td>"; 
		// $x++;
	// }
	// $isiExcel .= " </tr>";
	// $isiTambahan .= " </tr>";
	
	// $isiExcel .= $isiTambahan;
	
	// $countHeader = 0;
	// $queryHeader = "SELECT DISTINCT MK.KaryaCode, MK.KaryaName
	// FROM MastKarya MK WHERE MK.KaryaCode=10038 ";
	// $resultHeader = odbc_exec($conn, $queryHeader);
	// while(odbc_fetch_row($resultHeader)){
		// $countHeader++;
		// $dataId = odbc_result($resultHeader, 1);
		// $dataName = odbc_result($resultHeader, 2);
		// $JamMasukAsli = '';
		// $JamKeluarAsli = '';
		// $JamMasuk = '';
		// $JamKeluar = '';
		// $countIn = 0;
		// $isiExcel .= "
		// <tr>
			// <td><div align=\"center\">$countHeader</div></td>
			// <td><div align=\"left\">$dataName</div></td>
		 // ";
		 // $count=0;
		// for($i=0; $i<=$selisih; $i++){
			// if($i>=11 && $selisih==30 && $count==0){
				// $count=1;
				// $x=1;
				// $bulandipakai=$bulandipakai + 1; 
				// $haridipakai=21;
				// if($bulanskr=='12'){
					// $tahundipakai=$tahundipakai + 1; 
					// $bulandipakai='01'; 
				// }
				// if(strlen($bulansebelum)==1)
					// $bulandipakai="0" . $bulandipakai;
			// } elseif ($i>=10 && $selisih==29 && $count==0){
				// $count=1;
				// $x=1;
				// $bulandipakai=$bulandipakai + 1; 
				// $haridipakai=21;
				// if($bulanskr=='12'){
					// $tahundipakai=$tahundipakai + 1; 
					// $bulandipakai='01'; 
				// }
				// if(strlen($bulansebelum)==1)
					// $bulandipakai="0" . $bulandipakai;
			// } elseif ($i>=8 && $selisih<29 && $count==0){
				// if($modTahun==0){
					// if($i==9){
						// $count=1;
						// $x=1;
						// $bulandipakai=$bulandipakai + 1; 
						// $haridipakai=21;
						// if($bulanskr=='12'){
							// $tahundipakai=$tahundipakai + 1; 
							// $bulandipakai='01'; 
						// } 
						// if(strlen($bulansebelum)==1)
							// $bulandipakai="0" . $bulandipakai;
					// }
				// } else {
					// $count=1;
					// $x=1;
					// $bulandipakai=$bulandipakai + 1; 
					// $haridipakai=21;
					// if($bulanskr=='12'){
						// $tahundipakai=$tahundipakai + 1; 
						// $bulandipakai='01'; 
					// }
					// if(strlen($bulansebelum)==1)
						// $bulandipakai="0" . $bulandipakai;
				// }
			// } 
			// $countIn = 0;
			// if(strlen($x)==1)
				// $x="0" . $x;
			// $query = "SELECT CIO.KaryaCode, CIO.TranDate, CIO.StateCode
			// FROM CardInOut CIO 
			// WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) = '$tahundipakai-$bulandipakai-$x'
			// AND CIO.KaryaCode=$dataId ";
			// //echo $query;
			// $result = odbc_exec($conn, $query);
			// while(odbc_fetch_row($result)){
				// $Tgl = odbc_result($result, 2);
				// $Status = odbc_result($result, 3);

				// if($Status == 0 && $countIn == 0){
					// $JamMasukAsli = substr($Tgl, 11, 5);
					// $JamMasuk = str_replace(':', '', $JamMasukAsli);
					// $countIn++;
				// }
				// if($Status == 1){
					// $JamKeluarAsli = substr($Tgl, 11, 5);
					// $JamKeluar = str_replace(':', '', $JamKeluarAsli);
				// }
			// }
			// $isiExcel .= "
				// <td><div align=\"center\">$JamMasukAsli</div></td>
				// <td><div align=\"center\">$JamKeluarAsli</div></td>
			 // ";
			// $JamMasukAsli = '';
			// $JamKeluarAsli = '';
			// $x++;
		// }
		// $isiExcel .= " </tr>";
		
	// }
// $isiExcel .= " </table>";
	
// $fp = fopen($namaFile, "w");
// fwrite($fp, $isiExcel);

// fclose($fp);

// header('Content-Length: ' . filesize($namaFile));
// readfile($namaFile);

// exit();

?>