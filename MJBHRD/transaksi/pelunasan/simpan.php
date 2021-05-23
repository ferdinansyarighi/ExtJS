<?PHP
//require('smtpemailattachment.php');
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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
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
$arrTransID=array();
$tglskr=date('Y-m-d'); 
$bulan = date('m', time());
$tahun = date('Y', time());
$tahunbaru=substr($tglskr, 0, 2);
$tahunGenNo=substr($tglskr, 0, 4);
$data="gagal";
 if(isset($_POST['nama_user']))
  {
  	$arrTransID=json_decode($_POST['arrTransID']);
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$nama_user=$_POST['nama_user'];
  	$nominal=$_POST['nominal'];
  	$gajigross=$_POST['gajigross'];
	$tipe=$_POST['tipe'];
  	$tgl=$_POST['tgl'];
  	$status=$_POST['status'];
	
	$TransID = 0;
	
	
	if ( $typeform == "tambah" ) {
		
		$valid_no_pinjaman = 'Y';
		
		$countID = count($arrTransID);
		
		for ( $x = 0; $x < $countID; $x++ ) {
			
			$TransID = $arrTransID[$x];		
			
			
			// Validasi apakah nomor pinjaman telah dibuatkan pelunasan
			
			$result_valid = oci_parse( $con,"
				SELECT  COUNT( * )
				FROM    MJ_T_PELUNASAN_PINJAMAN_DT
				WHERE   ID_PINJAMAN = $TransID
			");
			
			oci_execute( $result_valid );
			$row_valid = oci_fetch_row( $result_valid );
			$valid_flag = $row_valid[0];
			
			if ( $valid_flag == 0 ) {
				
				$valid_no_pinjaman = 'Y';
				
			} else {
				
				$x = $countID;
				$valid_no_pinjaman = 'N';
				
			}
			
			
			/*
			Ext.Ajax.request({
				url:'<?php echo 'cek_no_pinjaman_valid_save.php'; ?>',
					timeout: 500000,
					params:{
						
						hd_id : $TransID,
						
					},
					success:function(response) {
						
						var json = Ext.decode(response.responseText);
						var jsonresults = json.results;
						
						if ( json.success == true ) {
							
							if ( jsonresults == false )
							{
								
								$x = $countID;
								$cek_valid_number = 'N';
								
								alertDialog( 'Peringatan', 'Pelunasan atas nomor pinjaman ' + data[i].get('DATA_NO_PINJAM') + ' sudah pernah dilakukan. Data tidak dapat disimpan.' );
								
							} else {
								
								$cek_valid_number = 'Y';
								
							}
							
						} else {
							
							$x = $countID;
							$cek_valid_number = 'N';
							
							alertDialog( 'Kesalahan', 'Pengecekan pelunasan atas nomor pinjaman di detail gagal.' );
							
						}
						
					},
				method:'POST',
			});
			*/
			
		}
		
		// echo $valid_no_pinjaman; exit;
		
		if ( $valid_no_pinjaman == 'Y' ) 
		{
			
			if ( $tipe == 'POTONG GAJI' ) {
				$queryCek = "SELECT CASE WHEN EFFECTIVE_END_DATE <= LAST_DAY(TO_DATE('$tahun-$bulan-20', 'YYYY-MM-DD')) 
					THEN 'YES' ELSE 'NO' END STAT FROM PER_PEOPLE_F WHERE PERSON_ID = $nama_user";
				$resultCek = oci_parse($con,$queryCek);
				oci_execute($resultCek);
				$rowCek = oci_fetch_row($resultCek);
				$resign = $rowCek[0]; 
				
				if($nominal > $gajigross ){
					$simpan = 'NO';
				}else{
					$simpan = 'YES';
				}
			} else {
				$resign = 'NO';
				$simpan = 'YES';
			}
			
			
			if($resign == 'NO' && $simpan == 'YES'){
				$querycount = "SELECT COUNT(-1) 
				FROM MJ.MJ_M_GENERATENO 
				WHERE APPCODE='" . APPCODE . "' 
				AND TRANSAKSI_KODE='PELUNASAN'";
				$resultcount = oci_parse($con,$querycount);
				oci_execute($resultcount);
				$rowcount = oci_fetch_row($resultcount);
				$jumgen = $rowcount[0]; 
				
				$querycount = "SELECT COUNT(-1) 
				FROM MJ.MJ_M_GENERATENO 
				WHERE APPCODE='" . APPCODE . "' 
				AND TRANSAKSI_KODE='PELUNASAN'";
				$resultcount = oci_parse($con,$querycount);
				oci_execute($resultcount);
				$rowcount = oci_fetch_row($resultcount);
				$jumgen = $rowcount[0]; 
				
				if ($jumgen>0){
					$query = "SELECT LASTNO 
					FROM MJ.MJ_M_GENERATENO 
					WHERE APPCODE='" . APPCODE . "' 
					AND TRANSAKSI_KODE='PELUNASAN'";
					$result = oci_parse($con,$query);
					oci_execute($result);
					$rowGLastno = oci_fetch_row($result);
					$lastNo = $rowGLastno[0];
				} else {
					$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
					oci_execute($resultSeq);
					$rowHSeq = oci_fetch_row($resultSeq);
					$gencountseq = $rowHSeq[0];
				
					$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TRANSAKSI_KODE) 
					VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', 'PELUNASAN')";
					$result = oci_parse($con,$query);
					oci_execute($result);
					$lastNo = 0;
				} 
				
				$querycount = "SELECT TAHUN 
				FROM MJ.MJ_M_GENERATENO 
				WHERE APPCODE='" . APPCODE . "' 
				AND TRANSAKSI_KODE='PELUNASAN'";
				$resultcount = oci_parse($con,$querycount);
				oci_execute($resultcount);
				$rowcount = oci_fetch_row($resultcount);
				$thnGen = $rowcount[0]; 
				
				if($thnGen!=$tahunGenNo){
					$lastNo = 0;
					$lastNo=$lastNo+1;
					$queryLast = "UPDATE MJ.MJ_M_GENERATENO 
					SET LASTNO='$lastNo', TAHUN='$tahunGenNo' 
					WHERE APPCODE='" . APPCODE . "' 
					AND TRANSAKSI_KODE='PELUNASAN'";
					$resultLast = oci_parse($con,$queryLast);
					oci_execute($resultLast);
				} else {
					$lastNo=$lastNo+1;
					$queryLast = "UPDATE MJ.MJ_M_GENERATENO 
					SET LASTNO='$lastNo' 
					WHERE APPCODE='" . APPCODE . "' 
					AND TRANSAKSI_KODE='PELUNASAN'";
					$resultLast = oci_parse($con,$queryLast);
					oci_execute($resultLast);
				}
				
				$jumno=strlen($lastNo);
				if($jumno==1){
					$nourut = "0000".$lastNo;
				} else if ($jumno==2){
					$nourut = "000".$lastNo;
				} else if ($jumno==3){
					$nourut = "00".$lastNo;
				} else if ($jumno==4){
					$nourut = "0".$lastNo;
				} else {
					$nourut = $lastNo;
				}
				
				$noPelunasan = "HRD/PPK/" . $bulan. $tahun . "/" . $nourut;
				
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_PELUNASAN_PINJAMAN_SEQ.nextval FROM dual"); 
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$hdid = $row[0];
				
				$sqlQuery = "INSERT INTO MJ.MJ_T_PELUNASAN_PINJAMAN (ID, NO_PELUNASAN, PERSON_ID, TIPE_PELUNASAN, NOMINAL, TGL_PELUNASAN, STATUS, CREATED_BY, CREATED_DATE) 
				VALUES ( $hdid, '$noPelunasan', $nama_user, '$tipe', $nominal, TO_DATE('$tgl', 'YYYY-MM-DD'), '$status', $emp_id, SYSDATE)";
				$result = oci_parse($con,$sqlQuery);
				
				if(oci_execute($result)) {
					
					$countID = count($arrTransID);
					
					for ($x=0; $x<$countID; $x++) {
						
						$TransID = $arrTransID[$x];
						
						$resultSeqDet = oci_parse($con,"SELECT MJ.MJ_T_PELUNASAN_PINJAMAN_DT_SEQ.nextval FROM dual"); 
						oci_execute($resultSeqDet);
						$rowDet = oci_fetch_row($resultSeqDet);
						$hdidDet = $rowDet[0];


						$sqlOutstanding = "
						SELECT  ( MTP.NOMINAL * MTP.JUMLAH_CICILAN_AWAL )
								- NVL (
										  ( SELECT  SUM( NOMINAL )
											FROM    MJ_T_PINJAMAN_DETAIL
											WHERE   MJ_T_PINJAMAN_ID = MTP.ID ),
									  0 ) OUTSTANDING
						FROM    MJ_T_PINJAMAN MTP
						WHERE   MTP.ID = $TransID
						";
						
						$resultOutstanding = oci_parse( $con, $sqlOutstanding );
						oci_execute( $resultOutstanding );
						$rowOutstanding = oci_fetch_row( $resultOutstanding );
						$nominal_oustanding = $rowOutstanding[0];

						
						$sqlQueryDt = "
						INSERT INTO MJ.MJ_T_PELUNASAN_PINJAMAN_DT (ID, HDID, ID_PINJAMAN, NOMINAL, CREATED_BY, CREATED_DATE) 
						VALUES ( $hdidDet, '$hdid', $TransID, $nominal_oustanding, $emp_id, SYSDATE)
						";
						
						$resultDt = oci_parse($con,$sqlQueryDt);
						oci_execute($resultDt);

						
						// Insert ke detail peminjaman dengan SOURCE = 'PELUNASAN'
						
						$resultSeqDtlPinjaman = oci_parse($con,"SELECT MJ.MJ_T_PINJAMAN_DETAIL_SEQ.nextval FROM dual"); 
						oci_execute( $resultSeqDtlPinjaman );
						$rowDtlPinjaman = oci_fetch_row( $resultSeqDtlPinjaman );
						$idDtlPinjaman = $rowDtlPinjaman[0];
						
						
						$queryTglPelunasan = "
							SELECT  TO_CHAR( TGL_PELUNASAN, 'YYYY' ) TAHUN, TO_NUMBER( TO_CHAR( TGL_PELUNASAN, 'MM' ) ) BULAN
							FROM    MJ_T_PELUNASAN_PINJAMAN
							WHERE   ID = $hdid
						";
						
						$resultTglPelunasan = oci_parse( $con, $queryTglPelunasan );
						oci_execute( $resultTglPelunasan );
						$rowTglPelunasan = oci_fetch_row( $resultTglPelunasan );
						
						$tahun_pelunasan = $rowTglPelunasan[0];
						$bulan_pelunasan = $rowTglPelunasan[1];
						
						
						$sqlDtlPinjaman = "
						INSERT INTO MJ.MJ_T_PINJAMAN_DETAIL (
							ID, 
							MJ_T_PINJAMAN_ID, 
							TAHUN, 
							BULAN, 			
							NOMINAL, 
							STATUS, 
							CREATED_BY, 
							CREATED_DATE,
							SOURCE
						) 
						VALUES (
							$idDtlPinjaman, 
							$TransID, 
							$tahun_pelunasan,  
							$bulan_pelunasan,
							$nominal_oustanding, 
							1, 
							1, 
							SYSDATE,
							'PELUNASAN'
						)";
						
						// echo $sqlDtlPinjaman; exit;
						
						$resultDtlPinjaman = oci_parse( $con, $sqlDtlPinjaman );
						oci_execute( $resultDtlPinjaman );
						
						
						
						$sqlHeaderPinjaman = "
							UPDATE MJ_T_PINJAMAN
							SET	JUMLAH_CICILAN = 0
							WHERE ID = $TransID
						";
						
						$resultHeaderPinjaman = oci_parse( $con, $sqlHeaderPinjaman );
						oci_execute( $resultHeaderPinjaman );
						
					}
					
					$data = "sukses";
					
					$resultNo = oci_parse($con,"SELECT NO_PELUNASAN FROM MJ.MJ_T_PELUNASAN_PINJAMAN WHERE ID = $hdid");
					oci_execute($resultNo);
					$rowNo = oci_fetch_row($resultNo);
					$noPelunasan = $rowNo[0];
					
					$result = array('success' => true,
									'results' => $hdid .'|'. $noPelunasan,
									'rows' => $data
								);
					
				}
				
			}
		
		} else {
			
			// echo  ' $TransID: ' . $TransID; exit;
			
			$data = "gagal_validasi";

			$queryNoPinjaman = "
				SELECT  NOMOR_PINJAMAN
				FROM    MJ_T_PINJAMAN
				WHERE   ID = $TransID
			";
			
			$resultNoPinjaman = oci_parse( $con, $queryNoPinjaman );
			oci_execute( $resultNoPinjaman );
			$rowNoPinjaman = oci_fetch_row( $resultNoPinjaman );
			
			$noPinjamanResult = $rowNoPinjaman[0];
			
			$result = array('success' => true,
							'results' => $hdid .'|'. $noPinjamanResult,
							'rows' => $data
						);
			
		}
		
		
	}  // end of if($typeform == "tambah")
	
	
	// else {
		// // $resultCek = oci_parse($con,"SELECT * from MJ.MJ_T_PELUNASAN_PINJAMAN where id = $hdid and STATUS_DOKUMEN <> 'Approved'
			// // AND TINGKAT = 0"); 
		// // oci_execute($resultCek);
		// // $rowCek = oci_fetch_row($resultCek);
		// // $cek = $rowCek[0];
		
		// // if($cek == ''){
			// // $data="gagal";
		// // }else{
			// $sqlQuery = "UPDATE MJ.MJ_T_PELUNASAN_PINJAMAN SET TIPE_PELUNASAN = '$tipe', TGL_PELUNASAN = TO_DATE('$tgl', 'YYYY-MM-DD'), NOMINAL='$nominal', STATUS='$status', LAST_UPDATED_BY=$emp_id, LAST_UPDATED_DATE=SYSDATE WHERE ID=$hdid ";
			// //ECHO $sqlQuery;EXIT;
			// $result = oci_parse($con,$sqlQuery);
			// oci_execute($result);
						
			// $data="sukses";
		// //}
	// }
	

	
	/*
	$result = array('success' => true,
					'results' => $hdid .'|'. $noPelunasan,
					'rows' => $data
				);
	*/
	
	echo json_encode($result);
  }


?>