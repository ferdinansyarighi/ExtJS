<?php
	function get_tingkat($dept, $pos){
		$tingkat = -1;
		$apv_status = '';
		$dt_tingkat = array(array( 'DEPT' => NULL, 'POS' => 'MGR', 'TINGKAT' => 0, 'APV_STATUS'=>'P', ),
							array( 'DEPT' => 'HRD', 'POS' => 'MGR', 'TINGKAT' => 1,	'APV_STATUS'=>'P', ),
							);
		
		foreach($dt_tingkat as $key_dt_tingkat => $val_dt_tingkat){
			
			if($val_dt_tingkat['DEPT'] == $dept && $val_dt_tingkat['POS'] == $pos){
				$tingkat = $val_dt_tingkat['TINGKAT'];
				$apv_status = $val_dt_tingkat['APV_STATUS'];
			}
			elseif($val_dt_tingkat['DEPT'] == NULL && $val_dt_tingkat['POS'] == $pos && $tingkat < 0){
				$tingkat = $val_dt_tingkat['TINGKAT'];
				$apv_status = $val_dt_tingkat['APV_STATUS'];
			}
		}
		
		$data['tingkat'] = $tingkat;
		$data['apv_status'] = $apv_status;
		
		return $data;
	}
?>