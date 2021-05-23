<?php
include '../../main/koneksi.php';
session_start();

$nama=""; 
$nama_text=""; 
$plant=""; 
$periode=""; 
$queryfilter=""; 
$tglskr=date('Y-m-d'); 

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
// $pos_name = "";

$queryFilter ='';
$data = '';
$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
if (!$conn) {
	echo "Connection MSSQL Black failed.";
	
	exit;
}
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
	// $pos_name = $_SESSION[APP]['pos_name'];
}

if(isset($_GET['nama'])){
	$nama 		= $_GET['nama'];
	$plant 		= $_GET['plant'];
	$tgldari 	= $_GET['tgldari'];
	$tglke 		= $_GET['tglke'];

	// echo $plant;exit;

	//ambil tgl
	if($tgldari != '' or $tglke != ''){
		if($tgldari == ''){
			$tgldari = "'2015-3-23'";
		}else{
			$tgldari = "'$tgldari'";
		}
		
		if($tglke==''){
			$tglke = "to_char(sysdate, 'YYYY-MM-DD')";
		}else{
			$tglke = "'$tglke'";
		}
		//$queryFilter .= "and to_char(MBA.created_date, 'YYYY-MM-DD') >= $tgldari and to_char(MBA.created_date, 'YYYY-MM-DD') <= $tglke";
	}

	//ambil plant
	if ($plant != "" && $plant != 'null'){
		$queryFilter .= " AND MD.DEPARTCODE='$plant' ";
	}else if ($plant == "" && $plant == 'null') {
		$plant = "";
		$queryFilter .= " AND MD.DepartCode= ''";
	}

	if ($nama != "" && $nama != 'null'){
		$queryFilter .= " AND MK.KaryaCode='$nama' ";
	}
	// echo $queryFilter;exit;
}
// echo $nama;exit;

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

	if ($nama != '') {
		$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
		FROM MastKarya MK, MastDepart MD WHERE MK.KaryaCode=$nama";
		$resultHeader = odbc_exec($conn, $queryHeader);
	}else {
		$queryHeader = "SELECT DISTINCT MD.DEPARTCODE, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
		FROM MastKarya MK, MastDepart MD WHERE MD.DepartCode='$plant'";
		$resultHeader = odbc_exec($conn, $queryHeader);
	}

	while (odbc_fetch_row($resultHeader)) {
		$dataId = odbc_result($resultHeader, 1);
		$TglHeader = substr(odbc_result($resultHeader, 2), 0, 2);

	// if ($nama != '' && $plant != '') { //jika data terisi normal
	// 		$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND CIO.KaryaCode = $dataId AND CIO.KaryaCode = MK.KaryaCode $queryFilter";
	// 	}else if ($plant == '') { //jika data plant tidak ada parameter
	// 		$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND CIO.KaryaCode = $dataId AND CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode";
	// 	}else { //jika data nama tidak ada parameter
	// 		$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE 1=1
	// 			and CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND mk.KaryaCode = cio.KaryaCode and mk.departcode = md.departcode $queryFilter";
	// 	}

		$query = "
		SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName 
		FROM CardInOut CIO, MastKarya MK, MastDepart MD 
		WHERE CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode AND CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke $queryFilter";
		//echo $query;exit;
		$result = odbc_exec($conn, $query);
		while ($tes = odbc_fetch_array($result)) {
			
			if ($tes['StateCode'] == 1) {
				$tes['StateCode'] = "OUT";
			}else if($tes['StateCode'] == 0){
			$tes['StateCode'] = "IN";
			}else if($tes['StateCode'] == 2){
				$tes['StateCode'] = "Break-Out";
			}

			$KaryaCode=$tes['KaryaCode'];
			$KaryaName=$tes["KaryaName"];
			$DepartName=$tes["DepartName"];
			$TranDate=substr($tes["TranDate"],0,19);
			$StateCode=$tes["StateCode"];
			$UserDate=substr($tes["UserDate"],0,19);
		}
	}


	$tgldari1= str_replace("'", " ", $tgldari);
	$tglke1= str_replace("'", " ", $tglke);

	if ($nama != '') {
		$nama1=$nama;
		$nama = $KaryaName;
	}
	// echo $nama1;exit;

$isiExcel = "<table border=\"0\">
<tr>
    <td colspan=\"8\">
    	<div align=\"center\">
    		<h2>Report Detail Presensi Biofinger</h2>
    	</div>
    </td>
</tr>

<tr>
    <td>
    	<div align=\"left\">
    		<b> Employee </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> : </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> $nama </b>
    	</div>
    </td>
</tr>

<tr>
	<td>
		<div align=\"left\">
			<b> Plant </b>
		</div>
	</td>
	<td>
    	<div align=\"center\">
    		<b> : </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> $DepartName </b>
    	</div>
    </td>

</tr>

<tr>
    <td>
    	<div align=\"left\">
    		<b> Periode </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> : </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> $tgldari1 </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> s/d </b>
    	</div>
    </td>
    <td>
    	<div align=\"center\">
    		<b> $tglke1 </b>
    	</div>
    </td>
</tr>

<tr>
	<td><td>
</tr>

<tr>
	<td colspan=\"7\"><div align=\"left\"><table border=\"1\">
		<tr>
			<td><div align=\"center\">No</div></td>
			<td><div align=\"center\">Employee Name</div></td>
			<td><div align=\"center\">Plant</div></td>
			<td><div align=\"center\">ID Finger</div></td>
			<td><div align=\"center\">Checklog</div></td>
			<td><div align=\"center\">IN/OUT</div></td>
			<td><div align=\"center\">Import Date</div></td>
		</tr>
	</td>
</tr>
 ";

 $countibase=0;


  	if ($nama != '') {
		$queryHeader = "SELECT DISTINCT MK.KaryaCode, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
		FROM MastKarya MK, MastDepart MD WHERE MK.KaryaCode=$nama1";
		$resultHeader = odbc_exec($conn, $queryHeader);
	}else {
		$queryHeader = "SELECT DISTINCT MD.DEPARTCODE, CONVERT(VARCHAR(10),DATEADD(day, -3, getdate()),120)
		FROM MastKarya MK, MastDepart MD WHERE MD.DepartCode='$plant'";
		$resultHeader = odbc_exec($conn, $queryHeader);
	}

	while (odbc_fetch_row($resultHeader)) {
		$dataId = odbc_result($resultHeader, 1);
		$TglHeader = substr(odbc_result($resultHeader, 2), 0, 2);

		// if ($nama != '' && $plant != '') { //jika data terisi normal
		// 	$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND CIO.KaryaCode = $dataId AND CIO.KaryaCode = MK.KaryaCode $queryFilter";
		// }else if ($plant == '') { //jika data plant tidak ada parameter
		// 	$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND CIO.KaryaCode = $dataId AND CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode";
		// }else { //jika data nama tidak ada parameter
		// 	$query = "SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName FROM CardInOut CIO, MastKarya MK, MastDepart MD WHERE 1=1
		// 		and CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke AND mk.KaryaCode = cio.KaryaCode and mk.departcode = md.departcode $queryFilter";
		// }

		$query = "
		SELECT DISTINCT CIO.KaryaCode as KaryaCode, CIO.TranDate as TranDate, CIO.StateCode as StateCode, CONVERT(VARCHAR(19), CIO.TranDate,120), CIO.UserDate as UserDate, MK.KaryaName as KaryaName, MD.DepartName as DepartName 
		FROM CardInOut CIO, MastKarya MK, MastDepart MD 
		WHERE CIO.KaryaCode = MK.KaryaCode AND MK.DepartCode = MD.DepartCode AND CONVERT(VARCHAR(10), CIO.TranDate,120) BETWEEN $tgldari AND $tglke $queryFilter";
		//echo $query;exit;
		$result = odbc_exec($conn, $query);
		
		while ($tes = odbc_fetch_array($result)) {
			
			if ($tes['StateCode'] == 1) {
				$tes['StateCode'] = "OUT";
			}else if($tes['StateCode'] == 0){
			$tes['StateCode'] = "IN";
			}else if($tes['StateCode'] == 2){
				$tes['StateCode'] = "Break-Out";
			}

			$KaryaCode=$tes['KaryaCode'];
			$KaryaName=$tes["KaryaName"];
			$DepartName=$tes["DepartName"];
			$TranDate=substr($tes["TranDate"],0,19);
			$StateCode=$tes["StateCode"];
			$UserDate=substr($tes["UserDate"],0,19);
			
			$countibase++;

			$isiExcel .= "
				<tr>
				    <td><div align=\"center\">$countibase</div></td>
				    <td><div align=\"center\">$KaryaName</div></td>
				    <td><div align=\"center\">$DepartName</div></td>
				    <td><div align=\"center\">$KaryaCode</div></td>
				    <td><div align=\"center\">$TranDate</div></td>
				    <td><div align=\"center\">$StateCode</div></td>
				    <td><div align=\"center\">$UserDate</div></td>
				</tr>
			";
		}

	}


$isiExcel .= " </table>";
	
$fp = fopen($namaFile, "w");
fwrite($fp, $isiExcel);

fclose($fp);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);

exit();

?>