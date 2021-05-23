<?php
include '../../main/koneksi.php';
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
  
$kategori = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$Dept = "";
$tingkat = 0;
$count = 0;
$countSpv = 0;
$countMan = 0;
$rangeDate = 0;
if (isset($_GET['kategori']) || isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$tffilter = $_GET['kategori'];
	$tglfrom = $_GET['tglfrom'];
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglto = $_GET['tglto'];
	$namaKaryawan = $_GET['namaKaryawan'];
	$plant = $_GET['plant'];
	$noDok = $_GET['noDok'];
	$Dept = $_GET['Dept'];
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	
	if ($tffilter!='All'){
		$queryfilter .=" AND KATEGORI LIKE '%$tffilter%' ";
	}
	if ($namaKaryawan!=''){
		$queryfilter .=" AND PERSON_ID = '$namaKaryawan' ";
	}
	if ($plant!=''){
		$queryfilter .=" AND PLANT LIKE '%$plant%' ";
	}
	if ($noDok!=''){
		$queryfilter .=" AND NOMOR_SIK LIKE '%$noDok%' ";
	}
	if ($Dept!=''){
		$queryfilter .=" AND DEPARTEMEN LIKE '%$Dept%' ";
	}
} 

$query = "SELECT DISTINCT ID AS hd_id
	, NOMOR_SIK AS DATA_NO_SIK
	, PEMOHON AS DATA_PEMOHON
	, PLANT AS DATA_PLANT
	, KATEGORI AS DATA_KATEGORI
	, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
	, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
	, KETERANGAN AS DATA_KETERANGAN
	, JAM_FROM AS DATA_JAM_FROM
	, JAM_TO AS DATA_JAM_TO 
	, DEPARTEMEN AS DATA_DEPARTEMEN
	, IJIN_KHUSUS AS DATA_KHUSUS
	, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') DATA_CREATED_DATE
	FROM MJ.MJ_T_SIK 
	WHERE STATUS='1' 
	AND STATUS_DOK='Approved' 
	$queryfilter
	";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$TransID=$row[0];
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_SIK']=$row[1];
	$record['DATA_PEMOHON']=$row[2];
	$record['DATA_PLANT']=$row[3];
	$record['DATA_KATEGORI']=$row[4];
	$record['DATA_TGL_FROM']=$row[5];
	$record['DATA_TGL_TO']=$row[6];
	$record['DATA_KETERANGAN']=$row[7];
	$record['DATA_JAM_FROM']=$row[8];
	$record['DATA_JAM_TO']=$row[9];
	$record['DATA_ALASAN']='';
	$record['DATA_DEPARTEMEN']=$row[10];
	$record['DATA_KHUSUS']=$row[11];
	$record['DATA_CREATED_DATE']=$row[12];
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID";
	$resultAtt = oci_parse($con, $queryAtt);
	oci_execute($resultAtt);
	$doccount=0;
	$dataAtt='';
	while($rowAtt = oci_fetch_row($resultAtt))
	{
		$vTransID=$rowAtt[0];
		$vFilename=$rowAtt[1];
		$vFilesize=$rowAtt[2];
		$vFiletype=$rowAtt[3];
		$vFileuser=$rowAtt[4];
		$vFiledate=$rowAtt[5];
		$ekstensi	= end(explode(".", $vFilename));
		$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
		//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		if($doccount==0){
			$dataAtt = $docattachment;
		} else {
			$dataAtt .= ", " . $docattachment;
		}
		//echo $docattachment;
		//$mail->addAttachment( $docattachment );
		$doccount++;
	}
	$data[]=$record;
	$countSpv++;
}

if ($count==0 && $countSpv==0 && $countMan==0)
{
	$data="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>