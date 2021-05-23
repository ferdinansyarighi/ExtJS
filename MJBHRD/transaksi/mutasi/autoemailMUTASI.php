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
	
	
	$query = "SELECT  
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
				AND     A.ID = $hdid";
	
	
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
	

	
	$query_status = "
		SELECT  STATUS
		FROM    MJ_T_MUTASI
		WHERE   ID = $hdid";
	
	
    // echo $query;
	
	$result_status = oci_parse( $con, $query_status );
	oci_execute( $result_status );
	$row_status = oci_fetch_row( $result_status );
	
} 


// Tambahan oleh Yuke di 5 Oktober 2018.
// Jika dokumen aktif, maka kirim email. Jika dokumen dinonaktifkan maka tidak perlu dikirimkan email.

if ( $row_status[0] == 'Y' ) {


$emailJam = date("H");
$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $noRequest";
$bodyEmail = "Dear $deptManager,
	
Telah dibuat pengajuan $vparam_tipe nomor $noRequest atas nama $vparam_karyawan.

Mohon untuk melakukan approval pengajuan $vparam_tipe diatas.
Terima Kasih.


[Form: Mutasi Karyawan]


To: $deptManagerEmail
CC: $deptPembuat, $deptPemohon, $deptMgrBaruEmail
";



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
	
	
	/* DITUTUP SEMENTARA */
	
	$mail->AddAddress($deptManagerEmail); 
	$mail->addCC($deptPemohon);
	$mail->addCC($deptPembuat);
	$mail->addCC($deptMgrBaruEmail);
	
	/* DITUTUP SEMENTARA */
	
	
	$mail->addCC('maria.natalia@merakjaya.co.id'); 
	$mail->addCC('yuke.indarto@merakjaya.co.id'); 
	$mail->addCC('dwatra@merakjaya.co.id');
	
	$success = $mail->Send();
	//echo $success;
}



?>