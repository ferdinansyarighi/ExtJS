<?php
	$cut_off_monthly_min = 21;
	$cut_off_monthly_max = 20;
	$cut_off_weekly_min = 'MONDAY';
	$cut_off_weekly_max = 'SUNDAY';
	$cut_off_n_weekly_min = 1;
	$cut_off_n_weekly_max = 7;
	$tot_ddl = 12;
	
	$data = array();
	$date = date('Y-m');
	
	$salary_type = $_GET['salary_type'];
	
	// if($salary_type == 'monthly')
	if($salary_type == 'weekly')
	{
		$today = date('Y-m-d');
		$day = date('N');
		// $day_date = date('d');
		// $new_day_date_min = 8 - $day;
		// if today not monday
		if ($day > $cut_off_n_weekly_min)
			$new_day_date_min = $day - ($day - 1);
		// if today is monday
		else
			$new_day_date_min = 0;
		// $date_min = strtotime($date."-20");
		$date_min = strtotime("+".$new_day_date_min." day", strtotime($today));
		// $date_max = strtotime("+".$new_day_date_min." day");
		$dt_co_max = $cut_off_n_weekly_max - $cut_off_n_weekly_min;
		for($i=0; $i<=$tot_ddl; $i++)
		{
			$dt_min = date('Y-m-d', strtotime("-".$i." week", $date_min));
			$dt_max = date('Y-m-d', strtotime("+".$dt_co_max." day", strtotime($dt_min)));
			$record['DATA_VALUE'] = $dt_min.' s/d '.$dt_max;
			$record['DATA_NAME'] = $dt_min.' s/d '.$dt_max;
			$data[] = $record;
		}
	}
	else
	{
		$date_max = strtotime($date."-".$cut_off_monthly_max);
		
		for($i=0; $i<=$tot_ddl; $i++)
		{
			$dt_max = date('Y-m-d', strtotime("-".$i." month", $date_max));
			$dt_min = date('Y-m-d', strtotime("-1 month", strtotime( date('Y-m')."-".$cut_off_monthly_min)));
			$record['DATA_VALUE'] = $dt_min.' s/d '.$dt_max;
			$record['DATA_NAME'] = $dt_min.' s/d '.$dt_max;
			$data[] = $record;
		}
	
		$data[] = $record;
	}
	
	echo json_encode($data);
	
?>