<?php

function getUserGroup($user_id)
{
	$CI =& get_instance();
	
	$group = $CI->doctrine->em->getRepository('models\Group')->getUserGroup($user_id);

	if($group === null) return null;
	else return $group;
}

function user_access($permission)
{
	if(!Current_User::user()) return FALSE;
	foreach (Current_User::user()->getGroups() as $group) {
		if($group->isAdmin())
			return TRUE;
	}	
	return Current_User::can($permission);
}

function config_access(){
	$listeners = Events::get_listeners('config_launcher_init');
	if($listeners){
		$launchers = array();
		foreach($listeners as $l){
			$launchers = $l($launchers);
		}
		
		if(count($launchers)){
			foreach($launchers as $ln){
				$permission = $ln['permission'];
				if(user_access($permission)) return TRUE;
			}			
		}
		
		return FALSE;
	}
}

function user_access_or($permissions = array()){
	foreach (Current_User::user()->getGroups() as $group) {
		if($group->isAdmin())
			return TRUE;
	}	
	foreach($permissions as $p){
		if(Current_User::can($p))
			return TRUE;
	}
	return FALSE;
}

function user_access_and($permissions = array()){
	foreach (Current_User::user()->getGroups() as $group) {
		if($group->isAdmin())
			return TRUE;
	}
	foreach($permissions as $p){
		if(!Current_User::can($p))
			return FALSE;
	}
	return TRUE;
}

function report_access($reportID) {
	
		if (!Current_User::user()) return FALSE; 

		$report = CI::$APP->doctrine->em->find('models\Common\Report',$reportID);
		if($report)	$permitted_groups = $report->getUserGroups();

		$current_user_group = Current_User::user()->getGroups();

		foreach ($current_user_group as $value) {
			if ($value->isAdmin()) 
			{
				return true;
			}elseif($report && in_array($value->getId(), $permitted_groups))
			{
				return true;
			}

		}
		
		return FALSE;
			
	}
	
function txnhalted(){

	if (\Options::get('isTxnHalt', '0')=='1') {
		
		$from 	= \Options::get('txn_halt_from', '0000-00-00 00:00');
		$to 	= \Options::get('txn_halt_to', '0000-00-00 00:00');
		
		$from_date = substr($from, 0, 10);
		$to_date = substr($to, 0, 10);
		
		if (isValidDate($from_date) and isValidDate($to_date)) 
			if ( (strtotime($from.':00') < time()) and (time() <  strtotime($to.':00')) ) return $to;
	
	}
	
	return FALSE;
	
}
?>