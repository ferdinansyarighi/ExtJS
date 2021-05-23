<?php
 include 'E:/dataSource/MJBHRD/main/koneksi2.php'; //Koneksi ke database
require('smtpemailattachment.php');
date_default_timezone_set("Asia/Jakarta");

$query = "SELECT DISTINCT PPF.FULL_NAME, PPF.EMAIL_ADDRESS
FROM MJ.MJ_T_TIMECARD MTT
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS J ON PAF.JOB_ID=J.JOB_ID
LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID=POS.POSITION_ID
LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO  
WHERE MTT.STATUS=247
AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '2015-10-21'
AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '2015-11-20'
AND (MTS.STATUS_DOK='In process' OR MTS.STATUS_DOK IS NULL)
--AND MTS.ID IS NULL
ORDER BY EMAIL_ADDRESS";
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$namaKaryawan = $row[0];
	$emailTujuan = $row[1];
	
	$emailJam = date("H");
	$subjectEmail = "Reminder Hari Terakhir Pembuatan SIK";
	$bodyEmail = "Dear ALL,

Sehubungan dengan dijalankannya sistem payroll diprogram oracle. 
Mohon untuk user yang namanya ada pada excel Data_Karyawan_Belum_Buat_SIK.xls untuk segera membuat Surat Ijin dan mohon untuk SPV dan Manager melakukan Approve Surat ijin yang sudah dibuat bisa dilihat pada Data_Karyawan_Sudah_Buat_SIK.xls.

untuk file terlampir.

Peringatan: Batas pembuatan SIK samppai tanggal 23 November 2015. Apabila belum membuat sampai tanggal yang ditentukan maka akan dianggap ALPHA.

Terima Kasih,";

	$mail = new PHPMailer();
	$mail->Hostname = '192.168.0.35';
	$mail->Port = 25;
	$mail->Host = "192.168.0.35";
	$mail->SMTPAuth = true;
	$mail->Username = 'autoemail.it@merakjaya.co.id';
	$mail->Password = 'autoemail';

	$mail->Mailer = 'smtp';
	$mail->From = "ricky.kurniadi@merakjaya.co.id";
	$mail->FromName = "Ricky Kurniadi"; 
	$mail->Subject = $subjectEmail;
	$mail->Body = $bodyEmail;
	//$emailTujuan
	$mail->AddAddress($emailTujuan); 
	$mail->addCC('indah.ys@merakjaya.co.id'); 
	$mail->addAttachment('Data_Karyawan_Sudah_Membuat_SIK.xls');
	$mail->addAttachment('Data_Karyawan_Belum_Membuat_SIK.xls');
	$mail->addCC('ricky.kurniadi@merakjaya.co.id');

	$success = $mail->Send();
}
?>