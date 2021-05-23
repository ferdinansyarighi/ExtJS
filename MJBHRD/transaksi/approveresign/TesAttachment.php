<?PHP
require('smtpemailattachment.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
// deklarasi variable dan session
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
  
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$data="gagal";
$attachment="";
$typeform="";
$Keputusan="";
$arrTransID=array();
$arrAlasan=array();
$emailSblmPengepprove='';


	$typeform='Setuju';
	if ($typeform=='Setuju') {
		$Keputusan = 'Approved';
	} else {
		$Keputusan = 'Disapproved';
	}
	
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$emp_name'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPengepprove='ricky.kurniadi@merakjaya.co.id';
	
	$BatasApprove=3;
  
	$TransID = 7756;
	$AlasanApp = '';
	$emp_id = 4759;
	
	$resultCount = oci_parse($con, "SELECT COUNT(-1)
	FROM MJ.MJ_M_USERAPPROVAL
	WHERE STATUS='A' AND APP_ID= 1 AND EMP_ID=$emp_id ");
	
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$jumlahcount = $rowCount[0];
	if ($jumlahcount==0){
		$resultCount = oci_parse($con, "SELECT NVL(TINGKAT, 0) AS TINGKAT
		FROM MJ.MJ_T_SIK
		WHERE STATUS=1 AND ID=$TransID ");
		oci_execute($resultCount);
		$rowCount = oci_fetch_row($resultCount);
		$tingkat = $rowCount[0];
		$tingkat = 1;
		$tingkatUpdate = $tingkat + 1;
		$tingkatDecress = $tingkat - 1;
	} else {
		$resultCount = oci_parse($con, "SELECT NVL(TINGKAT, 0) AS TINGKAT
		FROM MJ.MJ_M_USERAPPROVAL
		WHERE STATUS='A' AND APP_ID= " . APPCODE . " AND EMP_ID=$emp_id
		ORDER BY ID DESC ");
		oci_execute($resultCount);
		$rowCount = oci_fetch_row($resultCount);
		$tingkat = $rowCount[0];
		$tingkatUpdate = $tingkat + 1;
		$tingkatDecress = $tingkat - 1;
	}
	
	$query = "SELECT DISTINCT ID AS hd_id, NOMOR_SIK AS DATA_NOSIK, PEMBUAT AS DATA_PEMBUAT, PEMOHON AS DATA_PEMOHON, DEPARTEMEN AS DATA_DEPT, PLANT AS DATA_PLANT, MANAGER AS DATA_MANAGER, EMAIL_MANAGER AS DATA_EMAILMANAGER, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO, JAM_FROM AS DATA_JAM_FROM, JAM_TO AS DATA_JAM_TO, KETERANGAN AS DATA_KETERANGAN, ALAMAT AS DATA_ALAMAT, NO_TELP AS DATA_NOTELP, NO_HP AS DATA_NOHP, EMAIL AS DATA_EMAIL, STATUS AS DATA_STATUS, TINGKAT AS DATA_TINGKAT, KATEGORI AS DATA_KATEGORI, IJIN_KHUSUS AS DATA_IJIN, SPV AS DATA_SPV, EMAIL_SPV AS DATA_EMAILSPV FROM MJ.MJ_T_SIK WHERE ID=$TransID";
//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$no_sik = $row[1];
	$pembuat = str_replace("'", "''", $row[2]);
	$pemohon = str_replace("'", "''", $row[3]);
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
	$tingkatTrans=$row[18];
	$kategori=$row[19];
	$ijin=$row[20];
	$spv=$row[21];
	$email_spv=$row[22];
	if($tingkatUpdate==1){
		if(($manager=='' || $manager == '- Pilih -') && ($jumlahcount == 0)){
			$tingkat = $tingkat + 1;
			$tingkatUpdate = $tingkat + 1;
			$tingkatDecress = 0;
		}
	}
	if($tingkatDecress==1){
		if(($manager=='' || $manager == '- Pilih -') && ($jumlahcount == 0)){
			// $tingkat = $tingkat - 1;
			// $tingkatUpdate = $tingkat + 1;
			$tingkatDecress = $tingkat - 2;
		}
	}
	
	$selisih=0;
	$selisih=(strtotime($tgl_to)-strtotime($tgl_from))/(60*60*24);
	$selisih=$selisih+1;
		
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$pembuat'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPembuat='ricky.kurniadi@merakjaya.co.id';
		
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$pemohon'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPemohon='ricky.kurniadi@merakjaya.co.id';
	//echo $tingkat ." dan ". $BatasApprove;
	if($tingkat >= $BatasApprove){
		$emailJam = date("H");
		$subjectEmail = "[Autoemail] ($Keputusan) Pengajuan Surat Ijin $no_sik";
		
		$mail = new PHPMailer();
		$mail->Hostname = '192.168.0.35';
		$mail->Port = 25;
		$mail->Host = "192.168.0.35";
		$mail->SMTPAuth = true;
		$mail->Username = 'autoemail.it@merakjaya.co.id';
		$mail->Password = 'autoemail';
		if ($typeform=='Setuju'){
				
			$bodyEmail = "Dear $pemohon,
				
Surat ijin anda, dengan detail dibawah, telah diapprove.

No Surat Ijin 	: $no_sik
Kategory 		: $kategori
Pemohon		: $pemohon
Department		: $dept
Plant			: $plant
Tanggal 		: $tgl_from s/d $tgl_to
Jam			: $jam_from s/d $jam_to
Keterangan		: $keterangan

Terima Kasih,";
		} else {
			if ($tingkatDecress==0){
				$emailSblmPengepprove=$email_spv;
			} elseif ($tingkatDecress==1){
				$emailSblmPengepprove=$email_manager;
			} else {
				$query = "SELECT EMAIL_ADDRESS 
                FROM MJ.MJ_M_USERAPPROVAL MMU
                INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMU.EMP_ID
                WHERE STATUS='A' AND APP_ID=" . APPCODE . " AND TINGKAT=$tingkatDecress AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND '$plant' IN (SELECT HL.LOCATION_CODE 
				FROM MJ.MJ_M_AREA MMA
				INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
				INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
				WHERE MMA.APP_ID=MMU.APP_ID AND MMA.NAMA_AREA=MMU.NAMA_AREA)";
				$result = oci_parse($con, $query);
				oci_execute($result);
				$row = oci_fetch_row($result);
				$emailSblmPengepprove=$row[0];
			}
			$emailSblmPengepprove = 'ricky.kurniadi@merakjaya.co.id';
			$bodyEmail = "Dear $pemohon,
				
Surat ijin anda, dengan detail dibawah, tidak disetujui oleh $emp_name.

No Surat Ijin 	: $no_sik
Kategory 		: $kategori
Pemohon		: $pemohon
Department		: $dept
Plant			: $plant
Tanggal 		: $tgl_from s/d $tgl_to
Jam			: $jam_from s/d $jam_to
Keterangan		: $keterangan
Alasan		: $AlasanApp

Terima Kasih,";

			$mail->addCC($emailPengepprove);
			$mail->addCC($emailSblmPengepprove);
		}
		$mail->Mailer = 'smtp';
		$mail->From = "autoemail.it@merakjaya.co.id";
		$mail->FromName = "Auto Email"; 
		$mail->Subject = $subjectEmail;
		$mail->Body = $bodyEmail;
		
		$mail->AddAddress($emailPemohon); 
		$mail->addCC($emailPembuat);
		//$mail->addCC('ricky.kurniadi@merakjaya.co.id');
		
		$success = $mail->Send();
	} else {
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
		
		//echo $typeform ." dan ". $tingkatUpdate;
		if ($typeform=='Setuju'){
			if($tingkatUpdate==1){
				$emailSsdhPengepprove=$email_manager;
				$namaSsdhPengepprove=$manager;
			} else {
				$query = "SELECT PPF.EMAIL_ADDRESS, PPF.FULL_NAME 
                FROM MJ.MJ_M_USERAPPROVAL MMU
                INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMU.EMP_ID
                WHERE STATUS='A' AND APP_ID=" . APPCODE . " AND TINGKAT=$tingkatUpdate AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND '$plant' IN (SELECT HL.LOCATION_CODE 
				FROM MJ.MJ_M_AREA MMA
				INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
				INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
				WHERE MMA.APP_ID=MMU.APP_ID AND MMA.NAMA_AREA=MMU.NAMA_AREA)";
				$result = oci_parse($con, $query);
				oci_execute($result);
				$row = oci_fetch_row($result);
				$emailSsdhPengepprove=$row[0];
				$namaSsdhPengepprove=$row[1];
			}
			$emailSsdhPengepprove = 'ricky.kurniadi@merakjaya.co.id';
			
			$query = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
			FROM MJ.MJ_M_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
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
				// $docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . ">" . $vFilename . "</a>";
				$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
				if($doccount==0){
					$attachment = $docattachment;
				} else {
					$attachment .= ", " . $docattachment;
				}
				//echo $docattachment;
				//$mail->addAttachment( $docattachment );
				$doccount++;
			}
			$emailJam = date("H");
			$subjectEmail = "[Autoemail] Pengajuan Surat Ijin $no_sik";
			$bodyEmail = "Dear $namaSsdhPengepprove,
				
Telah dibuat surat ijin dengan detail sebbagai berikut :

No Surat Ijin 	: $no_sik
Kategory 		: $kategori
Pemohon		: $pemohon
Department		: $dept
Plant			: $plant
Tanggal 		: $tgl_from s/d $tgl_to
Jam			: $jam_from s/d $jam_to
Keterangan		: $keterangan
No HP			: $no_hp
Attachment		: $attachment

Mohon untuk melakukan approval surat ijin diatas.
Terima Kasih,";

			
			$mail->AddAddress($emailSsdhPengepprove); 
			$mail->addCC($emailPemohon);
			
		} else {
			if ($tingkatDecress==0){
				$emailSblmPengepprove=$email_spv;
			} elseif ($tingkatDecress==1){
				$emailSblmPengepprove=$email_manager;
			} else {
				$query = "SELECT EMAIL_ADDRESS 
                FROM MJ.MJ_M_USERAPPROVAL MMU
                INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMU.EMP_ID
                WHERE STATUS='A' AND APP_ID=" . APPCODE . " AND TINGKAT=$tingkatDecress AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND '$plant' IN (SELECT HL.LOCATION_CODE 
				FROM MJ.MJ_M_AREA MMA
				INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
				INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
				WHERE MMA.APP_ID=MMU.APP_ID AND MMA.NAMA_AREA=MMU.NAMA_AREA)";
				$result = oci_parse($con, $query);
				oci_execute($result);
				$row = oci_fetch_row($result);
				$emailSblmPengepprove=$row[0];
			}
			$emailSblmPengepprove = 'ricky.kurniadi@merakjaya.co.id';
			$subjectEmail = "[Autoemail] ($Keputusan) Pengajuan Surat Ijin $no_sik";
			$bodyEmail = "Dear $pemohon,
				
Surat ijin anda, dengan detail dibawah, tidak disetujui oleh $emp_name.

No Surat Ijin 	: $no_sik
Kategory 		: $kategori
Pemohon		: $pemohon
Department		: $dept
Plant			: $plant
Tanggal 		: $tgl_from s/d $tgl_to
Jam			: $jam_from s/d $jam_to
Keterangan		: $keterangan
Alasan		: $AlasanApp

Terima Kasih,";

			$mail->AddAddress($emailPemohon); 
			$mail->addCC($emailPengepprove);
			$mail->addCC($emailSblmPengepprove);
		}
		
		
		$mail->Subject = $subjectEmail;
		$mail->Body = $bodyEmail;
			
		$mail->addCC($emailPembuat);
		//$mail->addCC('ricky.kurniadi@merakjaya.co.id');
		$success = $mail->Send();
	}
	
$data="sukses";

?>