<?PHP
include '../../main/koneksi.php';

$vhasil_cek="";


// Dipanggil saat validasi pengisian start potongan

if( isset( $_POST['param_bulan'] ) ) {
	
	$param_bulan=$_POST['param_bulan'];
	$param_tahun=$_POST['param_tahun'];
	
	if ( strlen( $param_bulan ) == 1 ) {
		$param_bulan = "0" . $param_bulan;
	}
	
	$query_check = "
					SELECT  TO_CHAR( ADD_MONTHS( SYSDATE, 1 ), 'MM' ) BULAN,
							TO_CHAR( ADD_MONTHS( SYSDATE, 1 ), 'YYYY' ) TAHUN
					FROM    DUAL
					";
				
	$result_check = oci_parse( $con, $query_check );
	oci_execute($result_check);
	$row_check = oci_fetch_row($result_check);
	
	$bln_check = $row_check[0];
	$thn_check = $row_check[1];
	
	if ( ( $param_tahun . $param_bulan ) >= ( $thn_check . $bln_check ) )
	{
		$vhasil_cek="1";
	} else {
		$vhasil_cek="0";
	}
	
	
	$query_blnthn = "
					SELECT  TO_CHAR( ADD_MONTHS( SYSDATE, 1 ), 'MM' ) BULAN,
							TO_CHAR( ADD_MONTHS( SYSDATE, 1 ), 'YYYY' ) TAHUN
					FROM    DUAL
					";
	
	$result = oci_parse($con, $query_blnthn);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
	$bln_result=$row[0];
	$thn_result=$row[1];
	
	$result = array(
				'bln_result' => $bln_result, 
				'thn_result' => $thn_result,
				'vhasil_cek' => $vhasil_cek,
				'cek_1' => $param_tahun . $param_bulan,
				'cek_2' => $thn_check . $bln_check
			);
	echo json_encode($result);


// Dipanggil saat pengisian default start potongan
 
} else {
	
	
	$query_blnthn = "
						SELECT  TO_NUMBER( TO_CHAR( ADD_MONTHS( SYSDATE, 1 ), 'MM' ) ) BULAN,
								TO_NUMBER( TO_CHAR( ADD_MONTHS( SYSDATE, 1 ), 'YYYY' ) ) TAHUN
						FROM    DUAL
					";
	
	$result = oci_parse($con, $query_blnthn);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
	$bln_result=$row[0];
	$thn_result=$row[1];
	
	$result = array(
				'bln_result' => $bln_result, 
				'thn_result' => $thn_result
			);
	echo json_encode($result);
	
}

?>