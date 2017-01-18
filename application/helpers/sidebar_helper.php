<?php

function getSidebar($menu = NULL,$depth = 0)
{
	$CI =& get_instance();
	$currentUriSegments = $CI->uri->segment_array();
	array_shift($currentUriSegments);
	//$currentUri = implode('/',$currentUriSegments);
	$currentUri = isset($currentUriSegments[0]) ? $currentUriSegments[0]:'';
	
	if($depth == 0)
		$html = '<div id="sidebar"><ul class="sideNav">';
	else
		$html = '<ul class="sub">';
	
	$imenu = ($menu == NULL) ? Sidemenu::$sidemenu:$menu;
	foreach($imenu as $k => $v)
	{
		//check permissions
		$permissions = explode('|',$v['permissions']);
		
		$allowed = TRUE;
		foreach($permissions as $p){
			$p = trim($p);
			if(!user_access($p)){
				$allowed = FALSE;
				continue;
			}
		}
		
		if($allowed){
			$class = ($currentUri == $v['url'] && $depth == 0) ? ' class="active"':'';
			$url = ($v['url'] == '#') ? '#':admin_url($v['url']);
				
			$html .="<li><a href='{$url}'{$class}><span>{$v['name']}</span></a>";
			if(isset($v['children']) && $depth == 0)
			{
				$html .= getSidebar($v['children'],1);
			}
			$html .="</li>";
		}
	}				
		
	if($depth == 0)
		$html .= '</ul></div>';
	else
		$html .= '</ul>';
	
	
	return $html;
	
}

?>