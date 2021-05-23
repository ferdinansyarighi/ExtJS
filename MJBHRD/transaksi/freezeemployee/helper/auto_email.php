<?php
	require_once('smtpemailattachment.php');
	date_default_timezone_set("Asia/Jakarta");
	
	/*
	* function untuk kirim email
	* var $production_level status program
	*	jika TRUE berarti program sudah live,
	* 	jika false program masih dalam tahap pengembangan
	* $dt_in['subject'] berisi subject/judul dari email
	* $dt_in['body'] berisi body/isi dari email
	* $dt_in['to'] berupa variabel array berisi email tujuan
	* $dt_in['cc'] berupa variabel array berisi email cc
	* $dt_in['attc'] berupa variabel array berisi attachment email
	*/
	function mjb_send_mail($production_level = FALSE, $dt_in)
	{
		$production_level=FALSE;
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
		
		$mail->Subject = $dt_in['subject'];	
		
		if(array_key_exists('to', $dt_in))
		{
			foreach($dt_in['to'] as $eml_to)
			{
				if(!empty($eml_to))
				{
					if($production_level==FALSE)
					{
						$dt_in['body'] .= "\n\n To: ".$eml_to."\n";
					}
					else
					{
						$mail->AddAddress($eml_to);
					}
				}
			}
		}
		
		if(array_key_exists('cc', $dt_in))
		{
			foreach($dt_in['cc'] as $eml_cc)
			{
				if(!empty($eml_cc))
				{
					if($production_level==FALSE)
					{
						$dt_in['body'] .= "\n cc: ".$eml_cc."\n";
					}
					else
					{
						$mail->addCC($eml_cc);
					}
				}
			}
		}
		
		if(array_key_exists('attc', $dt_in))
		{
			foreach($dt_in['attc'] as $eml_attc)
			{
				if(!empty($eml_attc))
				{
					$mail->addAttachment($eml_attc);
				}
			}
		}
		
		if($production_level==FALSE)
		{
			$mail->addCC( 'fajar.setiawan@merakjaya.co.id' );
			$mail->addCC( 'ridho.tri@merakjaya.co.id' );
		}
		
		$mail->Body = $dt_in['body'];
											
		$success = $mail->Send();
	}	
?>