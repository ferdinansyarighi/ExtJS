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
	
	// $vparam_manager = $_POST['param_manager'];
	// $vparam_karyawan = $_POST['param_karyawan'];
	
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
	
    $DirName = $row[0];
	$DirEmail = $row[1];
	

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
	
	// $ManagerHrdName = 'Anggo, Mr. Freddy FRG';
	// $ManagerHrdEmail = 'freddy.anggo@merakjaya.co.id';
	// Dear $ManagerHrdName,
	// To: $ManagerHrdEmail

	$query_Finance = 	"
					SELECT  LISTAGG( PPF.FULL_NAME, ',   ' ) WITHIN GROUP ( ORDER BY PPF.FULL_NAME ),
							LISTAGG( PPF.EMAIL_ADDRESS, ', ' ) WITHIN GROUP ( ORDER BY PPF.EMAIL_ADDRESS )
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
					INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
					INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
					INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
					AND PAF.PRIMARY_FLAG = 'Y'
					AND PJ.name LIKE '%FIN%' AND  UPPER( PG.name ) LIKE '%MANAGER%'
					";
	
	$resultFinance = oci_parse( $con, $query_Finance );
	oci_execute( $resultFinance );
	$rowFinance = oci_fetch_row( $resultFinance );
	
	$Fin_Mgr_Names = $rowFinance[0];
	$Fin_Mgr_Emails = $rowFinance[1];
	


	$QueryDireksi_Approve = oci_parse( $con, "
		SELECT  ACCOUNTING_ID
		FROM    
		(
			(
				SELECT DISTINCT MTP.ID
						, MTP.NOMOR_PINJAMAN
						, PPF.FULL_NAME
						, D.ORGANIZATION_ID
						, MMP.ACCOUNTING_ID
						, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
						, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
						, MTP.JUMLAH_CICILAN
						, MTP.TUJUAN_PINJAMAN
						, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
						, MTP.STATUS
						, MTP.PERSON_ID
						, MTP.NOMINAL NC
						, MTP.KETERANGAN_MGR
						, MTP.KETERANGAN
						, MTP.KETERANGAN_DIR
						, MTP.TIPE
						, PPF2.FULL_NAME
						, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
												7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
									||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
				FROM MJ.MJ_T_PINJAMAN MTP
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				INNER JOIN MJ.MJ_M_APPROVAL_PINJAMAN MMP ON MMP.PERUSAHAAN_ID = D.ORGANIZATION_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
				AND MTP.ID = $hdid
				AND MTP.TINGKAT = 3
				AND MTP.STATUS = 1
				AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
				AND MMP.TIPE = 'Pinjaman'
				AND MMP.STATUS = 'A'
			)
			UNION
			(
				SELECT DISTINCT MTP.ID
						, MTP.NOMOR_PINJAMAN
						, PPF.FULL_NAME
						, D.ORGANIZATION_ID
						, NULL ACCOUNTING_ID
						, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
						, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
						, MTP.JUMLAH_CICILAN
						, MTP.TUJUAN_PINJAMAN
						, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
						, MTP.STATUS
						, MTP.PERSON_ID
						, MTP.NOMINAL NC
						, MTP.KETERANGAN_MGR
						, MTP.KETERANGAN
						, MTP.KETERANGAN_DIR
						, MTP.TIPE
						, PPF2.FULL_NAME
						, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
												7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
									||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
				FROM MJ.MJ_T_PINJAMAN MTP
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
				AND MTP.TINGKAT = 3
				AND MTP.STATUS = 1
				AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
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
	oci_execute( $QueryDireksi_Approve );
	$rowDireksiApprove = oci_fetch_row( $QueryDireksi_Approve );
	$finance_id_approve = $rowDireksiApprove[0];
	
	

	if ( $finance_id_approve != '' ) {
		
		$query_Finance_Mgr =
		"
			SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
			FROM    APPS.PER_PEOPLE_F PPF
			WHERE   PERSON_ID = $finance_id_approve
		";
		
		$resultFinanceMgr = oci_parse( $con, $query_Finance_Mgr );
		oci_execute( $resultFinanceMgr );
		$rowFinanceMgr = oci_fetch_row( $resultFinanceMgr );
		
		$Finance_Mgr_Name = $rowFinanceMgr[0];
		$Finance_Mgr_Email = $rowFinanceMgr[1];
		
	}
	
	
	
	$QueryDireksi_Reject = oci_parse( $con, "
		SELECT  ACCOUNTING_ID
		FROM    
		(
			(
				SELECT DISTINCT MTP.ID
						, MTP.NOMOR_PINJAMAN
						, PPF.FULL_NAME
						, D.ORGANIZATION_ID
						, MMP.ACCOUNTING_ID
						, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
						, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
						, MTP.JUMLAH_CICILAN
						, MTP.TUJUAN_PINJAMAN
						, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
						, MTP.STATUS
						, MTP.PERSON_ID
						, MTP.NOMINAL NC
						, MTP.KETERANGAN_MGR
						, MTP.KETERANGAN
						, MTP.KETERANGAN_DIR
						, MTP.TIPE
						, PPF2.FULL_NAME
						, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
												7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
									||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
				FROM MJ.MJ_T_PINJAMAN MTP
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				INNER JOIN MJ.MJ_M_APPROVAL_PINJAMAN MMP ON MMP.PERUSAHAAN_ID = D.ORGANIZATION_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
				AND MTP.ID = $hdid
				AND MTP.TINGKAT IN ( 0, 2 ) 
				AND MTP.STATUS = 1
				AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
				AND MMP.TIPE = 'Pinjaman'
				AND MMP.STATUS = 'A'
			)
			UNION
			(
				SELECT DISTINCT MTP.ID
						, MTP.NOMOR_PINJAMAN
						, PPF.FULL_NAME
						, D.ORGANIZATION_ID
						, NULL ACCOUNTING_ID
						, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
						, MTP.NOMINAL * MTP.JUMLAH_CICILAN NOMINAL
						, MTP.JUMLAH_CICILAN
						, MTP.TUJUAN_PINJAMAN
						, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
						, MTP.STATUS
						, MTP.PERSON_ID
						, MTP.NOMINAL NC
						, MTP.KETERANGAN_MGR
						, MTP.KETERANGAN
						, MTP.KETERANGAN_DIR
						, MTP.TIPE
						, PPF2.FULL_NAME
						, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
												7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
									||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
				FROM MJ.MJ_T_PINJAMAN MTP
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
				AND MTP.TINGKAT IN ( 0, 2 ) 
				AND MTP.STATUS = 1
				AND MTP.JENIS_PINJAMAN = 'PINJAMAN'
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
	oci_execute( $QueryDireksi_Reject );
	$rowDireksiReject = oci_fetch_row( $QueryDireksi_Reject );
	$finance_id_reject = $rowDireksiReject[0];
	

	if ( $finance_id_reject != '' ) {
		
		$query_Finance_Mgr =
		"
			SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
			FROM    APPS.PER_PEOPLE_F PPF
			WHERE   PERSON_ID = $finance_id_reject
		";
		
		$resultFinanceMgr = oci_parse( $con, $query_Finance_Mgr );
		oci_execute( $resultFinanceMgr );
		$rowFinanceMgr = oci_fetch_row( $resultFinanceMgr );
		
		$Finance_Mgr_Name = $rowFinanceMgr[0];
		$Finance_Mgr_Email = $rowFinanceMgr[1];
		
	}
	
	
	
	if ( $vparam_approval == 'Approved' ) {
	
		$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo";
		
		
		// Jika TIDAK dilakukan setting ACCOUNTING_ID_ID di MJ_M_APPROVAL_PINJAMAN
		
		if ( $finance_id_approve == '' ) {
		
		$bodyEmail = "
Dear $Fin_Mgr_Names,

Telah dibuat pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName.

Mohon untuk melakukan approval pengajuan $vparam_tipe di atas.
Terima Kasih.


[Form: Approval Pinjaman Karyawan Direksi]


To: $Fin_Mgr_Emails
CC: $empEmail, $DirEmail
		";
		
		
		// Jika dilakukan setting ACCOUNTING_ID di MJ_M_APPROVAL_PINJAMAN
		
		} else {
		
		$bodyEmail = "
Dear $Finance_Mgr_Name,

Telah dibuat pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName.

Mohon untuk melakukan approval pengajuan $vparam_tipe di atas.
Terima kasih.


[Form: Approval Pinjaman Karyawan Direksi]


To: $Finance_Mgr_Email
CC: $empEmail, $DirEmail
		";
		
		}
				
	
	// Jika di-reject
	
	} else {
		
		$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo di-reject";
		$bodyEmail = "
Dear $empName,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName di-reject.

Keterangan reject dari direksi: $param_keterangan

Terima kasih.


[Form: Approval Pinjaman Karyawan Direksi]


To: $empEmail 
CC: $DirEmail, $ManagerHrdEmail
		";
		
	}


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



	if ( $vparam_approval == 'Approved' ) {
		
		
		// Jika TIDAK dilakukan setting ACCOUNTING_ID_ID di MJ_M_APPROVAL_PINJAMAN
		
		if ( $finance_id_approve == '' ) {
			
			// To: $Fin_Mgr_Emails
			// CC: $empEmail, $DirEmail
			
		
			$query_Finance_To = 	"
				SELECT  PPF.EMAIL_ADDRESS
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
				INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
				INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
				INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
				WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
				AND PAF.PRIMARY_FLAG = 'Y'
				AND PJ.name LIKE '%FIN%' AND  UPPER( PG.name ) LIKE '%MANAGER%'
				AND PPF.EMAIL_ADDRESS IS NOT NULL
							";
			
			$resultFinanceTo = oci_parse( $con, $query_Finance_To );
			oci_execute( $resultFinanceTo );
			
			while( $rowFinance = oci_fetch_row( $resultFinanceTo ) )
			{
				
				$mail->AddAddress( $rowFinance[0] );
				
			}
		
			
			// $mail->AddAddress( $Fin_Mgr_Emails );
			
			$mail->addCC( $empEmail );
			$mail->addCC( $DirEmail );
			
			
		// Jika dilakukan setting ACCOUNTING_ID di MJ_M_APPROVAL_PINJAMAN
		
		} else {
			
			// To: $Finance_Mgr_Email
			// CC: $empEmail, $DirEmail
			
			$mail->AddAddress( $Finance_Mgr_Email );
			$mail->addCC( $empEmail );
			$mail->addCC( $DirEmail );
			
		}
				
	
	// Jika di-reject
	
	} else {
		
		// To: $empEmail 
		// CC: $DirEmail, $ManagerHrdEmail
		
		$mail->AddAddress( $empEmail );
		$mail->addCC( $DirEmail );
		$mail->addCC( $ManagerHrdEmail );
		
	}
	
	
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