<?php

class JS_Controller extends MY_Controller
{
	public function __construct(){
		$this->output->set_content_type('text/javascript');
	}
	
	public function core(){
		$this->load->view('js/core');
	}
}