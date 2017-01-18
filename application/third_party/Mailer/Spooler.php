<?php

namespace Mailer;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_FileSpool;
use Swift_SpoolTransport;

class Spooler{
	public function startSendingMail()
	{
		// $CI =& get_instance();
		require_once 'vendor/autoload.php';
		
		if (!defined('STDIN'))
		{
			if(stristr($_SERVER['HTTP_HOST'], '10.13.209.') || $_SERVER['HTTP_HOST'] == "localhost" ){
				require_once 'application/config/email.php';
			}else{
				require_once 'application/config/production/email.php';
			}
		}else{
			require_once 'application/config/email.php';
		}

		$spool = new Swift_FileSpool(__DIR__."/spool");

		//create a new instance of Swift_SpoolTransport that accept an argument as Swift_FileSpool
		$transport = Swift_SpoolTransport::newInstance($spool);

		// $smtp_host = $CI->config->item('smtp_host');
		// $smtp_port = $CI->config->item('smtp_port');
		// $smtp_username = $CI->config->item('smtp_username');
		// $smtp_password = $CI->config->item('smtp_password');
		// $smtp_type = $CI->config->item('smtp_type');

		$smtp_host = $config['smtp_host'];
		$smtp_port = $config['smtp_port'];
		$smtp_username = $config['smtp_username'];
		$smtp_password = $config['smtp_password'];
		$smtp_type = $config['smtp_type'];


		$realTransport = Swift_SmtpTransport::newInstance($smtp_host, $smtp_port, $smtp_type)
			->setUsername($smtp_username)
			->setPassword($smtp_password);

		$spool = $transport->getSpool();
		$spool->setMessageLimit(100);
		$spool->setTimeLimit(100);
		$sent = $spool->flushQueue($realTransport);
	}
}

$spooler = new Spooler;
$spooler->startSendingMail();
?>