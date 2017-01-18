<?php
namespace Mailer;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_FileSpool;
use Swift_SpoolTransport;

class Mailer {

	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function _sendMail($from = array(), $to = array(), $subject, $message, $smtp=array()){
		// $CI  = & get_instance();
		if(!$smtp)
		{
			$smtp_host = $this->CI->config->item('smtp_host');
			$smtp_port = $this->CI->config->item('smtp_port');
			$smtp_username = $this->CI->config->item('smtp_username');
			$smtp_password = $this->CI->config->item('smtp_password');
			$smtp_type = $this->CI->config->item('smtp_type');
		}else{
			$smtp_host = $smtp['smtp_host'];
			$smtp_port = $smtp['smtp_port'];
			$smtp_username = $smtp['smtp_username'];
			$smtp_password = $smtp['smtp_password'];
			$smtp_type = $smtp['smtp_type'];
		}
		
		$transport = Swift_SmtpTransport::newInstance($smtp_host, $smtp_port, $smtp_type)
		->setUsername($smtp_username)
		->setPassword($smtp_password);

		$mailer = Swift_Mailer::newInstance($transport);

		$data['message'] = $message;

		$html = $this->CI->load->view('admin/email_layout', $data, TRUE);

		$mail = Swift_Message::newInstance($subject)
		->setFrom(array($from['from_email'] => $from['from_name']))
		->setTo(array($to['to_email'] => $to['to_name']))
		->setBody($html);

		$mail->setContentType("text/html");

		return  $mailer->send($mail);
	}

	public function sendMail($from = array(), $to = array(), $subject, $message)
	{
		$ret = $this->spoolMail($from, $to, $subject, $message);
		$this->flushSpool();

		return $ret;
	}

	public function spoolMail($from = array(), $to = array(), $subject, $message){

		// Setup the spooler, passing it the name of the folder to spool to
		$spool = new Swift_FileSpool(__DIR__."/spool");

		// Setup the transport and mailer
		$transport = Swift_SpoolTransport::newInstance($spool);
		$mailer = Swift_Mailer::newInstance($transport);

		$data['message'] = $message;

		$html = $this->CI->load->view('admin/email_layout', $data, TRUE);
		
		$mail = Swift_Message::newInstance($subject)
		->setFrom(array($from['from_email'] => $from['from_name']))
		->setTo(array($to['to_email'] => $to['to_name']))
		->setBody($html);

		$mail->setContentType("text/html");

		return  $mailer->send($mail);
	}

	public function flushSpool()
	{
		$cmd = 'php '.APPPATH.'/third_party/Mailer/Spooler.php';
	    $logFile = APPPATH.'/third_party/Mailer/mail_log.txt';
	    if (substr(php_uname(), 0, 7) == "Windows"){ 
	        pclose(popen("start /B ". $cmd. ' > "'.$logFile.'" 2>&1', "r"));  
	    } 
	    else { 
	        // exec($cmd . ' > "'.$logFile.'" 2>&1');
	        exec($cmd . " > /dev/null &");
	    } 
	}	
}

