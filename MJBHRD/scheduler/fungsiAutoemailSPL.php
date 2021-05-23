<?php
require('smtpemailattachment.php');
date_default_timezone_set("Asia/Jakarta");

function KirimEmailSPL($con, $dataName){  
$nomorCount=0;
$TransID = "";
$isiBody = "";
$data = "";
$query = "SELECT DISTINCT MTSD.ID AS hd_id
, MTS.NOMOR_SPL
, MTS.PEMBUAT AS DATA_PEMBUAT
, MTSD.NAMA AS NAMA_KARYAWAN
, MTSD.DEPARTEMEN
, MTS.PLANT
, MTSD.JAM_FROM
, MTSD.JAM_TO
, MTSD.TOTAL_JAM
, MTSD.PEKERJAAN
FROM MJ.MJ_T_SPL MTS
INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTSD.TINGKAT AND MMUP.APP_ID=" . APPCODE . "
AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
FROM MJ.MJ_M_AREA MMA
INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
WHERE 1=1 AND CASE MTSD.TINGKAT WHEN 0 THEN MTS.SPV WHEN 1 THEN MTS.MANAGER ELSE NVL(MMUP.FULL_NAME, '-') END='$dataName'
ORDER BY MTSD.ID";
//echo $query;
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$dataID = $row[0];
	$no_spl = $row[1];
	$pembuat = $row[2];
	$pemohon = $row[3];
	$dept=$row[4];
	$plant=$row[5];
	$jam_from=$row[6];
	$jam_to=$row[7];
	$total_jam=$row[8];
	$pekerjaan=$row[9];
	$nomorCount++;
	
	$isiBody .= "
	<tr>
		<td><div align=\"center\">$nomorCount</div></td>
		<td><div align=\"center\">$no_spl</div></td>
		<td><div align=\"center\">$pembuat</div></td>
		<td><div align=\"center\">$pemohon</div></td>
		<td><div align=\"center\">$dept</div></td>
		<td><div align=\"center\">$plant</div></td>
		<td><div align=\"center\">$jam_from s/d $jam_to</div></td>
		<td><div align=\"center\">$total_jam</div></td>
		<td><div align=\"center\">$pekerjaan</div></td>
	</tr>
	";
}


$isiExcel = "<table border=\"1\">
<tr>
    <td colspan=\"9\"><div align=\"center\">DATA SPL OUTSTANDING</div></td>
</tr>
<tr>
    <td colspan=\"9\"><div align=\"left\"></div></td>
</tr>
<tr>
		<td><div align=\"center\">No.</div></td>
		<td><div align=\"center\">No SPL</div></td>
		<td><div align=\"center\">Pembuat</div></td>
		<td><div align=\"center\">Pemohon</div></td>
		<td><div align=\"center\">Departemen</div></td>
		<td><div align=\"center\">Plant</div></td>
		<td><div align=\"center\">Jam</div></td>
		<td><div align=\"center\">Total Jam</div></td>
		<td><div align=\"center\">Pekerjaan</div></td>
	</tr>
" . $isiBody . "
</table> ";
//echo $isiExcel;
$namaFile ="Report_Outstanding_SPL_$dataName.xls";
 
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
$subjectEmail = "[Autoemail] Reminder Daftar SPL Outstanding";
$bodyEmail = "Dear $dataName,
	
Terlampir data SPL outstanding silakan melakukan approve atau reject agar SPL tersebut dapat diproses pada payroll.

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