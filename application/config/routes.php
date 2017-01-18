<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['translate_uri_dashes'] = TRUE;

$route['default_controller'] = "Dashboard_Controller/index";
$route['404_override'] = 'Dashboard_Controller/pagenotfound';


// $route['country/city/index/(:num)'] = "country/city/index/$1";
// $route['country/city/index/(:any)'] = "country/";

// $route['country/state/index/(:num)'] = "country/state/index/$1";
// $route['country/state/index/(:any)'] = "country/";


/*	MY ROUTES	*/

$route[$this->config->item('admin_dir_mask')] = $this->config->item('admin_dir_mask')."/dashboard";

//content module
	//ajax
	$route['content/ajax/(.+)'] = "content/ajax/$1";
	
	//front
	$route['content/category/(.+)'] = "content/category/$1";
	$route['content/(.+)'] = "content/index/$1";

// END OF content module
$route['agent/listagents/(.+)'] = "agent/listagents/index/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */