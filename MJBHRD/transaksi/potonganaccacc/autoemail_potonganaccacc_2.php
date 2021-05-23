<?php
require('smtpemailattachment.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
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
  
$status = "";
$data = "";
$namaTujuan = "";
$emailTujuan = "";

// if (isset($_POST['hdid']))

if ( isset($_POST['hdid']) && isset($_POST['param_approval']) )
{
	$hdid = $_POST['hdid'];
	$emailJam = date("H");
	
	$vparam_approval = $_POST['param_approval'];
	$vparam_transNo = $_POST['param_transNo']; 
	$vparam_tipe = $_POST['param_tipe'];
	$param_keterangan = $_POST['param_keterangan'];
	
	
	
	$query = "
		SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
		FROM    APPS.PER_PEOPLE_F PPF
		WHERE   PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND     PPF.EFFECTIVE_END_DATE > SYSDATE
		AND     PPF.PERSON_ID = $emp_id
	";
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
    $FinanceName = $row[0];
	$FinanceEmail = $row[1];
	
	
	$query_emp = "
		SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
		FROM    APPS.PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
		WHERE   PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND     PPF.EFFECTIVE_END_DATE > SYSDATE
		AND     PPF.PERSON_ID = MTP.PERSON_ID
		AND		MTP.ID = $hdid
	";
	
	$result_emp = oci_parse( $con, $query_emp );
	oci_execute( $result_emp );
	$row_emp = oci_fetch_row( $result_emp );
	
    $empName = $row_emp[ 0 ];
	$empEmail = $row_emp[ 1 ];
	
	
	
	$query_mgr = "
		SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
		FROM    APPS.PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
		WHERE   PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND     PPF.EFFECTIVE_END_DATE > SYSDATE
		AND     PPF.PERSON_ID = MTP.MANAGER
		AND     MTP.ID = $hdid
	";
	
	$result_mgr = oci_parse( $con, $query_mgr );
	oci_execute( $result_mgr );
	$row_mgr = oci_fetch_row( $result_mgr );
	
	$mgrEmail = $row_mgr[ 1 ];
	
	
	
	$query_Kasir = 	"
					SELECT  LISTAGG( PPF.EMAIL_ADDRESS, ', ' ) WITHIN GROUP ( ORDER BY PPF.EMAIL_ADDRESS )
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
					INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
					INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
					INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
					AND PAF.PRIMARY_FLAG = 'Y'
					AND PJ.name like '%FIN%'
					";
	
	$resultKasir = oci_parse( $con, $query_Kasir );
	oci_execute( $resultKasir );
	$rowKasir = oci_fetch_row( $resultKasir );
	
	
	$Kasir_Emails = $rowKasir[0];
	
	
	$QueryFinance_Approve = oci_parse( $con, "
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
		
	oci_execute( $QueryFinance_Approve );
	$rowFinance = oci_fetch_row( $QueryFinance_Approve );
	$kasir_id_approve = $rowFinance[0];
	

	if ( $kasir_id_approve != '' ) {
		
		$query_Kasir =
		"
			SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
			FROM    APPS.PER_PEOPLE_F PPF
			WHERE   PERSON_ID = $kasir_id_approve
		";
		
		$resultKasirApprove = oci_parse( $con, $query_Kasir );
		oci_execute( $resultKasirApprove );
		$rowKasirApprove = oci_fetch_row( $resultKasirApprove );
		
		$Kasir_Name = $rowKasirApprove[0];
		$Kasir_Email = $rowKasirApprove[1];
		
	}
	
	
	
	if ( $vparam_approval == 'Approved' ) {
	
		$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo";
		
	
		if ( $vparam_tipe == 'PINJAMAN PENGGANTI INVENTARIS' ) {
			
			$bodyEmail = "
Dear $empName,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName telah di-approve oleh Manager Finance.
Terima Kasih.


[Form: Approval Pinjaman Karyawan Finance]


To: $empEmail
CC: $mgrEmail, $FinanceEmail
			";
		
		
		// PINJAMAN PERSONAL
		
		} else {
			
			// Jika TIDAK dilakukan setting ACCOUNTING_ID_ID di MJ_M_APPROVAL_PINJAMAN
			
			if ( $kasir_id_approve == '' ) {
			
				$bodyEmail = "
Dear all,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName telah di-approve oleh Manager Finance.

Mohon untuk melakukan validasi pengajuan $vparam_tipe di atas.
Terima Kasih.


[Form: Approval Pinjaman Karyawan Finance]


To: $Kasir_Emails
CC: $empEmail, $FinanceEmail
				";

			// Jika dilakukan setting ACCOUNTING_ID di MJ_M_APPROVAL_PINJAMAN
			
			} else {
				
				$bodyEmail = "
Dear $Kasir_Name,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName telah di-approve oleh Manager Finance.

Mohon untuk melakukan validasi pengajuan $vparam_tipe di atas.
Terima kasih.


[Form: Approval Pinjaman Karyawan Finance]


To: $Kasir_Email
CC: $empEmail, $FinanceEmail
				";
				
			}
		}		
		
			
	// Jika di-reject
	
	} else {
		
		$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo di-reject";
		$bodyEmail = "
Dear $empName,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName di-reject.

Keterangan reject dari Finance: $param_keterangan

Terima hasih.


[Form: Approval Pinjaman Karyawan Finance]


To: $empEmail
CC: $FinanceEmail
		";
		
	}
	
	// CC: $FinanceEmail, $Kasir_Emails
	
	
	$mail = new PHPMailer();
	$mail->Hostname = '192.168.0.35';
	$mail->Port = 25;
	$mail->Host = "192.168.0.35";
	$mail->SMTPAuth = true;
	$mail->Username = 'autoemail.it@merakjaya.co.id';
	$mail->Password = 'autoemail';
	
	$mail->Mailer = 'smtp';
	$mail->From = "autoemail.it@merakjaya.co.id";
	$mail->FromName = "Auto Email"; 
	$mail->Subject = $subjectEmail;
	$mail->Body = $bodyEmail;
	
	
	
	// Set penerima email saat transaksi disimpan
	
	/* DIPAKAI */
	
	if ( $vparam_approval == 'Approved' ) {
	
		if ( $vparam_tipe == 'PINJAMAN PENGGANTI INVENTARIS' ) {
			
			// To: $empEmail
			// CC: $mgrEmail, $FinanceEmail
			
			$mail->AddAddress( $empEmail );
			
			$mail->addCC( $mgrEmail );
			$mail->addCC( $FinanceEmail );
			
			
		// PINJAMAN PERSONAL
		
		} else {
			
			// Jika TIDAK dilakukan setting ACCOUNTING_ID_ID di MJ_M_APPROVAL_PINJAMAN
			
			if ( $kasir_id_approve == '' ) {
				
				// To: $Kasir_Emails
				// CC: $empEmail, $FinanceEmail
				
				
				$query_Kasir_To = 	"
								SELECT  PPF.EMAIL_ADDRESS
								FROM APPS.PER_PEOPLE_F PPF
								INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
								INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
								INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
								INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
								WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
								AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
								AND PAF.PRIMARY_FLAG = 'Y'
								AND PJ.name like '%FIN%'
								";
				
				$resultKasirTo = oci_parse( $con, $query_Kasir_To );
				oci_execute( $resultKasirTo );
				
				while( $rowKasir = oci_fetch_row( $resultKasirTo ) )
				{
					
					$mail->AddAddress( $rowKasir[0] );
					
				}
				
				$mail->addCC( $empEmail );
				$mail->addCC( $FinanceEmail );
				
				
			// Jika dilakukan setting ACCOUNTING_ID di MJ_M_APPROVAL_PINJAMAN
			
			} else {
				
				// To: $Kasir_Email
				// CC: $empEmail, $FinanceEmail
				
				$mail->AddAddress( $Kasir_Email );
				
				$mail->addCC( $empEmail );
				$mail->addCC( $FinanceEmail );
				
			}
		}
	
	
	// Jika di-reject
	
	} else {
		
		// To: $empEmail
		// CC: $FinanceEmail
		
		$mail->AddAddress( $empEmail );
		
		$mail->addCC( $FinanceEmail );
		
	}
	
	/* DIPAKAI */
	
	
	$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
	$mail->addCC( 'maria.natalia@merakjaya.co.id' );
	$mail->addCC( 'dwatra@merakjaya.co.id' );
	
	
	/*
	$mail->AddAddress($deptManagerEmail);
	$mail->addCC($deptPemohon);
	$mail->addCC($deptPembuat);
	$mail->addCC($deptMgrBaruEmail);
	
	$mail->addCC('maria.natalia@merakjaya.co.id'); 
	$mail->addCC('yuke.indarto@merakjaya.co.id'); 
	$mail->addCC('dwatra@merakjaya.co.id');
	*/

	$success = $mail->Send();
	
	//echo $success;

	
	
}


?>