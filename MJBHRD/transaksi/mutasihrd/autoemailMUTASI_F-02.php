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

if (isset($_POST['hdid']))
{
	$hdid = $_POST['hdid'];
	$vparam_tipe = $_POST['param_tipe'];
	$vparam_karyawan = $_POST['param_karyawan'];
	$vparam_setuju = $_POST['param_setuju'];
	
	$vparam_notes = $_POST['param_notes'];
	
	/*
	$query = "SELECT PPF2.FULL_NAME, EMAIL.EMAIL_ADDRESS, PPF1.EMAIL_ADDRESS PEMOHON, PPF2.EMAIL_ADDRESS PEMBUAT, MTM.NO_REQUEST
	FROM MJ.MJ_T_MUTASI MTM
	LEFT JOIN 
	(
		SELECT DISTINCT PAF.JOB_ID, PPF.EMAIL_ADDRESS, PPF.FULL_NAME
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%'
	) EMAIL ON EMAIL.JOB_ID = MTM.DEPT_BARU_ID
	LEFT JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.KARYAWAN_ID AND PPF1.EFFECTIVE_END_DATE > SYSDATE AND PPF1.CURRENT_EMPLOYEE_FLAG = 'Y'
	LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.CREATED_BY AND PPF2.EFFECTIVE_END_DATE > SYSDATE AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y'
	WHERE 1=1 AND MTM.ID = $hdid";
	
    // echo $query;
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
	$deptManager = $row[0];		
	$deptManagerEmail = $row[1];		
	$deptPemohon = $row[2];		
	$deptPembuat = $row[3];
	$noRequest = $row[4];
	*/
	
	
	$query = "
		SELECT  
			A.ID
			, A.PEMOHON
			, A.NAMA_PEMBUAT
			, A.EMAIL_PEMBUAT
			, A.NO_REQUEST
			, B.EMAIL_ADDRESS_MGR_LAMA
			, B.FULL_NAME_MGR_LAMA
			, C.EMAIL_ADDRESS_MGR_BARU
			, C.FULL_NAME_MGR_BARU
		FROM
		(
		SELECT 
		MTM.ID
		, PPF1.EMAIL_ADDRESS PEMOHON
		, PPF2.FULL_NAME NAMA_PEMBUAT
		, PPF2.EMAIL_ADDRESS EMAIL_PEMBUAT
		, MTM.NO_REQUEST
		, MTM.DEPT_LAMA_ID
		, MTM.MGR_LAMA
		, MTM.MGR_BARU
		FROM    MJ.MJ_T_MUTASI MTM
		LEFT JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.KARYAWAN_ID AND PPF1.EFFECTIVE_END_DATE > SYSDATE AND PPF1.CURRENT_EMPLOYEE_FLAG = 'Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.CREATED_BY AND PPF2.EFFECTIVE_END_DATE > SYSDATE AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y'
		) A,
		(
		SELECT  DISTINCT PPF.PERSON_ID PERSON_ID_MGR_LAMA
		, PPF.EMAIL_ADDRESS EMAIL_ADDRESS_MGR_LAMA
		, PPF.FULL_NAME FULL_NAME_MGR_LAMA
		FROM    APPS.PER_PEOPLE_F PPF
		INNER   JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
		INNER   JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
		WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
		AND     PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%'
		) B,
		(
		SELECT  DISTINCT PPF.PERSON_ID PERSON_ID_MGR_BARU
		, PPF.EMAIL_ADDRESS EMAIL_ADDRESS_MGR_BARU
		, PPF.FULL_NAME FULL_NAME_MGR_BARU
		FROM    APPS.PER_PEOPLE_F PPF
		INNER   JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
		INNER   JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
		WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
		AND     PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%'
		) C
		WHERE   A.MGR_LAMA = B.PERSON_ID_MGR_LAMA 
		AND     A.MGR_BARU = C.PERSON_ID_MGR_BARU 
		AND     A.ID = $hdid
		";
				
				
    // echo $query;
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
    $deptPemohon = $row[1];
	$namaPembuat = $row[2];
    $deptPembuat = $row[3];
    $noRequest = $row[4];
    $deptManagerEmail = $row[5];
    $deptManager = $row[6];
    $deptMgrBaruEmail = $row[7];
    $deptMgrBaru = $row[8];
	



	// Jika transaksi di-approve maka tunggu 5 detik untuk generate file pdf F-02 untuk dikirim sebagai attachment 

	if ($vparam_setuju == 'Setuju') {
		
		sleep(5);
		
	}



	$emailJam = date("H");

/*
	$subjectEmail = "[Autoemail] Notifikasi pengajuan $vparam_tipe nomor $noRequest";
	$bodyEmail = "Dear $deptManager,

	Telah dibuat pengajuan $vparam_tipe nomor $noRequest atas nama $vparam_karyawan.

	Mohon untuk melakukan approval pengajuan $vparam_tipe diatas.
	Terima Kasih.


	[Form: Approval Mutasi Karyawan HRD]
	";
*/


	if ($vparam_setuju == 'Setuju') {
		
		$subjectEmail = "[Autoemail] Notifikasi approval $vparam_tipe nomor $noRequest";
		$bodyEmail = "
Dear $deptManager,

Pengajuan $vparam_tipe nomor $noRequest atas nama $vparam_karyawan telah di-approve.
Terima Kasih.


[Form: Approval Mutasi Karyawan HRD]


	";
	
	} 

// Jika tidak disetujui

	else {
	
	$subjectEmail = "[Autoemail] Notifikasi disapprove $vparam_tipe nomor $noRequest";
	$bodyEmail = "
Dear $deptManager,

Pengajuan $vparam_tipe nomor $noRequest atas nama $vparam_karyawan telah di-disapprove.

Notes Persetujuan: $vparam_notes.

Terima Kasih.


[Form: Approval Mutasi Karyawan HRD]


	";
	
	}


$bodyEmail .= "
To: $deptManagerEmail
CC: $deptPembuat, $deptPemohon, $deptMgrBaruEmail, freddy.anggo@merakjaya.co.id
";


/*
deptManagerEmail: $deptManagerEmail
deptPemohon: $deptPemohon
deptPembuat: $deptPembuat
*/


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
	


	if ($vparam_setuju == 'Setuju') {
		
		$query_noreq = "
			SELECT  '_' || REPLACE( NO_REQUEST, '/', '_' ) NO_REQUEST_FILENAME
			FROM    MJ_T_MUTASI
			WHERE   ID = $hdid
		";
		
		$result_noreq = oci_parse( $con, $query_noreq );
		oci_execute( $result_noreq );
		$row_noreq = oci_fetch_row( $result_noreq );
		
		$vNoReq = $row_noreq[0];
		
		$mail->addAttachment( 'Mutasi_F-02' . $vNoReq . '.pdf' );
		
		/* DIPAKAI */
		
		$mail->AddAddress('lambang.st@merakjaya.co.id');
		$mail->AddAddress('veronika.putri@merakjaya.co.id');
		$mail->AddAddress('hrd.payroll@merakjaya.co.id');
		
		$mail->AddAddress($deptManagerEmail);
		
		$mail->addCC($deptPembuat);
		$mail->addCC($deptPemohon);
		$mail->addCC($deptMgrBaruEmail);
		
		$mail->addCC('freddy.anggo@merakjaya.co.id');
		$mail->addCC('teguh@merakjaya.co.id');
		
		/* DIPAKAI */
		
		$mail->addCC('maria.natalia@merakjaya.co.id'); 
		$mail->addCC('yuke.indarto@merakjaya.co.id'); 
		$mail->addCC('dwatra@merakjaya.co.id');
	

	// Jika reject 
	
	} else {
		
		/* DIPAKAI */
		
		$mail->AddAddress($deptManagerEmail);
		
		$mail->addCC($deptPembuat);
		$mail->addCC($deptPemohon);
		$mail->addCC($deptMgrBaruEmail);
		
		$mail->addCC('freddy.anggo@merakjaya.co.id');
		$mail->addCC('veronika.putri@merakjaya.co.id');
		$mail->addCC('lambang.st@merakjaya.co.id');
		$mail->addCC('teguh@merakjaya.co.id');
		
		/* DIPAKAI */
		
		$mail->addCC('maria.natalia@merakjaya.co.id'); 
		$mail->addCC('yuke.indarto@merakjaya.co.id'); 
		$mail->addCC('dwatra@merakjaya.co.id');
		
	}
	
	
	// $mail->AddAddress('fajar.setiawan@merakjaya.co.id'); 
	// $mail->AddAddress('harianto.p@merakjaya.co.id'); 
	// $mail->addAttachment( 'Mutasi_F-02.pdf' );
	

	// $mail->AddAddress($deptManagerEmail);
	
	// freddy.anggo@merakjaya.co.id
	// sonia.adina@merakjaya.co.id
	// hrd.complain@merakjaya.co.id
	// sulianti@merakjaya.co.id
	
	// $mail->addCC('sonia.adina@merakjaya.co.id');
	// $mail->AddAddress('maria.natalia@merakjaya.co.id'); 
	// $mail->AddAddress('yuke.indarto@merakjaya.co.id'); 
	// $mail->addCC('dwatra@merakjaya.co.id');
	
	// $mail->addAttachment( 'Mutasi_F-03.pdf' );
	

	$success = $mail->Send();

} 

?>