<?php
require('smtpemailattachment.php');
date_default_timezone_set("Asia/Jakarta");

function KirimEmailSIK($con, $dataName){  
$nomorCount=0;
$TransID = "";
$isiBody = "";
$data = "";
$query = "SELECT DISTINCT MTS.ID AS hd_id
, NOMOR_SIK AS DATA_NOSIK
, PEMBUAT AS DATA_PEMBUAT
, PEMOHON AS DATA_PEMOHON
, DEPARTEMEN AS DATA_DEPT
, PLANT AS DATA_PLANT
, MANAGER AS DATA_MANAGER
, EMAIL_MANAGER AS DATA_EMAILMANAGER
, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
, JAM_FROM AS DATA_JAM_FROM, JAM_TO AS DATA_JAM_TO
, KETERANGAN AS DATA_KETERANGAN, ALAMAT AS DATA_ALAMAT
, NO_TELP AS DATA_NOTELP
, NO_HP AS DATA_NOHP
, MTS.EMAIL AS DATA_EMAIL
, MTS.STATUS AS DATA_STATUS
, MTS.TINGKAT AS DATA_TINGKAT
, KATEGORI AS DATA_KATEGORI
, IJIN_KHUSUS AS DATA_IJIN
, SPV AS DATA_SPV
, EMAIL_SPV AS DATA_EMAILSPV
FROM MJ.MJ_T_SIK MTS
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTS.TINGKAT AND MMUP.APP_ID=" . APPCODE . "
AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
FROM MJ.MJ_M_AREA MMA
INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
WHERE 1=1 AND CASE MTS.STATUS_DOK 
WHEN 'Approved' THEN '-' 
WHEN 'Disapproved' THEN '-' 
ELSE (CASE MTS.TINGKAT WHEN 0 THEN MTS.SPV WHEN 1 THEN MTS.MANAGER ELSE NVL(MMUP.FULL_NAME, '-') END) END='$dataName'
ORDER BY MTS.ID";
//echo $query;
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$dataID = $row[0];
	$query2 = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$dataID";
	$result2 = oci_parse($con, $query2);
	oci_execute($result2);
	$doccount=0;
	while($row2 = oci_fetch_row($result2))
	{
		$vTransID=$row2[0];
		$vFilename=$row2[1];
		$vFilesize=$row2[2];
		$vFiletype=$row2[3];
		$vFileuser=$row2[4];
		$vFiledate=$row2[5];
		$ekstensi	= end(explode(".", $vFilename));
		$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		if($doccount==0){
			$data = $docattachment;
		} else {
			$data .= ", " . $docattachment;
		} 
		$doccount++;
	}

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
	$nomorCount++;
	
	$isiBody .= "
	<tr>
		<td><div align=\"center\">$nomorCount</div></td>
		<td><div align=\"center\">$no_sik</div></td>
		<td><div align=\"center\">$kategori</div></td>
		<td><div align=\"center\">$pemohon</div></td>
		<td><div align=\"center\">$dept</div></td>
		<td><div align=\"center\">$plant</div></td>
		<td><div align=\"center\">$tgl_from s/d $tgl_to</div></td>
		<td><div align=\"center\">$jam_from s/d $jam_to</div></td>
		<td><div align=\"center\">$keterangan</div></td>
		<td><div align=\"center\">$no_hp</div></td>
		<td><div align=\"center\">$data</div></td>
	</tr>
	";
}


$isiExcel = "<table border=\"1\">
<tr>
    <td colspan=\"11\"><div align=\"center\">DATA SIK OUTSTANDING</div></td>
</tr>
<tr>
    <td colspan=\"11\"><div align=\"left\"></div></td>
</tr>
<tr>
		<td><div align=\"center\">No.</div></td>
		<td><div align=\"center\">No SIK</div></td>
		<td><div align=\"center\">Kategori</div></td>
		<td><div align=\"center\">Pemohon</div></td>
		<td><div align=\"center\">Departemen</div></td>
		<td><div align=\"center\">Plant</div></td>
		<td><div align=\"center\">Tanggal</div></td>
		<td><div align=\"center\">Jam</div></td>
		<td><div align=\"center\">Keterangan</div></td>
		<td><div align=\"center\">No HP</div></td>
		<td><div align=\"center\">Attachment</div></td>
	</tr>
" . $isiBody . "
</table> ";
//echo $isiExcel;
$namaFile ="Report_Outstanding_SIK_$dataName.xls";
 
$fp = fopen($namaFile, "w");
fwrite($fp, $isiExcel);

fclose($fp);

$query = "SELECT EMAIL_ADDRESS
FROM APPS.PER_PEOPLE_F PPF
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$dataName'";
$result = oci_parse($con, $query);
oci_execute($result);
$row = oci_fetch_row($result);
$emailTujuan=$row[0];

$emailJam = date("H");
$subjectEmail = "[Autoemail] Reminder Daftar SIK Outstanding";
$bodyEmail = "Dear $dataName,
	
Terlampir data SIK outstanding silakan melakukan approve atau reject agar SIK tersebut dapat diproses pada payroll.

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
//$emailTujuan
$mail->AddAddress($emailTujuan); 
$mail->addCC('indah.ys@merakjaya.co.id'); 
$mail->addAttachment($namaFile);
//$mail->addCC('ricky.kurniadi@merakjaya.co.id');

$success = $mail->Send();
//echo $success;
//}
}
?>