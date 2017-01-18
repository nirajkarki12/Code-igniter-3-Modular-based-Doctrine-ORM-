<?php

class System
{	
	public static function init()
	{
		//initialize the options
		Options::__init();
		//initialize the admin main menu
		
		if(!\Options::get('site_title',FALSE)){
			$message = "Site Title is not set. Click ".anchor('config#general-config','here').' to set it.';
			CI::$APP->message->set($message,'alert',FALSE,'critical');
		}
		if(!\Options::get('offset',FALSE)){
			$message = "Data per page is not set. Click ".anchor('config#general-config','here').' to set it.';
			CI::$APP->message->set($message,'alert',FALSE,'critical');
		}
		
		//read modules information
		CI::$APP->benchmark->mark('Module_read_start');
		ModuleManager::readModules();
		
		CI::$APP->benchmark->mark('Module_read_end');
		
		//read the theme and apply the theme specific settings
		CI::$APP->benchmark->mark('Themeprepare_start');
		
		CI::$APP->benchmark->mark('Themeprepare_end');

		//register shutdown handler
		//register_shutdown_function(array('self','handleShutdown'));
	}
	
	public static function themeOK($assertTrueForDefaultTheme = FALSE) {
		
		$current_theme = config_item('current_theme');
		$theme_path    = theme_path();
		
		$requiredDirs  = array(
			'',
			'css',  
			//	'js', 'images', 'icons', /* for now */ 
		);
		
		$requiredFiles = array(
			'' 		=> array('master' . EXT, 'template' . EXT, ),
			'css'	=> array('print.css', ),
		);
		
		$missingDirs  = array();
		$missingFiles = array();
		
		foreach ($requiredDirs as $dir) {
			if ( ! is_dir($theme_path . $dir)) {
			//	$missingDirs[] = $theme_path . $dir; // for security reasons we hide this FULL PATH
				$missingDirs[] = $current_theme . '/' . ( empty($dir) ? '' : $dir . '/' );
			}
		}
		
		foreach ($requiredFiles as $dir => $files) {
			$path = $theme_path . ( empty($dir) ? '' : $dir . '/' );
			
			foreach ((array) $files as $file) {
				if ( ! is_file( $path . $file )) { 
				//	$missingFiles[] = $path . $file; // for security reasons we hide this FULL PATH
					$missingFiles[] = $current_theme . '/' . ( empty($dir) ? '' : $dir . '/' ) . $file; 
				}
			}
		}
		
		if ( count($missingDirs) == 0 && count($missingFiles) == 0)
			return TRUE;
		
		$error = '';
		 
		if (! empty($missingDirs) ) {
			$error .= "The following folder(s) missing:<br/>";
			$error .= "<pre>  " . implode("\n  ", $missingDirs) . "</pre><br/>";
		}
		
		if (! empty($missingFiles) ) {
			$error .= "The following file(s) missing:<br/>";
			$error .= "<pre>  " . implode("\n  ", $missingFiles) . "</pre>";
		}
		
		show_error($error, 500, 'Incomplete Theme Definition');
	}
	
	public function handleShutdown(){
		$error = error_get_last();
		if($error !== NULL){
			$info = "[SHUTDOWN] file:".$error['file']." | ln:".$error['line']." | msg:".$error['message'];
			log_message('error',$info);
		}
		
	}
}
?>