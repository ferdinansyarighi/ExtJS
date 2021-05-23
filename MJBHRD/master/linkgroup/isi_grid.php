<?PHP
include '../../main/koneksi.php';
$union='';
$filter='';
//$queryfilter=' AND MGE.ID_ELEMENT_GAJI_MINGGUAN IS NULL ';

//Untuk insert linkgroup (ketika set nama group element)
if (isset($_GET['dtid']))
{	
	$dtid = $_GET['dtid'];
	$filter = " AND nvl(MGE.ID_GROUP, 0) = $dtid";
	
	$result = oci_parse($con, "SELECT EGM.ID, NAMA_ELEMENT, KOMPONEN, TYPE_ELEMENT, 0 VALUE
	, CASE WHEN MGE.DEFAULT_VALUE <> 0 THEN MGE.DEFAULT_VALUE
        ELSE 0 END DEFAULT_VALUE
	, MGE.SATUAN
    , NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
    , NVL(TO_CHAR(MGE.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MGE.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
    , MGE.ID
    , CASE WHEN MGE.DEFAULT_VALUE != 0 THEN 1
        ELSE 0 
      END CEK
    FROM MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM
    LEFT JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGE ON EGM.ID = MGE.ID_ELEMENT
    INNER JOIN MJ.MJ_M_USER MMU ON MGE.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON MGE.LAST_UPDATED_BY = MMU2.ID
	WHERE 1=1 and EGM.STATUS = 'Y' $filter
	order by komponen, nama_element
	");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA']=$row[1];
		$record['DATA_KOMPONEN']=$row[2];
		$record['DATA_TYPE']=$row[3];
		$record['DATA_VALUE']=$row[5];
		$record['DATA_DEFAULT_VALUE']=$row[4];
		$record['DATA_SATUAN']=$row[6];
		$record['DATA_OLEH']=$row[7];
		$record['DATA_TGL']=$row[8];
		$record['DATA_ID_GROUP_DETAIL']=$row[9];
		$record['DATA_CEK']=$row[10];
		$data[]=$record;
	}
}

//Untuk update data linkgroup yg sudah ada
if (isset($_GET['hdid']))
{	
	$hdid = $_GET['hdid'];
	$result = oci_parse($con, "SELECT DISTINCT EGM.ID, NAMA_ELEMENT, KOMPONEN, TYPE_ELEMENT
    , CASE WHEN MGE.DEFAULT_VALUE <> 0 THEN MGE.DEFAULT_VALUE
           ELSE 0 END VALUE
    , MGE.DEFAULT_VALUE
    , MGE.SATUAN
    , NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
    , NVL(NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')), TO_CHAR(MGE.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
    , MGE.ID
    , CASE WHEN MGE.DEFAULT_VALUE != 0 THEN 1
        ELSE 0 
      END CEK
    FROM MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM
    LEFT JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGE ON EGM.ID = MGE.ID_ELEMENT
    INNER JOIN MJ.MJ_M_GROUP_ELEMENT MG ON MGE.ID_GROUP = MG.ID
    INNER JOIN MJ.MJ_M_USER MMU ON MGE.CREATED_BY = MMU.ID
    LEFT JOIN MJ.MJ_M_USER MMU2 ON MGE.LAST_UPDATED_BY = MMU2.ID
    LEFT JOIN MJ.MJ_M_LINK_GROUP MLG ON MG.ID = MLG.ID_GROUP AND MLG.ID = $hdid
    LEFT JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MGE.ID = MLGD.ID_GROUP_DETAIL AND MLG.ID = MLGD.ID_LINK_GROUP
    WHERE 1=1 and EGM.STATUS = 'Y' 
    AND MGE.ID_GROUP =  (select id_group from mj.MJ_M_LINK_GROUP where id = $hdid) 
    AND  MLGD.CREATED_DATE IS NULL   
    UNION
select EGM.ID, EGM.NAMA_ELEMENT, EGM.KOMPONEN, EGM.TYPE_ELEMENT
        , CASE WHEN MGED.DEFAULT_VALUE <> 0 THEN MGED.DEFAULT_VALUE
                ELSE MLGD.VALUE END VALUE
        , MGED.DEFAULT_VALUE, MGED.SATUAN
        , NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
        , NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
        , MGED.ID
        , CASE WHEN MGED.DEFAULT_VALUE != 0 THEN 1
            ELSE 0 
          END CEK
        FROM MJ.MJ_M_LINK_GROUP MLG
        INNER JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MLG.ID = MLGD.ID_LINK_GROUP
        INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MLGD.ID_ELEMENT = EGM.ID
        INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGED ON MLGD.ID_GROUP_DETAIL = MGED.ID
        INNER JOIN MJ.MJ_M_USER MMU ON MGED.CREATED_BY = MMU.ID
        LEFT JOIN MJ.MJ_M_USER MMU2 ON MGED.LAST_UPDATED_BY = MMU2.ID
    WHERE 1=1 and EGM.STATUS = 'Y' 
    AND NVL(MLGD.ID_LINK_GROUP, 0) = $hdid
    order by komponen, nama_element
	"); 
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_NAMA']=$row[1];
		$record['DATA_KOMPONEN']=$row[2];
		$record['DATA_TYPE']=$row[3];
		$record['DATA_VALUE']=$row[4];
		$record['DATA_DEFAULT_VALUE']=$row[5];
		$record['DATA_SATUAN']=$row[6];
		$record['DATA_OLEH']=$row[7];
		$record['DATA_TGL']=$row[8];
		$record['DATA_ID_GROUP_DETAIL']=$row[9];
		$record['DATA_CEK']=$row[10];
		$data[]=$record;
	}
}

//BACKUP SEBELUM ERROR ERJ ADD BPJS KETENAGAKERJAAN DRI GROUP ELEMENT TIDAK MUNCUL
// if (isset($_GET['hdid']))
// {	
	// $hdid = $_GET['hdid'];
	// $filter = " AND NVL(MLGD.ID_LINK_GROUP, 0) = $hdid";
	// $result = oci_parse($con, "SELECT DISTINCT EGM.ID, NAMA_ELEMENT, KOMPONEN, TYPE_ELEMENT
    // , CASE WHEN MGE.DEFAULT_VALUE <> 0 THEN MGE.DEFAULT_VALUE
           // ELSE MLGD.VALUE END VALUE
    // , MGE.DEFAULT_VALUE
    // , MGE.SATUAN
    // , NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
    // , NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
    // , MGE.ID
    // , CASE WHEN MGE.DEFAULT_VALUE != 0 THEN 1
        // ELSE 0 
      // END CEK
    // FROM MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM
    // LEFT JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGE ON EGM.ID = MGE.ID_ELEMENT
    // INNER JOIN MJ.MJ_M_GROUP_ELEMENT MG ON MGE.ID_GROUP = MG.ID
    // INNER JOIN MJ.MJ_M_USER MMU ON MGE.CREATED_BY = MMU.ID
    // LEFT JOIN MJ.MJ_M_USER MMU2 ON MGE.LAST_UPDATED_BY = MMU2.ID
    // LEFT JOIN MJ.MJ_M_LINK_GROUP MLG ON MG.ID = MLG.ID_GROUP
    // LEFT JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MGE.ID = MLGD.ID_GROUP_DETAIL
    // WHERE 1=1 and EGM.STATUS = 'Y' $filter
    // UNION
// select EGM.ID, EGM.NAMA_ELEMENT, EGM.KOMPONEN, EGM.TYPE_ELEMENT
        // , CASE WHEN MGED.DEFAULT_VALUE <> 0 THEN MGED.DEFAULT_VALUE
                // ELSE MLGD.VALUE END VALUE
        // , MGED.DEFAULT_VALUE, MGED.SATUAN
        // , NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
        // , NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
        // , MGED.ID
        // , CASE WHEN MGED.DEFAULT_VALUE != 0 THEN 1
            // ELSE 0 
          // END CEK
        // FROM MJ.MJ_M_LINK_GROUP MLG
        // INNER JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MLG.ID = MLGD.ID_LINK_GROUP
        // INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MLGD.ID_ELEMENT = EGM.ID
        // INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGED ON MLGD.ID_GROUP_DETAIL = MGED.ID
        // INNER JOIN MJ.MJ_M_USER MMU ON MGED.CREATED_BY = MMU.ID
        // LEFT JOIN MJ.MJ_M_USER MMU2 ON MGED.LAST_UPDATED_BY = MMU2.ID
    // WHERE 1=1 and EGM.STATUS = 'Y' $filter
    // order by komponen, nama_element
	// "); 
	
	// //BACKUP SEBELUM BPJS KESEHATAN
	 // /* $result = oci_parse($con, "select EGM.ID, EGM.NAMA_ELEMENT, EGM.KOMPONEN, EGM.TYPE_ELEMENT
		// , CASE WHEN MGED.DEFAULT_VALUE <> 0 THEN MGED.DEFAULT_VALUE
                // ELSE MLGD.VALUE END VALUE
		// , MGED.DEFAULT_VALUE, MGED.SATUAN
        // , NVL(MMU2.NAMA_USER, MMU.NAMA_USER) OLEH
        // , NVL(TO_CHAR(MLGD.LAST_UPDATED_DATE, 'DD-MON-YYYY HH24:Mi:ss'), TO_CHAR(MLGD.CREATED_DATE, 'DD-MON-YYYY HH24:Mi:ss')) TGL
        // , MGED.ID
        // , CASE WHEN MGED.DEFAULT_VALUE != 0 THEN 1
            // ELSE 0 
          // END CEK
        // FROM MJ.MJ_M_LINK_GROUP MLG
        // INNER JOIN MJ.MJ_M_LINK_GROUP_DETAIL MLGD ON MLG.ID = MLGD.ID_LINK_GROUP
        // INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON MLGD.ID_ELEMENT = EGM.ID
        // INNER JOIN MJ.MJ_M_GROUP_ELEMENT_DETAIL MGED ON MLGD.ID_GROUP_DETAIL = MGED.ID
        // INNER JOIN MJ.MJ_M_USER MMU ON MLGD.CREATED_BY = MMU.ID
        // LEFT JOIN MJ.MJ_M_USER MMU2 ON MLGD.LAST_UPDATED_BY = MMU2.ID
    // WHERE 1=1 and EGM.STATUS = 'Y' $filter 
    // order by komponen, nama_element
	// ");  */
	
	// oci_execute($result);
	// while($row = oci_fetch_row($result))
	// {
		// $record = array();
		// $record['HD_ID']=$row[0];
		// $record['DATA_NAMA']=$row[1];
		// $record['DATA_KOMPONEN']=$row[2];
		// $record['DATA_TYPE']=$row[3];
		// $record['DATA_VALUE']=$row[4];
		// $record['DATA_DEFAULT_VALUE']=$row[5];
		// $record['DATA_SATUAN']=$row[6];
		// $record['DATA_OLEH']=$row[7];
		// $record['DATA_TGL']=$row[8];
		// $record['DATA_ID_GROUP_DETAIL']=$row[9];
		// $record['DATA_CEK']=$row[10];
		// $data[]=$record;
	// }
// }

	
	
echo json_encode($data); 
?>