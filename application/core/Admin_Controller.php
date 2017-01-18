<?php

class Admin_Controller extends MY_Controller
{
	protected $templatedata = array();

	protected $mainmenu  = null;
	
	public function __construct(){

		parent :: __construct();

		//system theme defination
		System::init();
		//$this->output->enable_profiler(TRUE);

		$this->load->library('breadcrumb');	
		
		$this->templatedata['printstyler'] = csscrush_file( BASEPATH.'../assets/themes/' . config_item('current_theme') . '/css/print.css' );
		$this->templatedata['themestyler'] = csscrush_file( BASEPATH.'../assets/themes/' . config_item('current_theme') . '/css/style.css' );
		
		if (!$this->session->user_id)
		{
			// preserve flashdata session for redirect with permission issue
			$this->session->keep_flashdata('_messages');

			redirect('auth/login/'.str_replace( 
				array(
					$this->config->item('url_suffix'), 
					site_url(), 
					'auth/authenticate', 
					'auth/login'
				), 
				'', 
				current_url()
				)
			);
		}
		// checking for first login and not when user is switching
		if(Current_User::user()->isFirstLogin() && !$this->session->main_user)
		{
			redirect('auth/change-password');
		}
		
		$this->load->library('breadcrumb');
		$this->breadcrumb->append_crumb('Dashboard', site_url());
		$this->templatedata['mainmenu'] = $this->mainmenu;
		$this->templatedata['_CONFIG']	= $this->_CONFIG;
		$this->templatedata['flashdata'] = $this->session->flashdata('feedback');		
		$this->templatedata['scripts'] = array();
		$this->templatedata['stylesheets'] = array();

		//check for any critical messages
		$_critical_messages = $this->message->get('alert','critical');
		if(count($_critical_messages) > 0 ){
			$this->templatedata['critical_alerts'] = $_critical_messages;
		}
		
		$_feedbacks = $this->message->get(FALSE,'feedback');
		if(count($_feedbacks) > 0){
			$this->templatedata['feedback'] = $_feedbacks;
		}

		$currentUser = Current_User::user();
		if($currentUser){
			// check if user status has been altered by admin, restrict further operations
			\Current_User::checkAccessStatus($currentUser);
			if (Current_User::isAlreadyLogged() && !config_item('allow_multiple_machine_session')) {			
				$this->session->sess_destroy();
				
				$this->templatedata['maincontent'] = 'config/user-already-logged-in';
				// $this->load->theme('master',$this->templatedata);		
			}
		
			$admin = Current_User::isSuperUser();
		
			if (is_numeric($main_user = $this->session->main_user)) {
				$main_user = $this->doctrine->em->find('models\User', $main_user);
				if ($main_user) {
					$this->templatedata['user_switch'] = 	array(
						'text' => 'You are currently using <strong>'.$this->config->item('project_name').'</strong> as <br><em>('. Current_User::user()->getName().' -> ' .Current_User::user()->getUsername(). ')</em>. <br>Revert back to <a href="' .site_url('auth/revert'). '" style="color:#3B7596;">Main User</a> when you are done.',
						'type' => 'warning',
						'layout' => 'topCenter',
						'theme' => 'defaultTheme',
					);				
				}			
			}
		
			if ($admin or $main_user) {} else {			
				if (\Options::get('user1st_login','0')=='1') {				
					if (($currentUser->isFirstLogin())) {
						$this->message->set("Logging in for first time! Please change your password.", 'error', TRUE, 'feedback');
						redirect(site_url('auth/changepwd'));					
					}				
				}
				
				if (\Options::get('userpwd_expirable','0') == '1'
					and	(\Options::get('userpwd_expiry_days','1000') >= '10')
					) 
				{				
					if ((isValidDate(($currentUser->pwdLastChangedOn()->format('Y-m-d')))) // bypass invalid timestamp
						and
						(time() - strtotime($currentUser->pwdLastChangedOn()->format('Y-m-d'))) // number of seconds from last pwd change to now
						>=
						((\Options::get('userpwd_expiry_days','1000')) * 86400) // number of seconds after which pwd expires
						) 
					{				
						$this->message->set("Your password has expired!! Please change your password.", 'error', TRUE, 'feedback');
						redirect(site_url('auth/changepwd'));
					}
				}
			}
	
			if (\Options::get('site_maintenance', '0')=='1') {
				$force_maintenance = TRUE;
				$autoresume = (\Options::get('site_maintenance_resume','0')=='1') ? TRUE : FALSE;
				
				if ($autoresume) {				
					$resume_date_time = \Options::get('site_maintenance_resume_after', '0000-00-00 00:00');
					$resume_date = substr($resume_date_time, 0, 10);
					
					if (isValidDate($resume_date)) {
						$resume_timestamp = strtotime($resume_date_time.':00');
						if ($resume_timestamp > 0 and $resume_timestamp < time()) {
							$force_maintenance = FALSE;
						}
					}
				}
				
				if ($force_maintenance) {
					if ($admin) {
						$this->templatedata['site_maintenance'] = 	array(
							'text' => 'Site is Currently in Maintenance Mode. <br>Please <a href="' .site_url('config#maintenance-config'). 
							// '/#maintenance-config" 
							'" style="color:#f99;">deactivate</a> this mode when you are done.',
							'type' => 'warning',
							'layout' => 'topCenter',
							'theme' => 'defaultTheme',
						);
					}else{
						$this->templatedata['pageTitle'] = 'Site Maintenance Mode';
						$this->templatedata['maincontent'] = 'config/site-maintenance';
						$this->load->theme('master',$this->templatedata);
						// $this->load->theme('config/site-maintenance');
					}
				}
			}
		}
	}

	public function paginate_data(\Doctrine\ORM\Query $query, $offset = null, $perpage = null){

 		if(DB_ACTIVE == 'mssql') 
			$query->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'DoctrineSqlServerExtensions\ORM\Query\AST\SQLServerSqlWalker');
		
		if(!is_null($offset))
			$query->setFirstResult($offset);

		if(!is_null($perpage))
			$query->setMaxResults($perpage);

		$data = new \Doctrine\ORM\Tools\Pagination\Paginator($query, $fetchJoin = true);
		return $data;
	}

	public function paginate_view($data, $base_url, $perpage = 20, $uri_segment = 3 ){
 			$total = count($data);

 		// if($total > $perpage)
 		// {
 			$this->load->library('pagination');
			
 			$config['base_url'] = $base_url;
 			$config['total_rows'] = $total;
 			$config['per_page'] = $perpage;
			$config['uri_segment'] = $uri_segment;
			$config['prev_link'] = 'Previous';
 			$config['next_link'] = 'Next';
 			$config['page_query_string'] = TRUE;
			$config["num_links"] = 5;
			
 			$this->pagination->initialize($config);
 			$this->templatedata['pagination'] = $this->pagination->create_links();
 		// }
	}
}
