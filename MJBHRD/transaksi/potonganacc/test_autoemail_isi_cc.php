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

	$emailJam = date("H");
	
	
	// $cc_autoemail = 'yuke.indarto@merakjaya.co.id, maria.natalia@merakjaya.co.id, dwatra@merakjaya.co.id';
	
	// $cc_autoemail = 'yuke.indarto@merakjaya.co.id';
	
	// $cc_autoemail = 'yuke.indarto@merakjaya.co.id, yuke.indarto@merakjaya.co.id';
	
	
	$subjectEmail = "Test kirim cc autoemail";
	$bodyEmail = "

Test kirim cc autoemail


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
	


	$query_penerima = "
		(
		SELECT  'yuke.indarto@merakjaya.co.id' EMAIL
		FROM    DUAL
		)
		UNION
		(
		SELECT  'maria.natalia@merakjaya.co.id' EMAIL
		FROM    DUAL
		)
		UNION
		(
		SELECT  'dwatra@merakjaya.co.id' EMAIL
		FROM    DUAL
		)
	";
	
	$result = oci_parse( $con, $query_penerima );
	oci_execute( $result );
	
	while( $row = oci_fetch_row( $result ) )
	{
		
		$mail->addCC( $row[0] );
		
	}
	
	/*
	$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
	$mail->addCC('maria.natalia@merakjaya.co.id'); 
	$mail->addCC('dwatra@merakjaya.co.id');	
	*/

	$success = $mail->Send();


?>