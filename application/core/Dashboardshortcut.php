<?php

class DashboardShortcut{
	
	static $shortcuts = array();
	
	static function register($shortcut)
	{
		self::$shortcuts[] = $shortcut;
	}
	/**/
	

}
