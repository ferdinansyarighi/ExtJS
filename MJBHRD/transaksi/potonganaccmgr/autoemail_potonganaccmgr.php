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
	
	// AND     PPF.PERSON_ID = $vparam_manager
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
	$managerName = $row[0];
	$managerEmail = $row[1];
	
	
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
	
	
	// $MgrFinanceName = 'Agustin, Mrs. Neneng NNG';
	// $MgrFinanceEmail = 'neneng.nng@merakjaya.co.id';
	// Dear $MgrFinanceName,
	// To: $MgrFinanceEmail
	

	$query_HRD = 	"
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
					AND PJ.name LIKE '%HRD%' AND  UPPER( PG.name ) LIKE '%MANAGER%'
					";
	
	$resultHrd = oci_parse( $con,$query_HRD );
	oci_execute( $resultHrd );
	$rowHrd = oci_fetch_row( $resultHrd );
	
	$HRD_Mgr_Names = $rowHrd[0];
	$HRD_Mgr_Emails = $rowHrd[1];
	
	
	
	$query_HRD_ID_Reject = 	
		"
		SELECT  HRD_ID
		FROM    
		(
			(
				SELECT DISTINCT MTP.ID
						, MTP.NOMOR_PINJAMAN
						, PPF.FULL_NAME
						, D.ORGANIZATION_ID
						, MMP.HRD_ID
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
				LEFT JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				INNER JOIN MJ.MJ_M_APPROVAL_PINJAMAN MMP ON MMP.PERUSAHAAN_ID = D.ORGANIZATION_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
				AND MTP.ID = $hdid
                AND MTP.TINGKAT = 0
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
						, NULL HRD_ID
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
				LEFT JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
                AND MTP.TINGKAT = 0
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
	";
	
	$resultHrdId_Reject = oci_parse( $con,$query_HRD_ID_Reject );
	oci_execute( $resultHrdId_Reject );
	$rowHrdId_Reject = oci_fetch_row( $resultHrdId_Reject );
	
	$HRD_Id_Check_Reject = $rowHrdId_Reject[0];
	

	
	$query_HRD_ID_Approve = 	
		"
		SELECT  HRD_ID
		FROM    
		(
			(
				SELECT DISTINCT MTP.ID
						, MTP.NOMOR_PINJAMAN
						, PPF.FULL_NAME
						, D.ORGANIZATION_ID
						, MMP.HRD_ID
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
				LEFT JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				INNER JOIN MJ.MJ_M_APPROVAL_PINJAMAN MMP ON MMP.PERUSAHAAN_ID = D.ORGANIZATION_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
				AND MTP.ID = $hdid
                AND MTP.TINGKAT = 1
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
						, NULL HRD_ID
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
				LEFT JOIN MJ.MJ_M_USER A ON A.EMP_ID = MTP.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON MTP.PERSON_ID = D.PERSON_ID
				WHERE MTP.STATUS_DOKUMEN <> 'Approved'
				AND D.PRIMARY_FLAG = 'Y'
				AND D.EFFECTIVE_END_DATE > SYSDATE
                AND MTP.TINGKAT = 1
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
	";
	
	$resultHrdId_Approve = oci_parse( $con, $query_HRD_ID_Approve );
	oci_execute( $resultHrdId_Approve );
	$rowHrdId_Approve = oci_fetch_row( $resultHrdId_Approve );
	
	$HRD_Id_Check_Approve = $rowHrdId_Approve[0];
	
	
	
	if ( $HRD_Id_Check_Reject != '' ) {
		
		$query_HRD_Emp =
		"
			SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
			FROM    APPS.PER_PEOPLE_F PPF
			WHERE   PERSON_ID = $HRD_Id_Check_Reject
		";
		
		$resultHrdEmp = oci_parse( $con, $query_HRD_Emp );
		oci_execute( $resultHrdEmp );
		$rowHrdEmp = oci_fetch_row( $resultHrdEmp );
		
		$HRD_Mgr_Name = $rowHrdEmp[0];
		$HRD_Mgr_Email = $rowHrdEmp[1];
		
	}
	

	if ( $HRD_Id_Check_Approve != '' ) {
		
		$query_HRD_Emp =
		"
			SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
			FROM    APPS.PER_PEOPLE_F PPF
			WHERE   PERSON_ID = $HRD_Id_Check_Approve
		";
		
		$resultHrdEmp = oci_parse( $con, $query_HRD_Emp );
		oci_execute( $resultHrdEmp );
		$rowHrdEmp = oci_fetch_row( $resultHrdEmp );
		
		$HRD_Mgr_Name = $rowHrdEmp[0];
		$HRD_Mgr_Email = $rowHrdEmp[1];
		
	}
	
	
	
	if ( $vparam_approval == 'Approved' ) {
		
		$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo";
		
		
		// Jika TIDAK dilakukan setting HRD_ID di MJ_M_APPROVAL_PINJAMAN
		
		if ( $HRD_Id_Check_Approve == '' ) {
			
			$bodyEmail = "
Dear $HRD_Mgr_Names,

Telah dibuat pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName.

Mohon untuk melakukan approval pengajuan $vparam_tipe diatas.
Terima Kasih.


[Form: Approval Pinjaman Karyawan Manager]


To: $HRD_Mgr_Emails
CC: $empEmail, $managerEmail
			";
		
		
		// Jika dilakukan setting HRD_ID di MJ_M_APPROVAL_PINJAMAN
		
		} else {
			
			$bodyEmail = "
Dear $HRD_Mgr_Name,

Telah dibuat pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName.

Mohon untuk melakukan approval pengajuan $vparam_tipe diatas.
Terima Kasih.


[Form: Approval Pinjaman Karyawan Manager]


To: $HRD_Mgr_Email
CC: $empEmail, $managerEmail
			";
			
		}
	
	
	// Jika di-reject
	
	} else {
		
		$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo di-reject";

		$bodyEmail = "
Dear $empName,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName di-reject.

Keterangan reject dari manager: $param_keterangan

Terima Kasih.


[Form: Approval Pinjaman Karyawan Manager]


To: $empEmail
CC: $managerEmail
		";

	}

/*

		
		// Jika TIDAK dilakukan setting HRD_ID di MJ_M_APPROVAL_PINJAMAN
		
		if ( $HRD_Id_Check_Reject == '' ) {

			
		
		// Jika dilakukan setting HRD_ID di MJ_M_APPROVAL_PINJAMAN
		
		} else {
			
			$bodyEmail = "
Dear $empName,

Pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName di-reject.

Keterangan reject dari manager: $param_keterangan

Terima Kasih.


[Form: Approval Pinjaman Karyawan Manager]


To: $empEmail
CC: $managerEmail
			";
		
		}

*/


	// CC: $managerEmail, $HRD_Mgr_Email
	
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
	
	
	
	/* DIPAKAI LIVE */
	
	if ( $vparam_approval == 'Approved' ) {
		
		
		// Jika TIDAK dilakukan setting HRD_ID di MJ_M_APPROVAL_PINJAMAN
		
		if ( $HRD_Id_Check_Approve == '' ) {
			
			// To: $HRD_Mgr_Emails
			// CC: $empEmail, $managerEmail
			
			
			$query_HRD_To = 	"
				SELECT  PPF.EMAIL_ADDRESS
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
				INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
				INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
				INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
				WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
				AND PAF.PRIMARY_FLAG = 'Y'
				AND PJ.name LIKE '%HRD%' AND  UPPER( PG.name ) LIKE '%MANAGER%'
							";
			
			$resultHRD_To = oci_parse( $con, $query_HRD_To );
			oci_execute( $resultHRD_To );
			
			while( $rowHRD = oci_fetch_row( $resultHRD_To ) )
			{
				
				$mail->AddAddress( $rowHRD[0] );
				
			}
			
			
			// $mail->AddAddress( $HRD_Mgr_Emails );
			
			$mail->addCC( $empEmail );
			$mail->addCC( $managerEmail );
			
		// Jika dilakukan setting HRD_ID di MJ_M_APPROVAL_PINJAMAN
		
		} else {
			
			// To: $HRD_Mgr_Email
			// CC: $empEmail, $managerEmail
			
			$mail->AddAddress( $HRD_Mgr_Email );
			
			$mail->addCC( $empEmail );
			$mail->addCC( $managerEmail );
			
		}
		
		
	// Jika di-reject
	
	} else {
		
		
		// To: $empEmail
		// CC: $managerEmail, $HRD_Mgr_Emails
		
		$mail->AddAddress( $empEmail );
		
		$mail->addCC( $managerEmail );
		
		
	}
	
	$mail->addCC( 'sulianti@merakjaya.co.id' );
	// $mail->addCC( 'yuke.indarto@merakjaya.co.id' );
	// $mail->addCC( 'maria.natalia@merakjaya.co.id' );
	// $mail->addCC( 'dwatra@merakjaya.co.id' );
	
	/* DIPAKAI LIVE */
	
	

	/* DIPAKAI TESTING
	
	$mail->AddAddress( 'maria.natalia@merakjaya.co.id' );
	$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
	$mail->addCC( 'dwatra@merakjaya.co.id' );
	
	DIPAKAI TESTING */
	
	
	
	$success = $mail->Send();
	//echo $success;
		
}


?>