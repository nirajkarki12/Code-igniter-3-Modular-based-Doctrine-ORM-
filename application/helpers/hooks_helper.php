<?php

/*
** content shortcodes
*/

function register_shortcode($shortcode,$function)
{
	Shortcode::register($shortcode,$function);
}

function do_shortcode($shortcode,$args = NULL)
{
	return Shortcode::run($shortcode,$args);
}

/*
** dashboard shortcuts
*/
function register_dashboard_shortcut($shortcut)
{
	Dashboardshortcut::register($shortcut);
}

