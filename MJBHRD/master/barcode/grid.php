<?php
include '../../main/koneksi.php';


$result = mysqli_query($con, "SELECT * FROM kendaraan ORDER BY no_lambung");
while ($row = mysqli_fetch_row($result)) {
    $record                     = array();
    $record['HD_ID']            = $row[0];
    $record['DATA_ASSET_K']     = $row[1];
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
				CII.INSTANCE_NUMBER = '".$record['DATA_ASSET_K']."'
	 ");
	oci_execute($sj);
	$rowOr = oci_fetch_row($sj);
	$record['DATA_AREA'] = $rowOr[0];
    $record['DATA_IMEI']        = $row[2];
    $record['DATA_HP']          = $row[3];
    $record['DATA_GROUP_K']     = $row[4];
    $record['DATA_AREA_K']      = $row[5];
    $record['DATA_PLAT_K']      = $row[6];
    $record['DATA_LAMBUNG_K']   = $row[7];
    $record['DATA_BULAN_K']     = $row[8];
    $record['DATA_TAHUN_K']     = $row[9];
    $record['DATA_MODEL_K']     = $row[10];
    $record['DATA_TYPE_K']      = $row[11];
    $record['DATA_COMPANY_K']   = $row[12];
    $record['DATA_OWNER']       = $row[13];
    $record['DATA_OWNER_ID']    = $row[14];
    $record['DATA_DRIVER']      = $row[15];
    $record['DATA_TIMEZONE']    = $row[16];
    $record['DATA_STATUS']      = $row[17];
    $record['DATA_UPDATEDTIME'] = $row[20];
    $record['DATA_UPDATEDBY']   = $row[21];
	$record['DATA_SN']  		= $row[22];
    $data[]                     = $record;
}
//print_r($data[];exit;
echo json_encode($data);
?>