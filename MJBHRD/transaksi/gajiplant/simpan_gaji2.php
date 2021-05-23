<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database
 
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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
 
$nama=""; 
$plant=""; 
$periode=""; 
$vPeriode1="";
$vPeriode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tglskrdipakai=date('d F Y'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";

$eGajiHarian = 76200;
$satuan = '%';
$value = 10;


				$totalUangLembur = 0;
				$queryJamLembur = "SELECT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, NVL(MTSI.JAM_TO, NVL(MTT2.JAM_KELUAR, '23:59'))), MTS.TANGGAL_SPL        
                FROM MJ.MJ_T_SPL MTS
                INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
                LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=33273
                LEFT JOIN MJ.MJ_T_TIMECARD MTT2 ON MTT2.TANGGAL=MTS.TANGGAL_SPL AND MTT2.STATUS=248 AND MTT2.PERSON_ID=33273
                LEFT JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
                AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
                AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
                AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
                WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
                AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '2018-02-12'
                AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '2018-02-18'
                AND MTSD.PERSON_ID=33273
                AND MTT.ID IS NOT NULL
                and MTSD.TOTAL_JAM = '23:28' 
                ORDER BY MTS.TANGGAL_SPL
				";
				//echo $queryJamLembur;
				$resultJamLembur = oci_parse($con,$queryJamLembur);
				oci_execute($resultJamLembur);
				while($rowJamLembur = oci_fetch_row($resultJamLembur)){
					$vTotSPL = 0;
					$vJamLembur = $rowJamLembur[0]; 
					$ArrTempMasuk = explode(":", $vJamLembur);
					$vTempJamMasuk = $ArrTempMasuk[0];
					$vTempMenitMasuk = $ArrTempMasuk[1];
					$vTempJamMasuk = ($vTempJamMasuk*60) + $vTempMenitMasuk;
					//echo $vTempJamMasuk;
					$vJamLemburMasuk = $rowJamLembur[1]; 
					$ArrTempMasukSpl = explode(":", $vJamLemburMasuk);
					$vTempJamMasukSpl = $ArrTempMasukSpl[0];
					$vTempMenitMasukSpl = $ArrTempMasukSpl[1];
					$vTempJamMasukSpl = ($vTempJamMasukSpl*60) + $vTempMenitMasukSpl;
					//echo $vTempJamMasukSpl;
					$vJamLemburKeluar = $rowJamLembur[2]; 
					$ArrTempKeluarSpl = explode(":", $vJamLemburKeluar);
					$vTempJamKeluarSpl = $ArrTempKeluarSpl[0];
					$vTempMenitKeluarSpl = $ArrTempKeluarSpl[1];
					$vTempJamKeluarSpl = ($vTempJamKeluarSpl*60) + $vTempMenitKeluarSpl;
					
					if($vTempJamKeluarSpl < $vTempJamMasukSpl){
						$vTempJamKeluarSpl = $vTempJamKeluarSpl + 1440;
					}
					$vTotSPL = $vTempJamKeluarSpl - $vTempJamMasukSpl;
					echo $vTempJamKeluarSpl.' - '.$vTempJamMasukSpl.' = '.$vTotSPL.' | '.$vTempJamMasuk;
					//Mencari yg terkecil
					if($vTotSPL > $vTempJamMasuk){
						if($vTempJamMasuk > 0){
								echo '<br/>Menit: '.$vTempJamMasuk;
							if($satuan == 'IDR'){
								$uangLembur = $value*$vTempJamMasuk;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
							}else{
								$uangLembur = $eGajiHarian*($value/100)*$vTempJamMasuk;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
								echo ' - : '.$uangLembur/60;
							}
						}
					} else {
						if($vTotSPL > 0){
								echo '<br/>Menit: '.$vTotSPL;
							if($satuan == 'IDR'){
								$uangLembur = $value*$vTotSPL;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
							}else{
								$uangLembur = $eGajiHarian*($value/100)*$vTotSPL;
								$totalUangLembur = $totalUangLembur + ($uangLembur/60);
								echo ' - : '.$uangLembur/60;
							}
						}
					}
				}
				$nominal = $totalUangLembur;
				echo '<br/>Total: '.$nominal;exit;
				
echo json_encode('');
?>