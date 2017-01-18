<?php
/* load the MX_Loader class */
require APPPATH."third_party/MX/Config.php";
class MY_Config extends MX_Config {

	function __construct()
	{
		parent::__construct();
	}
	
	public function site_url($uri = '', $protocol = NULL)
	{
		//return false;
		if ($uri == '')
		{
			return $this->slash_item('base_url').$this->item('index_page');
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}
			
			
			//handle the hash tags in the uri			
			$hashTag = '';
			if($act = strstr($uri,'#'))
			{
				$uri = substr($uri,0,-strlen($act));
				$hashTag = $act;
			}
			
			$index = $this->item('index_page') == '' ? '' : $this->slash_item('index_page');
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$index.trim($uri, '/').$suffix.$hashTag;
		}
		else
		{
			if (is_array($uri))
			{
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val)
				{
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix.$key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			return $this->slash_item('base_url').$this->item('index_page').'?'.$uri;
		}/**/
	}
}
?>