<?php
use models\User;

class Auth_Controller extends MY_Controller{
	
	public $data; 

	public function __construct(){
		parent::__construct();
		$data = array();
		$_feedbacks = $this->message->get(FALSE,'feedback');
		if(count($_feedbacks) > 0){
			$data['feedback'] = $_feedbacks;
		}

		$this->data = $data;

	}

	public function login()
	{
		// echo password_hash(('123456'), PASSWORD_BCRYPT);
		if ($this->session->user_id) redirect('');
		else {
			$data = $this->data;
			$data['maincontent'] = 'auth/login';
			$this->load->theme('login_layout', $data);	
		}
	}
	
	public function authenticate(){
		if($this->_validate_login() === FALSE)
		{
			$this->login();
			return;
		}

		$user = Current_User::user();
		$user->setLastLogged();
		$this->doctrine->em->persist($user);
		$this->doctrine->em->flush();
		redirect(str_replace( array($this->config->item('url_suffix'), site_url(), 'auth/authenticate', 'auth/login'), '', current_url()));

	}
	
	private function _validate_login()
	{
		$this->form_validation->set_rules('sm-password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('sm-username', 'Username or Email', 'trim|required|callback_chklogin');
		$this->form_validation->set_message('chklogin','Invalid login. Please try again.');
		
		return $this->form_validation->run($this);
	}
	
	public function chklogin($username)
	{
		return Current_User::login($username, $this->input->post('sm-password'));
	}
	
	public function change_password()
	{
		try
		{
		if (!$this->session->user_id) redirect();
		if(!Current_User::user()->isFirstLogin()) redirect();
		if($this->input->post())
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('sm_password', 'Password', 'required|min_length[6]');
			$this->form_validation->set_rules('sm_confPassword', 'Confirm Password', 'required|min_length[6]|matches[sm_password]|callback_isOldPassword');
			if($this->form_validation->run($this))
			{
				$id = \Current_User::user()->getId();

				$user= $this->doctrine->em->getRepository('\models\User')->find($id);
					$user->setPassword(password_hash($this->input->post('sm_password'), PASSWORD_BCRYPT));
					$user->setFirstLogin(0);
					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();
					$this->session->set_userdata(array("msg"=>"Password changed Successfully, Please login to continue."));
					$this->session->unset_userdata(array('user_id'));
					redirect();
					
			}				
		}
		$data['maincontent'] = 'auth/change_password';
		$data['user'] = Current_User::user();
		$this->load->theme('login_layout', $data);
		} catch (Exception $e) {
			$this->message->set("Unable to Change password: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('auth/change-password');
		}
	}

	public function isOldPassword($password){
		if (password_verify($password, Current_User::user()->getPassword())) {
			$this->form_validation->set_message('isOldPassword', 'The New Password must be different than Old Password.<br/>');
			return false;
		}
	}
	
	public function logout($msg = null)
	{
		$this->session->sess_destroy();
		redirect('');
	}

	public function forgotPassword()
	{
		$data = $this->data;

		if($this->input->post())
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('sm_username', 'Username or Email', 'required|trim');

			if($this->form_validation->run($this))
			{
				$username = $this->input->post('sm_username');
				$user = null;
				if($u = $this->doctrine->em->getRepository('\models\User')->findOneBy(array('username'=> $username,'status'=>\models\User::STATUS_ACTIVE)))
				{
					$user = $u;
				}elseif($u = $this->doctrine->em->getRepository('\models\User')->findOneBy(array('email'=> $username,'status'=>\models\User::STATUS_ACTIVE)))
				{
					$user = $u;
				}

				if($user)
				{
					$user->setPassword(null);
					$current = new \DateTime();
					$token = md5(sha1($current->format('Y-m-d H:i:s').$username));
					$user->setToken($token);
					$user->setFirstLogin(0);
					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					$this->load->library('mailer');
					$mailer = new Mailer\Mailer;

					$from['from_name'] = $this->config->item('project_name');
					$from['from_email'] = \Options::get('email');
					$to['to_name'] = $user->getUsername();
					$to['to_email'] = $user->getEmail();

					$subject = "Recover Password!";
					$message = "<h2>Hello, {$user->getUsername()}</h2> <p>You have just requested to recover your password.</p><p>Please reset your password by clicking the link below.</p><p><a href='".site_url("auth/resetPassword/{$user->getUsername()}?token={$token}__{$user->getId()}")."'>Reset Password</a></p><p style='color:#cccccc;'><em>(Note: You will not be able to login until you reset your password.)</em></p>";

					$mailer->sendMail($from, $to, $subject, $message);

					$this->message->set("Reset password link has been sent to your registered email address.", 'info', TRUE, 'feedback');
					redirect('auth/login');
				}else{
					$this->message->set("No user is registerd with username or email address ({$username}).", 'error', TRUE, 'feedback');
					redirect('auth/forgotPassword');
				}
			}
		}
		$data['maincontent'] = 'auth/forgetPassword';
		$this->load->theme('login_layout', $data);
	}

	public function confirm()
	{
		$tokenId = $this->input->get('token');
		$username = $this->input->get('username');
		$arr = explode("__", trim($tokenId));
		$id = $arr[1];
		$token = $arr[0];

		$user= $this->doctrine->em->getRepository('\models\User')->find($id);

		if($user)
		{
			if($user->getUsername() == $username && $user->getToken() == $token)
			{
				// $user->setToken(null);
				$user->activate();
				$this->doctrine->em->persist($user);

				$this->doctrine->em->flush();

				$this->message->set("Your account has been activated. Please reset your password to continue.", 'success',TRUE,'feedback');
				redirect(site_url("auth/resetPassword/{$username}?token={$tokenId}"));
			}else{
				$this->message->set("Invalid token.", 'error',TRUE,'feedback');
			}
			redirect(site_url('auth/login'));
		}
		show_404($page = '', $log_error = TRUE);
	}

	public function resetPassword($username=null)
	{
		try {
			if(!$username) throw new Exception("User is missing.", 1);
			
			$data = $this->data;

			if($this->input->post())
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('sm_password', 'Password', 'required|min_length[6]');
				$this->form_validation->set_rules('sm_confPassword', 'Confirm Password', 'required|min_length[6]|matches[sm_password]');
				if($this->form_validation->run($this))
				{
					if($username && $tokenId = $this->input->get('token'))
					{
						$arr = explode("__", trim($tokenId));
						$id = $arr[1];
						$token = $arr[0];

						$user= $this->doctrine->em->getRepository('\models\User')->find($id);
						if($user && $user->getUsername() == $username && $user->getToken() == $token)
						{
							$user->setPassword(password_hash($this->input->post('sm_password'), PASSWORD_BCRYPT));
							$user->setToken(null);
							$user->setLastLogged();
							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							$this->session->set_userdata(array("msg"=>"Password Reset Successfully, Please login to continue."));
							redirect();
							
						}else{
							throw new Exception("Invalid token.", 1);
						}
					}else{
						throw new Exception("Username or token is missing.", 1);
					}
				}				
			}

			$data['username'] = $username;
			$data['maincontent'] = 'auth/resetPassword';
			$this->load->theme('login_layout', $data);
			
		} catch (Exception $e) {
			$this->message->set("Unable to reset password: {$e->getMessage()}", 'error',TRUE,'feedback');
			redirect('auth/login');
		}		
	}
	
	
	public function switchuser($username){
		
		if (!user_access('allow user switching')) redirect('user');
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$currentUser = Current_User::user();		
			$userRepo = $this->doctrine->em->getRepository('models\User');
			
			if (!($user = $userRepo->getActiveUser($username))) throw new Exception("User not found.", 1);
			
			// Restrict if company is not active or main company
			$this->load->helper('user/user');
			$adminGroup = getUserGroup($user->getId());
			if(!$adminGroup) throw new Exception("User GROUP you are trying to switch to is currently disabled.", 1);
			$flag = false;
			foreach ($adminGroup as $aGroup) {
				if(!$aGroup->isActive()) throw new Exception("User GROUP(s) you are trying to switch to is currently disabled.", 1);
				if($aGroup->getId() == \models\Group::SUPER_ADMIN)
				throw new Exception("Cannot Switch to Admin Group.", 1);
			}

			// Restrict if user is not active or super user or 
			if(!$user->isActive()) throw new Exception("User {$user->getUsername()} is currently disabled.", 1);
			if($user->getId() == \models\User::SUPER_ADMIN || $user->getId() == $currentUser->getId()) throw new Exception("Illegal operation.", 1);

			// if all works fine proceed to switch user
			if (Current_User::switchto($user->getId())) {
				$this->message->set("Successfully switched to ".$user->getUsername().".", 'success', TRUE, 'feedback');
				redirect();			
			} else {
				throw new Exception("Please try again.", 1);
			}
			
		} catch (Exception $e) {
			$this->message->set("Unable to switch user: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}

	public function revert() {
				
		if (is_numeric($main_user = $this->session->main_user)) {
	
			$main_user = $this->doctrine->em->find('models\User', $main_user);
			
			if (!$main_user or $main_user->isDeleted() or !$main_user->isActive()) redirect('');
			
			$this->session->user_id = $main_user->getId();
			
			if ($this->session->user_id == $main_user->getId()) {
				$this->session->unset_userdata('main_user');
				$this->message->set("Successfully reverted back to ".$main_user->getUsername().".", 'success',TRUE,'feedback');
				redirect('user');
			} else {
				$this->message->set("Cannot revert back user !!", 'error',TRUE,'feedback');
				redirect();
			}
		} else redirect();
	}
}