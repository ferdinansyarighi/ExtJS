<?PHP
include '../../main/koneksi.php';

if (isset($_GET['nolambung'])) {
    $noLambung     = $_GET['nolambung'];
    $area     = $_GET['area'];
}

if($noLambung!=''){
	$where = $noLambung;
}else{
	$where = "";
}
if($area!=''){
	$whereArea = " AND upper(MEL.LOCATION_CODES) = '$area'";
}else{
	$whereArea = "";
}
$result = mysqli_query($con, "SELECT * FROM kendaraan ORDER BY no_lambung");
$no=0;
while ($row = mysqli_fetch_row($result)) {
	$no_asset[$no]="'".$row[1]."'";
	$no++;
}

//masih mau split data dari instance_description from CSI_ITEM_INSTANCES buat ngisi ASSET_MODEL dan ASSET_TIPE

$asset_list = oci_parse($conn, "
SELECT  CII.INSTANCE_NUMBER AS ASSET_NUMBER, MSI.SEGMENT1 AS ASSET_GROUP, MEL.LOCATION_CODES AS AREA, ATTR.C_ATTRIBUTE2 AS NOPOL,
        ATTR.C_ATTRIBUTE11 AS NO_LAMBUNG, instance_description, ATTR.C_ATTRIBUTE7 AS ASSET_TIPE, ATTR.C_ATTRIBUTE3 AS ASSET_BULAN,
        ATTR.C_ATTRIBUTE4 AS ASSET_TAHUN, HOU.NAME AS ASSET_COMPANY, CII.INSTANCE_ID AS ID_BARCODE, HOU.ORGANIZATION_ID AS ORG_ID
FROM    CSI_ITEM_INSTANCES CII, MTL_SYSTEM_ITEMS_B MSI, EAM_ORG_MAINT_DEFAULTS EOMD, MTL_EAM_LOCATIONS MEL,
        MTL_EAM_ASSET_ATTR_VALUES ATTR, HR_ORGANIZATION_UNITS HOU
WHERE   CII.INVENTORY_ITEM_ID=MSI.INVENTORY_ITEM_ID AND 
        CII.INV_MASTER_ORGANIZATION_ID=MSI.ORGANIZATION_ID AND
        CII.INSTANCE_ID=EOMD.OBJECT_ID(+) AND
        EOMD.AREA_ID=MEL.LOCATION_ID(+) AND
        CII.INSTANCE_ID=ATTR.MAINTENANCE_OBJECT_ID(+) AND
        CII.INV_MASTER_ORGANIZATION_ID=HOU.ORGANIZATION_ID AND
        ATTR.ATTRIBUTE_CATEGORY(+)='Data Kendaraan' AND
        MSI.SEGMENT1 LIKE 'AST%' AND
        ATTR.C_ATTRIBUTE11 IS NOT NULL AND
        CII.INSTANCE_NUMBER LIKE '2%'  AND
		ATTR.C_ATTRIBUTE11 like '%".$where."%' $whereArea
		ORDER BY NO_LAMBUNG
 ");

oci_execute($asset_list);
$query = oci_result($asset_list, 1);
if (!$asset_list) {
    $msgError = oci_error($conn);
} else {
    $result = $asset_list;
    while ($row = oci_fetch_row($result)) {
        $record                 = array();
        $headerID               = $row[0];
        $record['DATA_ASSET']   = $row[0];
        $record['DATA_GROUP']   = $row[1];
        $record['DATA_AREA']    = $row[2];
        $record['DATA_PLAT']    = $row[3];
        $record['DATA_LAMBUNG'] = $row[4];
        // $record['DATA_MODEL']   = $row[5];
		$test = $row[5];
		list($record['testing'], $record['testing1'], $record['testing2']) = split('[/.-]', $test);
		$test1 = $record['testing1'];
		list($record['coba1'], $record['DATA_MODEL']) = split('model', $test1);
		$test2 = $record['testing2'];
		list($record['coba2'], $record['DATA_TYPE']) = split(' ', $test2);
        // $record['DATA_TYPE']    = $row[6];
		//Ingat kolom merk = DATA_TYPE
        $record['DATA_BULAN']   = $row[7];
        $record['DATA_TAHUN']   = $row[8];
        $record['DATA_COMPANY'] = $row[9];
        $record['DATA_BARCODE'] = $row[10];
        $record['DATA_ORID']    = $row[11];
        $data[]                 = $record;
    }
}
echo json_encode($data);
?>