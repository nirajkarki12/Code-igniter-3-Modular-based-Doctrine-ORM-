<?php
/*
* Mailer library class
* load or require mailer class from third party
*/
class Mailer
{
	public function __construct(){		
		require_once(APPPATH."third_party/Mailer/Mailer.php");
	}
	
}