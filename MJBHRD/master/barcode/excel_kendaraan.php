<?php
include '../../main/koneksi.php';

$namaFile ="Excel_Master_kendaraan.xls";

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
	<td colspan=\"14\"><div align=\"left\"><table border=\"1\">
		<tr>
			<td><div align=\"center\">No Asset</div></td>
			<td><div align=\"center\">No Plat</div></td>
			<td><div align=\"center\">No Lambung</div></td>
			<td><div align=\"center\">Area</div></td>
			<td><div align=\"center\">No Serial GPS</div></td>
			<td><div align=\"center\">No HP GPS</div></td>
			<td><div align=\"center\">Penanggung Jawab</div></td>
			<td><div align=\"center\">Driver</div></td>
			<td><div align=\"center\">Status</div></td>
		</tr>
	</table></div></td>
 </tr>


 ";
 //
$countibase=0;
$result = mysqli_query($con, "SELECT * FROM kendaraan");
while ($row = mysqli_fetch_row($result)) {
    $id            = $row[0];
    $no_asset     = $row[1];
	$sj = oci_parse($conn, "
		SELECT MEL.LOCATION_CODES
            FROM CSI_ITEM_INSTANCES cii,MTL_EAM_ASSET_ATTR_VALUES ATTR, EAM_ORG_MAINT_DEFAULTS EOMD, MTL_EAM_LOCATIONS MEL
            where cii.INSTANCE_ID=ATTR.MAINTENANCE_OBJECT_ID(+) AND
            CII.INSTANCE_ID=EOMD.OBJECT_ID AND
            EOMD.AREA_ID=MEL.LOCATION_ID AND
                ATTR.ATTRIBUTE_CATEGORY(+)='Data Kendaraan' AND
                ATTR.C_ATTRIBUTE11 IS NOT NULL
                and cii.maintainable_flag='Y'
                AND ATTR.C_ATTRIBUTE11 NOT LIKE '%EX%' and
				CII.INSTANCE_NUMBER = '".$no_asset."'
	 ");
	oci_execute($sj);
	$rowOr = oci_fetch_row($sj);
	$area 			= $rowOr[0];
    $imei        	= $row[2];
    $no_hp          = $row[3];
    $group     		= $row[4];
    $area_sql      	= $row[5];
    $no_plat      	= $row[6];
    $no_lambung   	= $row[7];
    $bulan     		= $row[8];
    $tahun     		= $row[9];
    $model     		= $row[10];
    $type      		= $row[11];
    $company  		= $row[12];
    $owner       	= $row[13];
    $owner_id   	= $row[14];
    $driver      	= $row[15];
    $timezone    	= $row[16];
    $status      	= $row[17];
    $update_time 	= $row[20];
    $update_by   	= $row[21];
	$no_sn  		= $row[22];
    $isiExcel .= "
		 <tr>
			<td colspan=\"14\"><div align=\"left\"><table border=\"1\">
				<tr>
					<td><div align=\"center\">$no_asset</div></td>
					<td><div align=\"left\">$no_plat</div></td>
					<td><div align=\"left\">$no_lambung-$loccod</div></td>
					<td><div align=\"left\">$area</div></td>
					<td><div align=\"left\">$no_sn</div></td>
					<td><div align=\"left\">$no_hp</div></td>
					<td><div align=\"left\">$owner</div></td>
					<td><div align=\"left\">$driver</div></td>
					<td><div align=\"left\">$status</div></td>
				</tr>
			</table></div></td>
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