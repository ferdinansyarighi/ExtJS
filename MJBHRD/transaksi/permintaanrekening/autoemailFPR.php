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


if (isset($_POST['noreq']))
{	
	$noreq = $_POST['noreq'];
	$pembuat = $_POST['pembuat'];
	$namakar = $_POST['namakar'];
	$dept = $_POST['departemen'];
	$pos = $_POST['posisi'];
	$loc = $_POST['lokasi'];
	$alasan = $_POST['alasan'];
	$cabank = $_POST['cabangbank'];
	$bank = $_POST['bank'];
	$norek = $_POST['norek'];
	$namarek = $_POST['namarek'];
} 
$queryPemohon = "select email_address from per_people_f where full_name = '$namakar'";
//echo $queryPemohon;
$resultPemohon = oci_parse($con,$queryPemohon);
oci_execute($resultPemohon);
$rowPemohon = oci_fetch_row($resultPemohon);
$emailKaryawan = $rowPemohon[0];

$queryPem = "select full_name from per_people_f where person_id = $pembuat";
//echo $queryPemohon;
$resultPem = oci_parse($con,$queryPem);
oci_execute($resultPem);
$rowPem = oci_fetch_row($resultPem);
$pembuatfix = $rowPem[0];

$emailJam = date("H");
$subjectEmail = "[Autoemail] Pengajuan Rekening Tervalidasi";
$bodyEmail = "Dear all,

Pengajuan rekening untuk karyawan sesuai data berikut:

Nomor                        :   $noreq
Pembuat                    :   $emp_name
Nama karyawan      :   $namakar
Departemen             :   $dept
Posisi                            :   $pos
Lokasi                           :   $loc
Alasan                          :   $alasan
Bank                             :   $bank
Cabang Bank             :   $cabank
Nomor Rekening     :   $norek
Nama Rekening       :   $namarek

Telah divalidasi, karyawan yang bersangkutan dimohon untuk mengambil rekening tersebut.

* Note : Untuk Tim Database HRD, Mohon untuk segera update nomor rekening di database karyawan.

Terima Kasih.";

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
	
	$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
	$mail->AddAddress('maria.natalia@merakjaya.co.id');  
	//$mail->AddAddress($emailKaryawan); 
	//$mail->AddAddress('freddy.anggo@merakjaya.co.id')
	//$mail->AddAddress('sonia.adina@merakjaya.co.id')
	
	//$mail->addCC('dwatra@merakjaya.co.id');
	
	$success = $mail->Send();
	//echo $success;
//}
?>