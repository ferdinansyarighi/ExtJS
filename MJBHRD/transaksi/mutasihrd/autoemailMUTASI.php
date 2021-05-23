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
} 

sleep(5);

$emailJam = date("H");
$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $noRequest";
$bodyEmail = "Dear $deptManager,
	
Telah dibuat pengajuan $vparam_tipe nomor $noRequest atas nama $vparam_karyawan.

Mohon untuk melakukan approval pengajuan $vparam_tipe diatas.
Terima Kasih.


[Form: Approval Mutasi Karyawan HRD]";

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
	
	// $mail->AddAddress($deptManagerEmail);
	
	$mail->AddAddress('maria.natalia@merakjaya.co.id'); 
	$mail->AddAddress('yuke.indarto@merakjaya.co.id'); 
	$mail->addCC('dwatra@merakjaya.co.id');
	
	//$mail->addAttachment( 'Mutasi_F-03.pdf' );
	
	$mail->addAttachment( 'Mutasi_F-02.pdf' );
	$mail->addAttachment( 'Mutasi_F-03_Payroll.pdf' );
	$mail->addAttachment( 'Mutasi_F-03_Non_Payroll.pdf' );
	
	$success = $mail->Send();
	//echo $success;
//}
?>