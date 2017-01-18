<?php

class ModuleManager
{
	private static $permission_array;
	
	public static function readModules()
	{
		$perms = array();
		$mainmenu = array();
		$permsarr = array();
			
		foreach (Modules::$locations as $location => $offset)
		{		
			$dh = opendir($location);
			while($file = readdir($dh))
			{
				$path = $location.$file;
				if($file != "." AND $file != ".." AND is_dir($path))
				{
					$module = $file;
					if(file_exists($path."/setup.php"))
					{
						$_method = $module."_permissions";
						if(function_exists($_method)) {
							$permissions = $_method();
							$permsarr[$module] = $permissions;
						}
						/*include($path."/setup.php");*/
						if(isset($permissions) && is_array($permissions))
						{
							$perms = array_merge($perms,$permissions);
						}
						unset($permissions);
					}
				}
			}
		}
		
		if(!isset(self::$permission_array)) {			
			self::$permission_array = $permsarr;
			unset($permsarr);
		}
		
		//show_pre($perms); exit;
		//now fix the permissions
		$CI =& get_instance();
		
		//build the in string
		$inString = '';
		foreach($perms as $k => $v)
			$inString .= "'$k',";
		$inString = substr($inString,0,strlen($inString)-1);
		
		$query = $CI->doctrine->em->createQuery("SELECT p.name FROM models\Permissions p WHERE p.name IN ($inString)");
		$permissions = $query->getResult();
		
		$dbperms = array();
		$modPerm = array();
		foreach($permissions as $p){
			foreach($p as $k => $v){
				array_push($dbperms,$v);
			}
		}
		
		foreach($perms as $k => $v)
			array_push($modPerm,$k);
			
		$newPermissions = array_diff($modPerm,$dbperms);
			
		foreach($newPermissions as $k)
		{
			$permission = new models\Permissions();
			$permission->setName($k);
			$permission->setDescription($perms[$k]);
			$CI->doctrine->em->persist($permission);
		}
		
		$CI->doctrine->em->flush();
		unset($permissions);
		unset($perms);
		unset($newPermissions);
		unset($dbperms);
		unset($modPerm);
	}
	
	private function _sort_permissions($permissions)
	{
	}
	
	public static function permissionArray(){
		
		return self::$permission_array;
	}
}