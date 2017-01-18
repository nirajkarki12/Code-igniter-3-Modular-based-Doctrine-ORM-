<?php

function admin_url($uri = '')
{
	$CI =& get_instance();
	return $CI->config->site_url($CI->config->item('admin_dir_mask').'/'.$uri);
}


function admin_redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = admin_url($uri);
		}

		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
	
	
function admin_base_url()
{
	$CI =& get_instance();
	return $CI->config->slash_item('base_url').$CI->config->slash_item('admin_dir_mask');
}

function encode_array($args)
{
  if(!is_array($args)) return false;
  $c = 0;
  $out = '';
  foreach($args as $name => $value)
  {
    if($c++ != 0) $out .= '&';
    $out .= urlencode("$name").'=';
    if(is_array($value))
    {
      $out .= urlencode(serialize($value));
    }else{
      $out .= urlencode("$value");
    }
  }
  return $out . "\n";
}
?>