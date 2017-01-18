<?php
function the_dashboard()
{
	
	foreach(Dashboardshortcut::$shortcuts as $s)
	{
		if(user_access($s['permission']))
		{
			echo '<li><a href="' .admin_url($s['controller']). '"><img src="' .base_url().'assets/images/'.$s['icon']. '"><span>' .$s['name']. '</span></a></li>';
		}
	}
}