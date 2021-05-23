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
$TransID = "";
$namaTujuan = "";
$emailTujuan = "";
if (isset($_POST['hdid']))
{	
	$hdid = $_POST['hdid'];
	$query = "SELECT DISTINCT MTSD.ID AS hd_id
	, MTS.PEMBUAT AS DATA_PEMBUAT
	, MTS.SPV AS DATA_SPV
	, MTS.EMAIL_SPV AS DATA_EMAILSPV
	, MTS.MANAGER AS DATA_MANAGER
	, MTS.EMAIL_MANAGER AS DATA_EMAILMANAGER
    , MTS.NOMOR_SPL
	FROM MJ.MJ_T_SPL MTS
	INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
	WHERE MTS.ID=$hdid";
//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$TransID .= $row[0];
		$pembuat=$row[1];
		$spv=$row[2];
		$email_spv=$row[3];
		$manager=$row[4];
		$email_manager=$row[5];
		$no_spl=$row[6];
	}	
		
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$pembuat'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPembuat=$row[0];
	
	if($spv == '' || $spv == '- Pilih -'){
		$namaTujuan = $manager;
		$emailTujuan = $email_manager;
	} else {
		$namaTujuan = $spv;
		$emailTujuan = $email_spv;
	}
	
} 



$emailJam = date("H");
$subjectEmail = "[Autoemail] Pengajuan SPL $no_spl";
$bodyEmail = 'Dear ' . $namaTujuan . ',
	
Telah dibuat surat perintah lembur dengan detail terlampir.

File lampiran :
- Detail_SPL_' . $TransID . '.xls

Mohon untuk melakukan approval surat perintah lembur diatas.
Terima Kasih,';

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
	
	$mail->AddAddress($emailPembuat); 
	$mail->addCC($emailPembuat);
	//$mail->addCC('ricky.kurniadi@merakjaya.co.id');
	
	$mail->addAttachment( 'Detail_SPL_' . $TransID . '.xls' );

	$success = $mail->Send();
	//echo $success;
//}
?>