<?php
 require('smtpemailattachment.php');
$email_to = "m.ferdinansyah@merakjaya.co.id"; // Replace this with your email address
$success_message = "Thank you for contacting us."; // This is an example message. Replace with your own.
$site_name = "User Absen Biofinger PT. Merak Jaya Beton"; // Replace this with your website name.

$email = trim($_POST['email']);
$submitted = $_POST['submitted'];

if(isset($submitted)){
	if($email === '' || $email === 'Enter Your Suggestion' ) {
		$email_empty = true;
		$error = true;
	} 
}

if(isset($error)){
		echo '<span class="error_notice"><ul>';
		if($email_empty){
			echo '<li>Please enter your suggestion/li>';
		} else {
			echo '<li>An error has occurred while sending your message. Please try again later.</li>';
		}
		echo "</ul></span>";
}

if(!isset($error)){
		$emailJam = date("H");
		$subjectEmail = "[Autoemail] Masukan User Perihal Masalah ODBC Connect";
		
		$mail = new PHPMailer();
		$mail->Hostname = '192.168.0.35';
		$mail->Port = 25;
		$mail->Host = "192.168.0.35";
		$mail->SMTPAuth = true;
		$mail->Username = 'autoemail.it@merakjaya.co.id';
		$mail->Password = 'autoemail';

		$bodyEmail = "Dear Tim IT Operational,
				
Terkait masalah koneksi ODBC, ada catatan atau masukan dari user yaitu sebagai berikut,

Catatan : $email

Mohon untuk dapat menyelesaikan permasalahan koneksi saat ini.

Terima Kasih.";

		$mail->Mailer = 'smtp';
		$mail->From = "autoemail.it@merakjaya.co.id";
		$mail->FromName = "Auto Email"; 
		$mail->Subject = $subjectEmail;
		$mail->Body = $bodyEmail;
		
		$mail->AddAddress('krisatyadhi@merakjaya.co.id'); 
		$mail->addCC('it.care@merakjaya.co.id');
		$mail->addCC('it.engineering@merakjaya.co.id');
		$mail->Send();
		echo '<span class="success_notice">' . $success_message . '</span>';
	}
?>