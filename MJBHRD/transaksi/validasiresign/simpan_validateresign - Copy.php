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

 if(isset($_POST['typeform']))
  {
	$typeform=$_POST['typeform'];
	if ($typeform=='Setuju') {
		$Keputusan = 'Validated';
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
	
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD/MM/YY'),TO_CHAR(TGL_RESIGN, 'DD/MM/YY'),TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_VALIDASI
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
		
	$query = "SELECT EMAIL_ADDRESS
	FROM APPS.PER_PEOPLE_F PPF
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '$nama_karyawan'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	$emailPemohon=$row[0];
	
	$queryEmailman = "SELECT DISTINCT PPF.EMAIL_ADDRESS FROM PER_PEOPLE_F PPF, MJ.MJ_T_RESIGN RES
	WHERE PPF.FULL_NAME LIKE '%$manager%'
	AND RES.ID = $id_pengajuan
	AND PPF.EMAIL_ADDRESS IS NOT NULL";
	$resultEmailman = oci_parse($con, $queryEmailman);
	oci_execute($resultEmailman);
	$rowEmailman = oci_fetch_row($resultEmailman);
	$emailMan=$rowEmailman[0];

	$queryPembuat = "SELECT DISTINCT PPF.EMAIL_ADDRESS FROM PER_PEOPLE_F PPF, MJ.MJ_T_RESIGN RES
	WHERE PPF.PERSON_ID = $pembuat
	AND PPF.PERSON_ID = RES.CREATED_BY
	AND RES.ID = $id_pengajuan
	AND PPF.EMAIL_ADDRESS IS NOT NULL";
	$resultPembuat = oci_parse($con, $queryPembuat);
	oci_execute($resultPembuat);
	$rowPembuat = oci_fetch_row($resultPembuat);
	$emailPembuat=$rowPembuat[0];

	$emailJam = date("H");
	$subjectEmail = "[Autoemail] ($Keputusan) Pengajuan Resign $no_pengajuan";
	
	$mail = new PHPMailer();
	$mail->Hostname = '192.168.0.35';
	$mail->Port = 25;
	$mail->Host = "192.168.0.35";
	$mail->SMTPAuth = true;
	$mail->Username = 'autoemail.it@merakjaya.co.id';
	$mail->Password = 'autoemail';

	$queryResign = "UPDATE MJ.MJ_T_RESIGN SET PIC_VALIDASI = '$emp_name', VALIDASI='Validated', TGL_APP_TERAKHIR = SYSDATE, LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE 
	WHERE ID=$TransID";
	$resultResign = oci_parse($con,$queryResign);
	oci_execute($resultResign);
	
	$hdid = $TransID;
	$data="sukses";

	$bodyEmail = "Dear All,
				
Telah dilakukan BAST dengan detail informasi sebagai berikut,

Nama Karyawan 	: $nama_karyawan
No. Dokumen		: $no_pengajuan
Tanggal			: $tglskr
Note	 		: $arrAlasan[$x]

Mohon untuk segera dilakukan proses ex-employee karyawan.

Terima Kasih.";

	$mail->Mailer = 'smtp';
	$mail->From = "autoemail.it@merakjaya.co.id";
	$mail->FromName = "Auto Email"; 
	$mail->Subject = $subjectEmail;
	$mail->Body = $bodyEmail;
			
/*	$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
	$mail->addCC('teguh@merakjaya.co.id');
	$mail->addCC('sulchan.masyhari@merakjaya.co.id');
	$mail->addCC('$emailMan');
	$mail->addCC('sonia.adina@merakjaya.co.id');
	$mail->addCC($emailPemohon);*/
	$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
	$mail->addCC('maria.natalia@merakjaya.co.id');

	$success = $mail->Send();
} 

$result = array('success' => true,
				'results' => $hdid,
				'rows' 	  => $data
			);
echo json_encode($result);

?>