<?PHP
//require('smtpemailattachment.php');
include '../../main/koneksi.php';
// date_default_timezone_set("Asia/Jakarta");

// deklarasi variable dan session
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

 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];

		$QueryKasir = oci_parse( $con, "
			SELECT  KASIR_ID
			FROM    
			(				
				(
					SELECT DISTINCT MTP.ID
							, MTP.NOMOR_PINJAMAN
							, PPF.FULL_NAME
							, MMP.KASIR_ID
							, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
							, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
							, MTP.JUMLAH_CICILAN
							, MTP.TUJUAN_PINJAMAN
							, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
							, MTP.STATUS
							, MTP.PERSON_ID
							, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL_TF
							, MTP.TIPE
							, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
													7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
										||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
							, D.ORGANIZATION_ID
					FROM MJ_T_PINJAMAN MTP
					INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID
					INNER JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
					INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
					INNER JOIN MJ.MJ_M_APPROVAL_PINJAMAN MMP ON MMP.PERUSAHAAN_ID = D.ORGANIZATION_ID
					WHERE MTP.STATUS_DOKUMEN = 'Approved'
					AND D.PRIMARY_FLAG = 'Y'
					AND D.EFFECTIVE_END_DATE > SYSDATE
					AND MTP.ID = $hdid
					AND MTP.TINGKAT = 4
					AND MTP.STATUS = 1
					AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
					AND MTP.TIPE = 'PINJAMAN PERSONAL'
					AND MMP.TIPE = 'Pinjaman'
					AND MMP.STATUS = 'A'
				)
				UNION
				(
					SELECT DISTINCT MTP.ID
							, MTP.NOMOR_PINJAMAN
							, PPF.FULL_NAME
							, NULL KASIR_ID
							, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
							, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
							, MTP.JUMLAH_CICILAN
							, MTP.TUJUAN_PINJAMAN
							, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
							, MTP.STATUS
							, MTP.PERSON_ID
							, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL_TF
							, MTP.TIPE
							, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
													7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
										||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
							, D.ORGANIZATION_ID
					FROM MJ_T_PINJAMAN MTP
					INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID
					INNER JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
					INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
					WHERE MTP.STATUS_DOKUMEN = 'Approved'
					AND D.PRIMARY_FLAG = 'Y'
					AND D.EFFECTIVE_END_DATE > SYSDATE
					AND MTP.TINGKAT = 4
					AND MTP.STATUS = 1
					AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
					AND MTP.TIPE = 'PINJAMAN PERSONAL'
					AND MTP.ID = $hdid
					AND NOT EXISTS (
						SELECT  1
						FROM    MJ_M_APPROVAL_PINJAMAN
						WHERE   PERUSAHAAN_ID = D.ORGANIZATION_ID
						AND     TIPE = 'Pinjaman'
						AND     STATUS = 'A'
						)
				)
			)
			");
		oci_execute( $QueryKasir );
		$rowKasir = oci_fetch_row( $QueryKasir );
		$kasir_id = $rowKasir[0];
		
		if ( $kasir_id == '' ) {

			$hasil_cek = "sukses";
			
		} else {
			
			if ( $kasir_id == $emp_id ) {
				
				$hasil_cek = "sukses";
				
			} else {
				
				$hasil_cek = "gagal";
				
			}
			
		}
		
	}
	
	
	$result = array( 'hasil' => $hasil_cek );
	
	echo json_encode($result);



?>