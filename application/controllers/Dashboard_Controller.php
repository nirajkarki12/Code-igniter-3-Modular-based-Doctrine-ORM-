<?php

class Dashboard_Controller extends Admin_Controller
{
	public function __construct()
	{
		$this->mainmenu = MAINMENU_DASHBOARD;
		parent::__construct();
	}	
	
	public function index()
	{
		$this->load->helper('dashboard');
		$this->templatedata['maincontent'] = 'dashboard/dashboard';
		$this->templatedata['pageTitle'] = 'Dashboard';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function pagenotfound(){
		$this->breadcrumb->append_crumb('404 Error', site_url());
		$this->templatedata['maincontent'] = 'dashboard/error_404';
		$this->templatedata['pageTitle'] = '404 Error';
		$this->load->theme('master',$this->templatedata);
	}

}
?>