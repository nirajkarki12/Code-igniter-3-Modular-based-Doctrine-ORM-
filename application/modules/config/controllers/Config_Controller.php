<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_Controller extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if(!user_access('config')) redirect();
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));

		$widgetArray = \Options::get('widgets') ?: false;

		//report listing
		$repo=$this->doctrine->em->getRepository('models\Common\ReportGroup');
		$reportList=$repo->getReportGroups();
		
		$subReports=array();
		
		foreach($reportList as $rep_group)
		{
			$subReports[$rep_group['id']]=$this->doctrine->em->getRepository('models\Common\Report')->findBy(array('reportgroup'=>$rep_group['id']));
		}

		$otherreports=$this->doctrine->em->getRepository('models\Common\Report')->findBy(array('reportgroup'=>NULL));

		$this->templatedata['reports'] = &$reportList;
		$this->templatedata['subreports'] = &$subReports;
		$this->templatedata['otherreports']=&$otherreports;
		$this->templatedata['widgetArray'] = $widgetArray;
		$this->templatedata['pageTitle'] = 'Configuration';
		$this->templatedata['maincontent'] = 'config/config';
		$this->load->theme('master',$this->templatedata);

	}

	public function settings(){
		if (!Current_User::isSuperUser()) redirect();
		$POST = $this->input->post();
		$params = $POST['params'];
		unset($POST['params']);

		if($POST['widget'])
		{
			if(!isset($POST['widgets']))
			{
				\Options::update('widgets','');
			}else{
				foreach($POST as $k => $v){
					\Options::update($k,$v);
				}
			}
			$this->message->set('Settings has beeen updated successfully.', 'success', TRUE, 'feedback');
			redirect('config#'.$params);
		
		}elseif($POST['notice']){
			foreach($POST as $k => $v){
				\Options::update($k,$v);
			}

			$this->message->set('Notice has beeen updated successfully.', 'success', TRUE, 'feedback');
			redirect('config#'.$params);

		}else{
			foreach($POST as $k => $v){
				\Options::update($k,$v);
			}

			$this->message->set('Settings has beeen updated successfully.', 'success', TRUE, 'feedback');
			redirect('config#'.$params);
		}

		$this->message->set('No data to Update', 'error', TRUE, 'feedback');
		redirect('config#'.$params);

	}

	public function letter($format)
	{
		try {
			
		if(!user_access('config')) redirect();
		if(!\Options::get('letter'))
		{
			$this->message->set("Could not process: Welcome letter is not Enabled", 'error', TRUE, 'feedback');
			redirect('config');
		}
		
		if(!$format) throw new Exception("Invalid Request", 1);
		
		$data = \Options::get('letter_'.$format);
		// if(!$data) throw new Exception("Could not find any Letter", 1);

		$title = $format =='general' ? 'General' : 'USD';
		
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
		$this->breadcrumb->append_crumb('Welcome Letter', site_url('config#letter-config'));
		$this->breadcrumb->append_crumb($title, site_url('config/letter'));
		$this->templatedata['data'] = $data;
		$this->templatedata['title'] = $title;
		$this->templatedata['format'] = $format;
		$this->templatedata['pageTitle'] = 'Welcome Letter';
		$this->templatedata['maincontent'] = 'config/letter';
		$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set("Could not process: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('config#letter-config');
		}

	}

}