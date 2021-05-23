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
	$vparam_transNo = $_POST['param_transNo']; 
	$vparam_tipe = $_POST['param_tipe'];
	
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
	
    $HrdName = $row[0];
	$HrdEmail = $row[1];
		
	
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
	
	
	
	$emailJam = date("H");
	
	$MgrFinanceName = 'Agustin, Mrs. Neneng NNG';
	$MgrFinanceEmail = 'neneng.nng@merakjaya.co.id';
	
	$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $vparam_transNo";
	$bodyEmail = "
Dear $MgrFinanceName,

Telah dibuat pengajuan $vparam_tipe nomor $vparam_transNo atas nama $empName.

Mohon untuk melakukan approval pengajuan $vparam_tipe di atas.
Terima kasih.


[Form: Pengajuan Pinjaman Karyawan HRD]


To: $MgrFinanceEmail
CC: $empEmail, $HrdEmail
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
	
	
	// To: $MgrFinanceEmail
	// CC: $empEmail, $HrdEmail
	
	
	/* DIPAKAI LIVE */
	
	$mail->AddAddress( $MgrFinanceEmail );
	$mail->addCC( $empEmail );
	$mail->addCC( $HrdEmail );
	
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