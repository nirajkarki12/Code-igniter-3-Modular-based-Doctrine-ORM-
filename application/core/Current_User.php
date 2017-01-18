<?php

use models\Group;

use models\User,
	Doctrine\Common\Util\Debug;
	
class Current_User {
	
	/**
	 * @var models\User
	 */
	private static $user;
	
	private static $permissions = array();
	
	private function __construct() {}

	public static function user() {
		
		if(!isset(self::$user)) {
			$CI =& get_instance();

			if (!$user_id = $CI->session->user_id) {
				return FALSE;
			}
			
			$user = \CI::$APP->doctrine->em->find('models\User',$user_id);
			if(!$user)
				return FALSE;
			
			self::$user =& $user;
		}
		return self::$user;
	}
	
	public static function login($username, $password) {		
		$CI =& get_instance();
		

		$user = $CI->doctrine->em->getRepository('\models\User')->getActiveUser($username);

		if(!($user instanceof \models\User)) {
			$CI->message->set("This Username '{$username}'' does not exists", 'error', TRUE, 'feedback');
			redirect('auth/login');
		}

		self::checkAccessStatus($user);			
		
		if(password_verify($password, $user->getPassword()))
		{	
			$CI->load->library('session');
			$CI->session->user_id = $user->getId();
			self::$user = $user;
			
			//call the post_user_login hook
			\Events::trigger('post_user_login',self::$user);

			return TRUE;	
		}

		
		return FALSE;
	}
	
	public static function switchto($user_id) {
		$CI =& get_instance();
		$CI->load->library('session');
		
		if (is_numeric($main_user = $CI->session->main_user) and $main_user > 0) return FALSE;
		
		$CI->session->main_user = self::$user->getId();
		$CI->session->user_id = $user_id;
		if ($CI->session->user_id == $user_id) return TRUE;
		else return FALSE;
	}
	
	public static function can($seek_permission) {
		
		$CI =& get_instance();

		if (!$user_id = $CI->session->user_id) {
			return FALSE;
		}
		
		$given_permissions = array();		
		$groups = self::user()->getGroups();
		
		foreach ($groups as $group) {
			foreach($group->getPermissions() as $rp){
				$p = strtolower(trim($rp->getName()));
				$given_permissions[$p] = TRUE;
			}
		}

		if (is_array($seek_permission)) {			
			foreach ($seek_permission as $p) {				
				if (isset($given_permissions[strtolower(trim($p))])) return TRUE;
			}
		}else{
			if (isset($given_permissions[strtolower(trim($seek_permission))])) return TRUE;
		}
		
		return FALSE;
	}

	public static function isAlreadyLogged($user_id=NULL){
		
		if (!isset($user_id)) $user_id = self::user()->getId();
		
		$CI =& get_instance();
		$CI->load->library('session');
	
		$CI->load->database();
	
		$sesstimeout = time() - $CI->config->item('sess_time_to_update');
		$sessID = session_id();
		$delim_str = ';user_id|i:'.$user_id.';';
		$ip = $CI->input->ip_address();
		
		$query = $CI->db->query(
			"SELECT id, ip_address FROM tbl_sessions 
			WHERE timestamp > $sesstimeout 
			AND data LIKE '%".$delim_str."%' 
			AND id != '$sessID' " 
		);
		
		if ($query->num_rows() > 0) {			
			$result = $query->row_array();

			if ($result['ip_address'] == $ip ) {
				$CI->db->where('id', $result['id']);
				$CI->db->update($CI->config->item('sess_save_path'), array('data' => ''));
				return FALSE;
				
			}			
			return TRUE;
		}	
		return FALSE;	
	}
	
	public function setUser($user)
	{
		self::$user = $user;
	}
	
	public static function isSuperUser(){	
		if(self::user()):
			if(self::user()->isSuperAdmin())
				return true;
			else{
				foreach(self::user()->getGroups() as $group)
				{
					if($group->getId() == Group::SUPER_ADMIN ){
						return true;
					}
				}
			}
		endif;

		return false;
	}

	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function checkAccessStatus(\models\User $user)
	{
		$CI =& get_instance();
		try {
			
			// check group status
			$groups = $user->getGroups();
			if(count($groups)>0)
			{
				foreach ($groups as $group) {
					if($group->isActive())
					{
						continue;
					}else{
						throw new Exception("Your account is blocked for now. Contact administrator.", 1);
					}
				}
			}else{
				throw new Exception("Your account has no permissions set. Contact administrator.", 1);
			}

			// check user status
			if($user->isDeleted() || $user->isActive()==FALSE)
				throw new Exception("Your account is either disabled or deleted by administrator.", 1);

			return true;
			
		} catch (Exception $e) {
			$CI->message->set($e->getMessage() ,'error',TRUE,'feedback');
			if($CI->session->user_id)
			{
				$CI->session->sess_destroy();
			}
			redirect('auth/login');	
		}
	}
}
