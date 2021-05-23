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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$kategori = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$Dept = "";
$data = "";
$tingkat = 0;
$count = 0;
$countSpv = 0;
$countMan = 0;
$queryfilter=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunSkr=substr($tglskr, 0, 4);
$bulanSkr=substr($tglskr, 5, 2);
$hariSkr=substr($tglskr, 8, 2);
if (isset($_GET['nama_pem']))
{	
	$nama_pem=$_GET['nama_pem'];
	$bulanSkr = str_replace("0", "", $bulanSkr);
	
						  
	$query = "
	  SELECT DISTINCT
			 MTP.ID,
			 MTP.PERSON_ID,
			 MTP.TIPE,
			 TO_CHAR (MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN,
            MTP.NOMINAL JML_CICILAN_P,
             MTP.JUMLAH_CICILAN_AWAL,
             (MTP.NOMINAL * MTP.JUMLAH_CICILAN_AWAL) NOMINAL,
               (MTP.NOMINAL * MTP.JUMLAH_CICILAN_AWAL)
             - NVL (
                  (SELECT SUM (NOMINAL)
                     FROM MJ_T_PINJAMAN_DETAIL
                    WHERE     MJ_T_PINJAMAN_ID = MTP.ID
                          AND TAHUN <= '$tahunSkr'
                          AND BULAN <= '$bulanSkr' ),
                  0)
                OUTSTANDING,
               ( MTP.JUMLAH_CICILAN_AWAL 
				 - NVL (
					  (SELECT COUNT( * )
						 FROM MJ_T_PINJAMAN_DETAIL
						WHERE     MJ_T_PINJAMAN_ID = MTP.ID
							  AND TAHUN <= '$tahunSkr'
							  AND BULAN <= '$bulanSkr' ),
					  0 )
                ) OUTSTANDING_BLN,
			 DTL.BULAN,
			 TO_CHAR (MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN,
			 MTP.STATUS_DOKUMEN,
			 (SELECT FULL_NAME
				FROM APPS.PER_PEOPLE_F
			   WHERE     EFFECTIVE_END_DATE > SYSDATE
					 AND CURRENT_EMPLOYEE_FLAG = 'Y'
					 AND PERSON_ID =
							(SELECT CREATED_BY
							   FROM MJ_T_APPROVAL
							  WHERE     TRANSAKSI_KODE = 'PINJAMAN'
									AND TRANSAKSI_ID = MTP.ID
									AND TINGKAT = MTP.TINGKAT
									AND ID =
										   (SELECT MAX (ID)
											  FROM MJ_T_APPROVAL
											 WHERE     TRANSAKSI_KODE = 'PINJAMAN'
												   AND TRANSAKSI_ID = MTP.ID
												   AND TINGKAT = MTP.TINGKAT)))
				APP_TERAKHIR,
			 MTP.NOMOR_PINJAMAN,
				DECODE (MTP.START_POTONGAN_BULAN,
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
			 || ' '
			 || MTP.START_POTONGAN_TAHUN
				AS START_POTONGAN
		FROM (SELECT ID,
					 MJ_T_PINJAMAN_ID,
						DECODE (DTLSUB.BULAN,
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
					 || ' '
					 || DTLSUB.TAHUN
						AS BULAN
				FROM MJ.MJ_T_PINJAMAN_DETAIL DTLSUB
			   WHERE TAHUN || LPAD( BULAN, 2, '0' ) = 	
						(	SELECT 	MAX ( TAHUN || LPAD( BULAN, 2, '0' ) )
							FROM 	MJ_T_PINJAMAN_DETAIL
							WHERE 	MJ_T_PINJAMAN_ID = DTLSUB.MJ_T_PINJAMAN_ID
							AND TAHUN <= '$tahunSkr'
							AND BULAN <= '$bulanSkr'
						)
				) DTL,
			 MJ.MJ_T_PINJAMAN MTP,
			 APPS.PER_PEOPLE_F PPF
	   WHERE     MTP.PERSON_ID = PPF.PERSON_ID
			 AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
			 AND PPF.EFFECTIVE_END_DATE > SYSDATE
			 AND MTP.PERSON_ID = $nama_pem
			 AND DTL.MJ_T_PINJAMAN_ID(+) = MTP.ID
	ORDER BY MTP.ID
	";
	
	// echo $query;
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_JENIS']=$row[2];
		$record['DATA_TGL']=$row[3];
		$record['DATA_CICILAN']=number_format($row[4], 2, ',', '.');
		$record['DATA_JML_C']=$row[5];
		$record['DATA_JML_P']=number_format($row[6], 2, ',', '.');
		$record['DATA_OUTSTANDING_ASLI']=$row[7];
		$record['DATA_OUTSTANDING_RP']=number_format($row[7], 2, ',', '.');
		$record['DATA_OUTSTANDING_BLN']=$row[8];
		$record['DATA_CICILAN_BLN']=$row[9];
		$record['DATA_STATUS']=$row[11];
		$record['DATA_APP_TERAKHIR']=$row[12];
		$record['DATA_NO_PINJAM']=$row[13];
		$record['DATA_START_POTONGAN']=$row[14];
		
		$data[]=$record;
		$countSpv++;
	}

	echo json_encode($data); 
	



/*

			  AND TAHUN <= '$tahunSkr'
			  AND BULAN <= '$bulanSkr'),
			  
			 MTP.NOMINAL JML_CICILAN_P,
			 MTP.JUMLAH_CICILAN,
			 (MTP.NOMINAL * MTP.JUMLAH_CICILAN) NOMINAL,
			   (MTP.NOMINAL * MTP.JUMLAH_CICILAN)
			 - NVL (
				  (SELECT SUM (NOMINAL)
					 FROM MJ_T_PINJAMAN_DETAIL
					WHERE     MJ_T_PINJAMAN_ID = MTP.ID
						  AND TAHUN <= '$tahunSkr'
						  AND BULAN <= '$bulanSkr'),
				  0)
				OUTSTANDING,
			   MTP.JUMLAH_CICILAN
			 - (SELECT COUNT (-1)
				  FROM MJ_T_PINJAMAN_DETAIL
				 WHERE     MJ_T_PINJAMAN_ID = MTP.ID
					   AND TAHUN <= '$tahunSkr'
					   AND BULAN <= '$bulanSkr')
				OUTSTANDING_BLN,
*/

	
	
} 


?>