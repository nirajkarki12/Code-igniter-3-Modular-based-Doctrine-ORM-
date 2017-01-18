<?php

function theme_url()
{
	$current_theme = CI::$APP->config->item('current_theme');
	$path = base_url().'assets/themes/'.$current_theme.'/';
	
	return $path;
}


function theme_path()
{
	$current_theme = CI::$APP->config->item('current_theme');
	$path = './assets/themes/'.$current_theme.'/';
	
	return $path;
}

function get_header()
{
	//$current_theme = CI::$APP->config->item('current_theme');
	//$path = './assets/themes/'.$current_theme.'/header';
	CI::$APP->load->theme('common/header');
}

function get_footer()
{
	CI::$APP->load->theme('common/footer');
}

function get_main_nav(){
	CI::$APP->load->theme('common/mainnav');
}

/**
*	global function to get the theme configs
*	defined in template.php within the theme
*/
function _t($config)
{
	$args = func_get_args();
	$current_theme = CI::$APP->config->item('current_theme');
	$function = $current_theme.'_'.$config;
	
	if(function_exists($function))
		return call_user_func_array($function,array_slice($args, 1));
	else return FALSE;
}

function loadCSS( $files, $print = false ){
	
	foreach ((array) $files as $f) {
		
		if (substr($f, 0, 8) == 'https://' || substr($f, 0, 7) == 'http://' || is_file( strstr($f, '?', true) ) )
			$url = $f;
		else {
			$f = ( strtolower(substr($f,-4)) == '.css' ) ? $f : $f . '.css';
			$url = (is_file(theme_path() . 'css/' . $f)) ? theme_url() : base_url() . 'assets/themes/'.CI::$APP->config->item('current_theme').'/';

			$url .= 'css/' . $f;
		}
			
		echo "<link rel='stylesheet' type='text/css' href='{$url}' " . ($print ? ' media="print"' : '') ."/>\n";

	}
}

function loadJS( $files ){
	
	foreach ((array) $files as $f) {
		
		if (substr($f, 0, 8) == 'https://' || substr($f, 0, 7) == 'http://' || is_file( strstr($f, '?', true) ) )
			$url = $f;
		else {
			$f = ( strtolower(substr($f,-3)) == '.js' ) ? $f : $f . '.js';
			$url = (is_file(theme_path() . 'js/' . $f)) ? theme_url() : base_url() . 'assets/themes/'.CI::$APP->config->item('current_theme').'/';

			$url .= 'js/' . $f;
		}
			
		echo "<script type='text/javascript' src='{$url}'></script>\n";

	}
}

function loadImage( $image, $attributes = array()) {
	
	if ( substr($image, 0, 8) == 'https://' || substr($image, 0, 7) == 'http://'  )
		$url = $image;
	else {
		$url = (is_file(theme_path() . 'images/' . $image)) ? theme_url() : base_url() . 'assets/themes/'.CI::$APP->config->item('current_theme').'/';
	
		$url .= 'images/' . $image;
	}
	
	echo "<img src='{$url}'" . _parse_attributes($attributes) . " />";
}

function locateIcon( $image ){
	
	if (strstr($image, '.') === FALSE ) $image .= '.png'; 
	
	$loc = (is_file(theme_path() . 'icons/' . $image)) ? theme_url() : base_url() . 'assets/themes/'.CI::$APP->config->item('current_theme').'/';
	
	$loc .= 'icons/' . $image;
	
	return $loc;
}

function loadPlugin( $file, $type ){
			
	if (substr($file, 0, 8) == 'https://' || substr($file, 0, 7) == 'http://' || is_file( strstr($file, '?', true) ) )
	{
		$url = $file;		
	}
	else {
		if($type == 'css')
		{
			$file = ( strtolower(substr($file,-4)) == '.css' ) ? $file : $file . '.css';
			$url = (is_file(theme_path() . 'plugins/' . $file)) ? theme_url() : base_url() . 'assets/themes/'.CI::$APP->config->item('current_theme').'/';

			$url .= 'plugins/' . $file;
			$link = "<link rel='stylesheet' type='text/css' href='{$url}'/>\n";
		}
		elseif($type == 'js')
		{
			$file = ( strtolower(substr($file,-3)) == '.js' ) ? $file : $file . '.js';
			$url = (is_file(theme_path() . 'plugins/' . $file)) ? theme_url() : base_url() . 'assets/themes/'.CI::$APP->config->item('current_theme').'/';

			$url .= 'plugins/' . $file;
		}
	}

	if($type=='css')
	{
		echo "<link rel='stylesheet' type='text/css' href='{$url}'/>\n";
	}elseif($type == 'js')
	{
		echo "<script type='text/javascript' src='{$url}'></script>\n";
	}
}
?>
