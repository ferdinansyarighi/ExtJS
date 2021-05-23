<?PHP
require('smtpemailattachment.php');
include '../../main/koneksi.php';

$production_level = TRUE;

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

 if(isset($_POST['typeform']))
  {
	$typeform=$_POST['typeform'];
	if ($typeform=='Setuju') {
		$Keputusan = 'Approved';
	} else {
		$Keputusan = 'Disapproved';
	}
	$arrTransID=json_decode($_POST['arrTransID']);
	$arrAlasan=json_decode($_POST['arrAlasan']);
	
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$emp_name'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPengepprove=$row[0];
  }
  
$countID = count($arrTransID);
for ($x=0; $x<$countID; $x++){
	$TransID = $arrTransID[$x];
	$AlasanApp = $arrAlasan[$x];
	
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD/MM/YY'),TO_CHAR(TGL_RESIGN, 'DD/MM/YY'),TAHUN_LAMA_KERJA,BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,CREATED_DATE, TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE
	FROM MJ.MJ_T_RESIGN WHERE ID = $TransID";

	$result = oci_parse($con,$query);
	oci_execute($result);
	$row 		   =oci_fetch_row($result);

	$id_pengajuan  =$row[0];
	$no_pengajuan  =$row[1];
	$nama_karyawan =str_replace("'", "''", $row[2]);
	$company       =$row[3];
	$dept          =$row[4];
	$pos           =$row[5];
	$grade         =$row[6];
	$location      =$row[7];
	$tglmasuk      =$row[8];
	$tglresign     =$row[9];
	$thn_lama_kerja=$row[10];
	$bln_lama_kerja=$row[11];
	$hri_lama_kerja=$row[12];
	$manager       =$row[13];
	$keterangan    =$row[14];
	$status        =$row[15];
	$approval_man  =$row[16];
	$approval_hrd  =$row[17];
	$tglapprove    =$row[18];
	$pembuat       =$row[19];
	$tglpembuatan  =$row[20];
	$lamakerja     =$row[21];
	$ket_disapp    =$row[22];

	// EMAIL PEMOHON	
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$nama_karyawan'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPemohon=$row[0];

	// EMAIL MANAGER 
	$queryEmailman = "SELECT DISTINCT PPF.EMAIL_ADDRESS FROM PER_PEOPLE_F PPF, MJ.MJ_T_RESIGN RES
	WHERE PPF.FULL_NAME LIKE '%$manager%'
	AND RES.ID = $id_pengajuan
	AND PPF.EMAIL_ADDRESS IS NOT NULL";
	$resultEmailman = oci_parse($con, $queryEmailman);
	oci_execute($resultEmailman);
	$rowEmailman = oci_fetch_row($resultEmailman);
	$emailMan=$rowEmailman[0];

	// MANAGER HRD
	$querycount = "SELECT DISTINCT PPF.PERSON_ID, PPF.FULL_NAME, PJ.JOB_ID, PJ.NAME, PPF.EMAIL_ADDRESS 
	FROM PER_PEOPLE_F PPF, PER_JOBS PJ, PER_ASSIGNMENTS_F PAF, PER_GRADES PG
	WHERE PPF.PERSON_ID = PAF.PERSON_ID AND PAF.JOB_ID = PJ.JOB_ID AND (UPPER(PJ.NAME) LIKE UPPER('%HR%')) 
	AND PAF.GRADE_ID = PG.GRADE_ID AND PG.NAME LIKE '%Manager%' AND PPF.FULL_NAME LIKE '%$emp_name%'";
	$resultcount = oci_parse($con,$querycount);
	oci_execute($resultcount);
	$rowcount = oci_fetch_row($resultcount);
	$jumgen = $rowcount[0];

	// MANAGER DEPARTEMEN
	$querycountMan = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
	WHERE PPF.FULL_NAME LIKE '%$emp_name%' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID 
	AND PP.NAME LIKE '%MGR%' AND PAF.JOB_ID != 26066";
	$resultcountman = oci_parse($con,$querycountMan);
	oci_execute($resultcountman);
	$rowcountman = oci_fetch_row($resultcountman);
	$jumman = $rowcountman[0];
	//echo $querycountMan;
	if($jumgen>=1){
		$emailJam = date("H");
		$subjectEmail = "[Autoemail] ($Keputusan) Pengajuan Resign $no_pengajuan";
		
		$mail = new PHPMailer();
		$mail->Hostname = '192.168.0.35';
		$mail->Port = 25;
		$mail->Host = "192.168.0.35";
		$mail->SMTPAuth = true;
		$mail->Username = 'autoemail.it@merakjaya.co.id';
		$mail->Password = 'autoemail';

		$subjectEmail2 = "[Autoemail] ($Keputusan) Pengajuan Resign $no_pengajuan";
		
		$mail2 = new PHPMailer();
		$mail2->Hostname = '192.168.0.35';
		$mail2->Port = 25;
		$mail2->Host = "192.168.0.35";
		$mail2->SMTPAuth = true;
		$mail2->Username = 'autoemail.it@merakjaya.co.id';
		$mail2->Password = 'autoemail';

		if ($typeform=='Setuju'){
			$queryResign = "UPDATE MJ.MJ_T_RESIGN SET HRD_MGR = '$emp_name', APPROVAL_MANAGER_HRD='Approved', TGL_APP_TERAKHIR = SYSDATE, 
			LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE 
			WHERE ID=$TransID";
			$resultResign = oci_parse($con,$queryResign);
			oci_execute($resultResign);
			
			$hdid = $TransID;
			$data="sukses";

			$bodyEmail = "Dear All,
				
Pengajuan resign dengan data dibawah telah di approve oleh Manager HRD ($emp_name).

Nama Karyawan 	: $nama_karyawan
Perusahaan 		: $company
Departemen		: $dept
Posisi			: $pos
Grade			: $grade
Lokasi 			: $location
Tgl Masuk		: $tglmasuk
Tgl Resign		: $tglresign
Lama Bekerja		: $lamakerja
Dept Head		: $manager
Keterangan 		: $keterangan

Mohon untuk segera memproses pengajuan resign tersebut

Terima Kasih,";

			$mail->Mailer = 'smtp';
			$mail->From = "autoemail.it@merakjaya.co.id";
			$mail->FromName = "Auto Email"; 
			$mail->Subject = $subjectEmail;
			$mail->Body = $bodyEmail;
			
			if ($production_level)
			{
				$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
				$mail->addCC('teguh@merakjaya.co.id');
				//$mail->addCC('sulianti@merakjaya.co.id');
				$mail->addCC('$emailMan');
				//$mail->addCC('sonia.adina@merakjaya.co.id');
				$mail->addCC($emailPemohon);
			}
			else
			{
				$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
			}
			
			$success1 = $mail->Send();

			$bodyEmail2 = "Dear HRD Database,
				
Pengajuan resign dengan data dibawah telah di approve oleh Manager HRD ($emp_name).

Nama Karyawan 	: $nama_karyawan
Perusahaan 		: $company
Departemen		: $dept
Posisi			: $pos
Grade			: $grade
Lokasi 			: $location
Tgl Masuk		: $tglmasuk
Tgl Resign		: $tglresign
Lama Bekerja		: $lamakerja
Dept Head		: $manager
Keterangan 		: $keterangan

Mohon untuk segera memproses pengajuan resign dan melakukan freeze terhadap karyawan tersebut sesuai tanggal pengajuan

Terima Kasih,";

			$mail2->Mailer = 'smtp';
			$mail2->From = "autoemail.it@merakjaya.co.id";
			$mail2->FromName = "Auto Email"; 
			$mail2->Subject = $subjectEmail2;
			$mail2->Body = $bodyEmail2;
			
			if ($production_level)
			{
				
				$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
				$mail->AddAddress($emailPemohon);
				$mail->AddAddress('$emailMan');
				$mail->addCC('teguh@merakjaya.co.id');
				$mail->addCC('sulianti@merakjaya.co.id');
				$mail->addCC('veronika.putri@merakjaya.co.id');
				$mail2->AddAddress('sulianti@merakjaya.co.id');
				$mail2->addCC('veronika.putri@merakjaya.co.id');
			}
			else
			{
				$mail2->AddCC('maria.natalia@merakjaya.co.id');
				$mail2->addCC('m.ferdinansyah@merakjaya.co.id');
			}
			
			$success = $mail2->Send();

		} else {
			
			if($AlasanApp != '')
			{
				$queryResign = "UPDATE MJ.MJ_T_RESIGN SET APPROVAL_MANAGER_HRD='Disapproved', TGL_APP_TERAKHIR = SYSDATE, LAST_UPDATED_BY=$emp_id, 
				KETERANGAN_DISAPPROVE = '$AlasanApp' , PIC_DISAPPROVE = '$emp_name', LAST_UPDATED_DATE=SYSDATE 
				WHERE ID=$TransID";
				$resultResign = oci_parse($con,$queryResign);
				oci_execute($resultResign);

				$hdid = $TransID;
				$data="sukses";

				$bodyEmail = "Dear $nama_karyawan,
					
	Pengajuan resign anda, dengan detail dibawah, tidak disetujui oleh $emp_name.

	Nama Karyawan 	: $nama_karyawan
	Perusahaan 		: $company
	Departemen		: $dept
	Posisi			: $pos
	Grade			: $grade
	Lokasi 			: $location
	Tgl Masuk		: $tglmasuk
	Tgl Resign		: $tglresign
	Lama Bekerja		: $lamakerja
	Dept Head		: $manager
	Ket Disapprove  : $AlasanApp

	Terima Kasih,";
			}
			else {
				$hdid = $TransID;
				$data ="gagal";
			}

			if($AlasanApp != '')
			{
				$mail->Mailer = 'smtp';
				$mail->From = "autoemail.it@merakjaya.co.id";
				$mail->FromName = "Auto Email"; 
				$mail->Subject = $subjectEmail;
				$mail->Body = $bodyEmail;
				
				if ($production_level)
				{
					$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
					$mail->addCC('teguh@merakjaya.co.id');
					$mail->addCC('sulianti@merakjaya.co.id');
					$mail->AddAddress('$emailMan');
					$mail->addCC('veronika.putri@merakjaya.co.id');
					$mail->AddAddress($emailPemohon);
				}
				else{
					$mail->AddCC('m.ferdinansyah@merakjaya.co.id');
					$mail->addCC('maria.natalia@merakjaya.co.id');
				}
				$success = $mail->Send();
			}
		}
	} 
	else {
			$emailJam = date("H");
			$subjectEmail = "[Autoemail] ($Keputusan) Pengajuan Resign $no_pengajuan";
			
			$mail = new PHPMailer();
			$mail->Hostname = '192.168.0.35';
			$mail->Port = 25;
			$mail->Host = "192.168.0.35";
			$mail->SMTPAuth = true;
			$mail->Username = 'autoemail.it@merakjaya.co.id';
			$mail->Password = 'autoemail';
			
			if ($typeform=='Setuju'){
				$queryResign = "UPDATE MJ.MJ_T_RESIGN SET APPROVAL_MANAGER='Approved', TGL_APP_TERAKHIR = SYSDATE, LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE 
				WHERE ID=$TransID AND APPROVAL_MANAGER = 'In Proccess'";
				$resultResign = oci_parse($con,$queryResign);
				oci_execute($resultResign);

				$hdid = $TransID;
				$data="sukses";

				$bodyEmail = "Dear All,
				
Pengajuan resign dengan data dibawah telah di approve oleh Manager ($emp_name).

Nama Karyawan 	: $nama_karyawan
Perusahaan 		: $company
Departemen		: $dept
Posisi			: $pos
Grade			: $grade
Lokasi 			: $location
Tgl Masuk		: $tglmasuk
Tgl Resign		: $tglresign
Lama Bekerja		: $lamakerja
Dept Head		: $manager
Keterangan 		: $keterangan

Mohon untuk segera memproses pengajuan resign tersebut

Terima Kasih,";
			}
			else {
				if ($AlasanApp != '')
				{
					$queryResign = "UPDATE MJ.MJ_T_RESIGN SET APPROVAL_MANAGER='Disapproved', TGL_APP_TERAKHIR = SYSDATE, LAST_UPDATED_BY=$emp_id, 
					KETERANGAN_DISAPPROVE = '$AlasanApp' , PIC_DISAPPROVE = '$emp_name', LAST_UPDATED_DATE=SYSDATE 
					WHERE ID=$TransID AND APPROVAL_MANAGER ='In Proccess'";
					$resultResign = oci_parse($con,$queryResign);
					oci_execute($resultResign);

					$hdid = $TransID;
					$data="sukses";

					$bodyEmail = "Dear $nama_karyawan,
					
Pengajuan resign anda, dengan detail dibawah, tidak disetujui oleh $emp_name.

Nama Karyawan 	: $nama_karyawan
Perusahaan 		: $company
Departemen		: $dept
Posisi			: $pos
Grade			: $grade
Lokasi 			: $location
Tgl Masuk		: $tglmasuk
Tgl Resign		: $tglresign
Lama Bekerja		: $lamakerja
Dept Head		: $manager
Ket Disapprove  : $AlasanApp

Terima Kasih,";
				}
				else {
					$hdid = $TransID;
					$data = "gagal";
				}
			}
			
			if ($data=="sukses")
			{
				//if($AlasanApp != '')
				//{	
				$mail->Mailer = 'smtp';
				$mail->From = "autoemail.it@merakjaya.co.id";
				$mail->FromName = "Auto Email"; 
				$mail->Subject = $subjectEmail;
				$mail->Body = $bodyEmail;
				
				if ($production_level)
				{
					$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
					$mail->addCC('teguh@merakjaya.co.id');
					$mail->addCC('sulianti@merakjaya.co.id');
					$mail->AddAddress('$emailMan');
					$mail->addCC('veronika.putri@merakjaya.co.id');
					$mail->AddAddress($emailPemohon);
				}
				else{
					$mail->AddCC('m.ferdinansyah@merakjaya.co.id');
					$mail->addCC('maria.natalia@merakjaya.co.id');
					$mail->addCC('ridho.tri@merakjaya.co.id');
				}
				$success = $mail->Send();
			}
	}
}
	


$result = array('success' => true,
				'results' => $hdid,
				'rows' 	  => $data
			);
echo json_encode($result);

?>