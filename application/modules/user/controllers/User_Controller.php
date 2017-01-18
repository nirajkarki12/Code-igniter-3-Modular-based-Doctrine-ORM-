<?php 
use models\Group;
use models\User;

class User_Controller extends Admin_Controller {
	private $adminUser = null;

	private $adminUserId = null;

	public function __construct()
	{
		parent::__construct();
		
 		$this->load->helper('security');

		if (strpos(current_url(), 'changepwd') !== FALSE or strpos(current_url(), 'profile') !== FALSE){}		
		else $this->breadcrumb->append_crumb('Users', site_url('user'));
   }
	   
	public function index($offset = NULL)
	{		
		if (!user_access('view users')) redirect();
		$this->breadcrumb->append_crumb('View Users', site_url('user'));
		
		$perpage = \Options::get('offset');
		$user = Current_User::user();
		$userGroups = $user->getGroups();
		
		$filters = array();
		
		$param = '';
		$geturi = '';
		$post = NULL;
		$offset = $this->input->get('per_page');
 		
 		if($this->input->get('a_filter')){
 			$post = $this->input->get();
 			$filters = $post;
 			
 			foreach($post as $k=>$v){
 				if($k !== 'per_page') $param .=  $k.'='.$v.'&'; 
 			}
 			$param = substr($param,0,-1);
 			$geturi = '?' . http_build_query($post, '', '&');
 		}

 		$users = $this->paginate_data($this->doctrine->em->getRepository('models\User')->getUserList($filters), $offset, $perpage );
		$this->paginate_view($users, base_url().'user/index?'.$param, $perpage);
 
 		$gRepo = $this->doctrine->em->getRepository('models\Group');
 		$groups = $gRepo->findAll();

 		if($this->input->get('do')=='xls')
 		{
 			$this->load->helper('excel');
 			$file_name = 'User(s)';
 			exportxls($file_name);
			$this->load->theme('user/report/user', array('users'=> $users,'offset'=>$offset));
			return;
 		}
 		 		
		$this->templatedata['groups'] = &$groups;
 		$this->templatedata['users'] = &$users;
		$this->templatedata['offset'] = $offset;
		$this->templatedata['adminUserId'] = $this->adminUserId;
		$this->templatedata['post'] = $post;
		$this->templatedata['filters']=$filters;
		$this->templatedata['per_page'] = $perpage;
		$this->templatedata['maincontent'] = 'user/list';
		$this->templatedata['pageTitle'] = 'Users';
		$this->load->theme('master',$this->templatedata);
	}

	public function add()
	{	
		if (!user_access('add user')) redirect();
		
		try {

			$gRepo = $this->doctrine->em->getRepository('models\Group');
 			$groups = $gRepo->findBy(array('status'=>\models\Group::STATUS_ACTIVE));
 			$grpsID = array();
 			foreach ($groups as $grp) {
 				$grpsID[] = $grp->getId();
 			}
			
			if($this->input->post())
			{
				$this->form_validation->set_rules('fname', 'First name', 'trim|required');
				$this->form_validation->set_rules('mname', 'Middle name', 'trim');
				$this->form_validation->set_rules('lname', 'Last name', 'trim|required');
				$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|required|unique_email');
				$this->form_validation->set_rules('username', 'Username', 'trim|valid_username|required|unique_username');
				$this->form_validation->set_rules('password','Password','trim|required|min_length[6]');
				$this->form_validation->set_rules('confPassword','Confirm Password','trim|required|min_length[6]|matches[password]');

				// check for valid groups
				$this->validGroups($this->input->post('groups'), $grpsID);
				
				if($this->form_validation->run($this))
				{
					$POST = $this->input->post();
					
					$user = new User;
					$user->setFirstName($POST['fname']);
					$user->setMiddleName($POST['mname']);
					$user->setLastName($POST['lname']);
					$user->setEmail($POST['email']);
					$user->setUsername($POST['username']);
					$user->setPassword(password_hash($POST['password'], PASSWORD_BCRYPT));

					$grps = $user->initGroups();
					foreach ($this->input->post('groups') as $group) {
						$grp = $gRepo->find($group);
						$grps[] = $grp;
					}
					$user->setGroups($grps);
					
					$token = md5(sha1($POST['username']));
					$user->setToken($token);

					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					$this->message->set("User '{$this->input->post('username')}' has been added successfully.", 'success', TRUE, 'feedback');
					redirect('user');
				}
			}

			$this->breadcrumb->append_crumb('Add User', current_url());
			$this->templatedata['groups'] = $groups;
			$this->templatedata['maincontent'] = 'user/add';
			$this->templatedata['pageTitle'] = 'Add User';
			$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set("Could not add user: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}		
	}
		
	public function edit($username)
	{	
		if (!user_access('edit user')) redirect();
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$uRepo = $this->doctrine->em->getRepository('models\User');
			$user = $uRepo->getActiveUser($username, \models\User::STATUS_ACTIVE);
			if(!$user) throw new Exception("User not found.", 1);

			// restrict user trying to edit main admin of company or self
			if($user->getId() == $this->adminUserId || $user->getId() == \Current_User::user()->getId()) throw new Exception("Illegal operation.", 1);
			
			// process only if user is active
			if($user->isDeleted() || $user->isBlocked()) throw new Exception("User '{$user->getUsername()}' is currently disabled.", 1);			

			//get groups
			$gRepo = $this->doctrine->em->getRepository('models\Group');
 			$groups = $gRepo->findBy(array('status'=>\models\Group::STATUS_ACTIVE));
 			$grpsID = array();
 			foreach ($groups as $grp) {
 				$grpsID[] = $grp->getId();
 			}

 			//getuser groupsId
 			$userGrpsID = array();
 			foreach ($user->getGroups() as $group) {
 				$userGrpsID[] = $group->getId();
 			}

			if($this->input->post())
			{

				$this->form_validation->set_rules('fname', 'First name', 'trim|required');
				$this->form_validation->set_rules('mname', 'Middle name', 'trim');
				$this->form_validation->set_rules('lname', 'Last name', 'trim|required');
				$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|required|unique_email_edit['.$user->getId().']');
				$this->form_validation->set_rules('status', 'Status', 'numeric|trim|required');

				// check for valid groups
				$this->validGroups($this->input->post('groups'), $grpsID);
				
				if($this->form_validation->run($this))
				{
					$POST = $this->input->post();
					
					$user->setFirstName($POST['fname']);
					$user->setMiddleName($POST['mname']);
					$user->setLastName($POST['lname']);
					$user->setEmail($POST['email']);
					$user->setStatus($this->input->post('status'));
					
					$grps = $user->initGroups();
					foreach ($this->input->post('groups') as $group) {
						$grp = $gRepo->find($group);
						$grps[] = $grp;
					}
					$user->setGroups($grps);

					// $this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					$this->message->set("User '{$username}' has been updated successfully.", 'success', TRUE, 'feedback');
					redirect('user');
				}
				$this->templatedata['post'] =$this->input->post();
			}

			$this->breadcrumb->append_crumb('Edit User', current_url());
			$this->templatedata['groups'] = $groups;
			$this->templatedata['user'] = $user;
			$this->templatedata['userGroups'] = $userGrpsID;
			$this->templatedata['maincontent'] = 'user/edit';
			$this->templatedata['pageTitle'] = 'Edit User';
			$this->load->theme('master',$this->templatedata);
			
		} catch (Exception $e) {
			$this->message->set("Unable to edit user: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}

	/* Checks if user has selected groups that does not belong to the specific company */
	public function validGroups($childGrp, $parentGrp)
	{
		// $parentGrp = json_decode($parentGrp);

		foreach ($childGrp as $grp) {
			if(!in_array($grp, $parentGrp)){
				$this->form_validation->set_message('validGroups', 'Illegal selection of groups.');
				return false;
			}
		}
		return true;
	}


	public function block($username)
	{
		if (!user_access('edit user')) redirect();
		
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$uRepo = $this->doctrine->em->getRepository('models\User');
			$user = $uRepo->getActiveUser($username, \models\User::STATUS_ACTIVE);
			if(!$user) throw new Exception("User not found.", 1);

			// restrict user trying to block main admin of company or self
			if($user->getId() == $this->adminUserId || $user->getId() == \Current_User::user()->getId()) throw new Exception("Illegal operation.", 1);

			// process only if user is active
			if(!$user->isActive()) throw new Exception("User '{$user->getUsername()}' is currently disabled.", 1);	

			$user->deactivate();
			$this->doctrine->em->flush();

			$this->message->set("User '{$user->getUsername()}' has been blocked.", 'success', TRUE, 'feedback');
			redirect('user');

		} catch (Exception $e) {
			$this->message->set("Unable to block user: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}
	
	public function unblock($username){
		if (!user_access('edit user')) redirect();
		
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$uRepo = $this->doctrine->em->getRepository('models\User');
			$user = $uRepo->findOneBy(array('username'=>$username,'status'=>\models\User::STATUS_BLOCK));
			if(!$user) throw new Exception("User not found.", 1);

			// restrict user trying to unblock main admin of company or self
			if($user->getId() == $this->adminUserId  || $user->getId() == \Current_User::user()->getId()) throw new Exception("Illegal operation.", 1);

			// process only if user is blocked
			if(!$user->isBlocked()) throw new Exception("User '{$user->getUsername()}' is not blocked yet.", 1);	

			$user->activate();
			$this->doctrine->em->flush();

			$this->message->set("User '{$user->getUsername()}' has been unblocked.", 'success', TRUE, 'feedback');
			redirect('user');

		} catch (Exception $e) {
			$this->message->set("Unable to unblock user: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}

	public function force_change_password($username){
		if (!user_access('force change password')) redirect();
		
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$uRepo = $this->doctrine->em->getRepository('models\User');
			$user = $uRepo->getActiveUser($username, \models\User::STATUS_ACTIVE);
			if(!$user) throw new Exception("User not found.", 1);

			// restrict user trying to unblock main admin of company or self
			if($user->getId() == $this->adminUserId  || $user->getId() == \Current_User::user()->getId()) throw new Exception("Illegal operation.", 1);

			// process only if user is not blocked
			if($user->isBlocked()) throw new Exception("User '{$user->getUsername()}' is currently disabled.", 1);

			if($user->isFirstLogin()) throw new Exception("Force Password Change is already enable for this user '{$user->getUsername()}'.", 1);

			$user->setFirstLogin(1);
			$this->doctrine->em->flush();

			$this->message->set("Force Password Change is enabled for this user '{$user->getUsername()}'.", 'success', TRUE, 'feedback');
			redirect('user');

		} catch (Exception $e) {
			$this->message->set("Unable to process : {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}

	
	
	public function delete($username)
	{
		if (!user_access('modify user')) redirect();
		
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$uRepo = $this->doctrine->em->getRepository('models\User');
			$user = $uRepo->getActiveUser($username, \models\User::STATUS_ACTIVE);
			if(!$user) throw new Exception("User not found.", 1);

			// restrict user trying to delete main admin of company or self
			if($user->getId() == $this->adminUserId || $user->getId() == \Current_User::user()->getId()) throw new Exception("Illegal operation.", 1);

			// process only if user is active
			if(!$user->isActive()) throw new Exception("User '{$user->getUsername()}' is currently disabled.", 1);	

			$user->delete();
			$this->doctrine->em->flush();

			$this->message->set("User '{$user->getUsername()}' has been deleted.", 'success', TRUE, 'feedback');
			redirect('user');

		} catch (Exception $e) {
			$this->message->set("Unable to modify user: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}

	public function resetpwd($username) {
		if (!user_access('reset password')) redirect();
		
		try {
			if(!$username) throw new Exception("Error processing request.", 1);
			
			$uRepo = $this->doctrine->em->getRepository('models\User');
			$user = $uRepo->getActiveUser($username, \models\User::STATUS_ACTIVE);
			if(!$user) throw new Exception("User not found.", 1);

			// restrict user trying to reset password of  main admin of company
			if($user->getId() == $this->adminUserId) throw new Exception("Illegal operation.", 1);

			if ($this->input->post()) {
				$this->form_validation->set_rules('newPwd','New Password','trim|required|min_length[6]');
				$this->form_validation->set_rules('confPwd','Confirm Password','trim|required|min_length[6]|matches[newPwd]|callback_checkNewPwd['.$user->getPassword().']');
				
				if ($this->form_validation->run($this)) {
					$user->setPassword(password_hash($this->input->post('newPwd'), PASSWORD_BCRYPT));
					$user->setFirstLogin(1);
					$this->doctrine->em->flush();					

					$this->message->set("User's '{$user->getUsername()}' password has been reset.", 'success', TRUE, 'feedback');
					redirect('user');					
				}			
			}
			
			$this->breadcrumb->append_crumb('Reset Password', current_url());
			$this->templatedata['user'] = $user;
			$this->templatedata['maincontent'] = 'user/resetpwd';
			$this->templatedata['pageTitle'] = 'Reset Password';
			$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set("Unable to reset password: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user');
		}
	}

	public function profile()
	{
		try {
			
		$user = \Current_User::user();
		
		if($this->input->post()){
			$this->form_validation->set_rules('fname', 'First name', 'trim|required');
			$this->form_validation->set_rules('mname', 'Middle name', 'trim');
			$this->form_validation->set_rules('lname', 'Last name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|required|unique_email_edit['.$user->getId().']');

			if($this->form_validation->run($this))
			{	
				$POST = $this->input->post();

				$user->setFirstName($POST['fname']);
				$user->setMiddleName($POST['mname']);
				$user->setLastName($POST['lname']);
				$user->setEmail($POST['email']);

				$this->doctrine->em->persist($user);
				$this->doctrine->em->flush();

				$this->message->set('Profile updated successfully', 'success', TRUE, 'feedback');
				redirect('user');
			}

		}
		$this->templatedata['user'] = $user;
		$this->templatedata['maincontent'] = 'user/profile';
		$this->templatedata['pageTitle'] = 'Profile';
		$this->breadcrumb->append_crumb('Profile', 'user/profile');
		$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set('Unable to update profile. Please try again.', 'error', TRUE, 'feedback');
			redirect('user/profile');
		}
	}
	
	public function changepwd()	{
		
		$user = Current_User::user();
		
		if ($this->input->post()) {
			
			$this->form_validation->set_rules('oldPwd','Old Password','trim|required|callback_checkOldPwd');
			$this->form_validation->set_rules('newPwd','New Password','trim|required|min_length[6]|callback_checkNewPwd['.\Current_User::user()->getPassword().']');
			$this->form_validation->set_rules('conPwd','Confirm Password','trim|required|min_length[6]|matches[newPwd]');
			
			if ($this->form_validation->run($this)) {
				$user->setPassword(password_hash($this->input->post('newPwd'), PASSWORD_BCRYPT));
				$this->doctrine->em->flush();
				
				$this->message->set("Password changed successfully.", 'success',TRUE,'feedback');
				redirect('user');
			}			
		}
		
		$this->breadcrumb->append_crumb('Change Password', current_url());
		$this->templatedata['user'] = $user;
		$this->templatedata['maincontent'] = 'user/changepwd';
		$this->templatedata['pageTitle'] = 'Change Password';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function checkNewPwd($newPwd, $oldPwd) 
	{
		if (password_verify($newPwd, $oldPwd)) {
			$this->form_validation->set_message('checkNewPwd', 'The New Password must be different than Old Password.<br/>');
			return false;
		}
		return true;
	}
	public function checkOldPwd($oldPwd) 
	{
		if (!password_verify($oldPwd, Current_User::user()->getPassword())) {
			$this->form_validation->set_message('checkOldPwd', 'The Old Password is Wrong.<br/>');
			return false;
		}
		
		return true;
	}
}