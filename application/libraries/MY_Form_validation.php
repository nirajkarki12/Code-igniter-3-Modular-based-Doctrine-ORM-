<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{
	
	function run($module = '', $group = ''){
		(is_object($module)) AND $this->CI = &$module;
		return parent::run($group);
	}
	
	public function money($str){
		return (bool) preg_match('/^[0-9]*\.?[0-9]{1,2}+$/',$str);
	}
	
	public function phone($str){
		return ( ! preg_match("/^([a-z0-9_-\s@+(),])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function alpha_numeric($str)
	{
		return ( ! preg_match("/^([a-z0-9_\-\s@\'\.])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function valid_username($str)
	{
		return ( ! preg_match("/^([a-z0-9_\-\.])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function decimal($value)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('decimal',
			'The {field} is not a valid decimal number.');
		
		$regx = '/^[-+]?[0-9]*\.?[0-9]*$/';
		if(preg_match($regx, $value))
			return true;
		return false;
	}

	function unique_username($username){
		$this->CI->form_validation->set_message('unique_username',"The {field} ($username) is already registered.");
		$em = $this->CI->doctrine->em;
		$user = $em->getRepository('models\User')->getActiveUser($username);
		return ($user) ? false : true;

	}

	function unique_groupName($groupName){
		$this->CI->form_validation->set_message('unique_groupName',"The {field} ($groupName) is already registered.");

		$em = $this->CI->doctrine->em;
		$group = $em->getRepository('models\Group')->getActiveGroup($groupName);
		return ($group) ? false : true;

	}

	function unique_groupName_edit($name, $id){
		$this->CI->form_validation->set_message('unique_groupName_edit',"The {field} ($name) is already registered.");

		$em = $this->CI->doctrine->em;
		$group = $em->getRepository('models\Group')->getActiveGroup($name);
		$flag = false;
		
		if($group)
		{
			if($group->getId()==$id)
			{
				$flag = true;
			}
			
		}else{
			$flag = true;
		}
		return $flag;
		// return ($group ? (($group->isDeleted() ? true : ($group->getId() == $id ? true))) : true); 

	}

	function unique_email($email){
		$this->CI->form_validation->set_message('unique_email',"The {field} ($email)  is already registered.");

		$em = $this->CI->doctrine->em;
		$user = $em->getRepository('models\User')->getUserByEmail($email);
		return ($user) ?  false : true;
	}

	function unique_email_edit($email, $userId){
		$this->CI->form_validation->set_message('unique_email_edit',"The {field} ($email)  is already registered.");

		$em = $this->CI->doctrine->em;
		$user = $em->getRepository('models\User')->getUserByEmail($email, $userId);
		return ($user) ?  false : true;
	}

	function alpha_space($str){
		$CI =& get_instance();
		$CI->form_validation->set_message('alpha_space','The {field} may only contain alphabet and spaces.');
		return (!preg_match("/^[a-zA-Z\s]+$/i",$str))?FALSE:TRUE;
	}	

	public function valid_date_range($date)
	{
		$this->CI->form_validation->set_message('valid_date_range',"Invalid date range format.");

		if(!preg_match("/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})(\s\-\s)([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/", $date))
		{
			return false;
		}
		return true;
	}

}
