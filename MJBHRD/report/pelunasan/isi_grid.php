<?php
include '../../main/koneksi.php';
session_start();
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
  }
  
$perusahaan = "";
$dept = "";
$tipe = "";
$nomor = "";
$nama = "";
$count=0;

$queryfilter=""; 

	
	$perusahaan = $_GET['perusahaan'];
	$dept = $_GET['dept'];
	$tipe = $_GET['tipe'];
	$nomor = $_GET['nomor'];
	$nama = $_GET['nama'];
	
	if ($perusahaan!=''){
		$queryfilter .=" AND PAF.ORGANIZATION_ID = $perusahaan ";
	}
	
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
	
	if ($tipe!='- Pilih -'){
		// $queryfilter .=" AND MTPP.TIPE_PELUNASAN = '$tipe' ";
		$queryfilter .=" AND MTPP.TIPE_PELUNASAN LIKE '%$tipe%' ";
	}
	
	if ($nomor!=''){
		$queryfilter .=" AND MTPP.NO_PELUNASAN LIKE '%$nomor%' ";
	}
	
	if ($nama!=''){
		$queryfilter .=" AND MTPP.PERSON_ID = $nama ";
	} 
	
	if ( isset( $_GET['param_tglfrom'] ) && isset( $_GET['param_tglto'] ) )
	{
		$v_tglfrom = $_GET[ 'param_tglfrom' ];
		$v_tglto = $_GET[ 'param_tglto' ];
		
		if( $v_tglfrom != '' && $v_tglto != '' ) {
			
			$queryfilter .= " AND MTPP.TGL_PELUNASAN BETWEEN TO_DATE( '$v_tglfrom', 'DD/MM/YY' ) AND TO_DATE( '$v_tglto', 'DD/MM/YY' )";
			
		}
	}
	
	
/* $queryAssignLogin = "SELECT ASSIGNMENT_ID 
FROM APPS.PER_ASSIGNMENTS_F
WHERE PERSON_ID = $emp_id AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
$resultAssignLogin = oci_parse($con,$queryAssignLogin);
oci_execute($resultAssignLogin);
$rowAssignLogin = oci_fetch_row($resultAssignLogin);
$assignment_id_login = $rowAssignLogin[0];  */

$query = "
SELECT 	MTP.ID, MTPP.NO_PELUNASAN, MTPP.TIPE_PELUNASAN, MTPP.TGL_PELUNASAN
		, MTP.NOMOR_PINJAMAN, MTP.TIPE TIPE_PINJAMAN, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'DD-MON-YYYY') TANGGAL_PINJAMAN
		, TO_CHAR(MTA.CREATED_DATE, 'DD-MON-YYYY') TGL_VALIDASI
		, MTP.JUMLAH_PINJAMAN
		, MTP.JUMLAH_CICILAN_AWAL JUMLAH_CICILAN
		, MTP.NOMINAL NOMINAL_CICILAN
        , ( MTP.NOMINAL * MTP.JUMLAH_CICILAN_AWAL )
             - NVL (
                  (SELECT   SUM (NOMINAL)
                     FROM   MJ_T_PINJAMAN_DETAIL
                    WHERE   MJ_T_PINJAMAN_ID = MTP.ID
                    AND     SOURCE = 'SCHEDULER'),
                  0)
                OUTSTANDING
        , (
                DECODE ( MTPD.BULAN,
                        1, 'Januari',
                        2, 'Februari',
                        3, 'Maret',
                        4, 'April',
                        5, 'Mei',
                        6, 'Juni',
                        7, 'Juli',
                        8, 'Agustus',
                        9, 'September',
                        10, 'Oktober',
                        11, 'November',
                        12, 'Desember')
                ||' '|| MTPD.TAHUN 
        ) CICILAN_TERAKHIR
FROM MJ.MJ_T_PELUNASAN_PINJAMAN MTPP
INNER JOIN MJ.MJ_T_PELUNASAN_PINJAMAN_DT MTPPD ON MTPP.ID = MTPPD.HDID
INNER JOIN MJ.MJ_T_PINJAMAN MTP ON MTPPD.ID_PINJAMAN = MTP.ID
LEFT JOIN (
        SELECT MAX(ID) ID, TRANSAKSI_ID FROM
        MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN'
        GROUP BY TRANSAKSI_ID
    ) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'
LEFT JOIN (
        SELECT MAX(ID) ID, MJ_T_PINJAMAN_ID FROM
        MJ.MJ_T_PINJAMAN_DETAIL WHERE STATUS = 1
        GROUP BY MJ_T_PINJAMAN_ID 
    ) MTPD2 ON MTPD2.MJ_T_PINJAMAN_ID = MTP.ID  
LEFT JOIN MJ.MJ_T_PINJAMAN_DETAIL MTPD ON MTPD.ID = MTPD2.ID
INNER JOIN PER_ASSIGNMENTS_F PAF ON MTPP.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
WHERE 1=1 
AND ((MTP.TIPE = 'PINJAMAN PERSONAL' and MTP.STATUS_DOKUMEN = 'Validate') 
or (MTP.TIPE = 'PINJAMAN PENGGANTI INVENTARIS' and MTP.STATUS_DOKUMEN = 'Approved'))
$queryfilter
ORDER BY MTPP.ID
";

// echo $query;

$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	//$TransID=$row[0];
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_PELUNASAN']=$row[1];
	$record['DATA_TIPE_PELUNASAN']=$row[2];
	$record['DATA_TGL_PELUNASAN']=$row[3];
	$record['DATA_NO_PINJAMAN']=$row[4];
	$record['DATA_TIPE_PINJAMAN']=$row[5];
	$record['DATA_TGL_PINJAMAN']=$row[6];
	$record['DATA_TGL_VALIDASI']=$row[7];
	$record['DATA_JUMLAH_PINJAMAN']=$row[8];
	$record['DATA_JUMLAH_CICILAN']=$row[9];
	$record['DATA_NOMINAL_CICILAN']=$row[10];
	$record['DATA_OUTSTANDING']=$row[11];
	$record['DATA_CICILAN_TERAKHIR']=$row[12];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>