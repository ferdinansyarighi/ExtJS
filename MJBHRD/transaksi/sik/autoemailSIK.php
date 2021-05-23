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
	$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NOSIK
	, PEMBUAT AS DATA_PEMBUAT
	, PEMOHON AS DATA_PEMOHON
	, DEPARTEMEN AS DATA_DEPT
	, PLANT AS DATA_PLANT
	, MANAGER AS DATA_MANAGER
	, EMAIL_MANAGER AS DATA_EMAILMANAGER
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO
	, KETERANGAN AS DATA_KETERANGAN
	, ALAMAT AS DATA_ALAMAT
	, NO_TELP AS DATA_NOTELP
	, NO_HP AS DATA_NOHP
	, EMAIL AS DATA_EMAIL
	, STATUS AS DATA_STATUS
	, TINGKAT AS DATA_TINGKAT
	, KATEGORI AS DATA_KATEGORI
	, IJIN_KHUSUS AS DATA_IJIN
	, SPV AS DATA_SPV
	, EMAIL_SPV AS DATA_EMAILSPV 
	FROM MJ.MJ_T_SIK 
	WHERE ID=$hdid";
//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$no_sik = $row[1];
	$pembuat = $row[2];
	$pemohon = $row[3];
	$dept=$row[4];
	$plant=$row[5];
	$manager=$row[6];
	$email_manager=$row[7];
	$tgl_from=$row[8];
	$tgl_to=$row[9];
	$jam_from=$row[10];
	$jam_to=$row[11];
	$keterangan=$row[12];
	$alamat=$row[13];
	$no_telp=$row[14];
	$no_hp=$row[15];
	$email=$row[16];
	$status=$row[17];
	$tingkat=$row[18];
	$kategori=$row[19];
	$ijin=$row[20];
	$spv=$row[21];
	$email_spv=$row[22];
	
	if($spv == '' || $spv == '- Pilih -'){
		$namaTujuan = $manager;
		$emailTujuan = $email_manager;
	} else {
		$namaTujuan = $spv;
		$emailTujuan = $email_spv;
	}
	
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$pembuat'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPembuat=$row[0];
		
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$pemohon'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPemohon=$row[0];
	$query = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$hdid";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$doccount=0;
	while($row = oci_fetch_row($result))
	{
		$vTransID=$row[0];
		$vFilename=$row[1];
		$vFilesize=$row[2];
		$vFiletype=$row[3];
		$vFileuser=$row[4];
		$vFiledate=$row[5];
		$ekstensi	= end(explode(".", $vFilename));
		/* $docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . ">" . $vFilename . "</a>";*/
		$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		if($doccount==0){
			$data = $docattachment;
		} else {
			$data .= ", " . $docattachment;
		} 
		//echo $docattachment;
		//$mail->addAttachment( $docattachment );
		$doccount++;
	}
} 



$emailJam = date("H");
$subjectEmail = "[Autoemail] Pengajuan Surat Ijin $no_sik";
$bodyEmail = "Dear $namaTujuan,
	
Telah dibuat surat ijin dengan detail sebagai berikut :

No Surat Ijin 		: $no_sik
Kategory 		: $kategori
Pemohon		: $pemohon
Department		: $dept
Plant			: $plant
Tanggal 		: $tgl_from s/d $tgl_to
Jam			: $jam_from s/d $jam_to
Keterangan		: $keterangan
No HP			: $no_hp
Attachment		: $data

Mohon untuk melakukan approval surat ijin diatas.
Terima Kasih,";

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
	
	$mail->AddAddress($emailTujuan); 
	$mail->addCC($emailPembuat);
	$mail->addCC($emailPemohon);
	//$mail->addCC('ricky.kurniadi@merakjaya.co.id');
	
	$success = $mail->Send();
	//echo $success;
//}
?>