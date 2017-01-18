<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*	My Constants	*/
define('MAINMENU_DASHBOARD',1);
define('MAINMENU_WEBSITE',2);
define('MAINMENU_USER',3);
define('MAINMENU_SETTING',4);
define('MAINMENU_CONTENT',5);

define('MAINMENU_PROFILE',6);

//groups
define('ROLE_SUPER_ADMIN',1);
define('ROLE_UNASSIGNED',2);

//status
define('STATUS_ACTIVE',1);
define('STATUS_INACTIVE',0);
define('STATUS_DELETED','deleted');

//taxonomies
define('TAXONOMY_CATEGORY','category');
define('TAXONOMY_TAG','tag');
define('DEFAULT_CATEGORY','Uncategorised');

define('HTTP_OK',200);
define('HTTP_CREATED',201);
define('HTTP_ACCEPTED',202);

define('HTTP_BAD_REQUEST',400);
define('HTTP_NOT_AUTHORIZED',403);
define('HTTP_NOT_FOUND',404);

define('HTTP_INTERNAL_SERVER_ERROR',500);

define('DB_ACTIVE','mysql'); //'mysql/mssql'

/* End of file constants.php */
/* Location: ./application/config/constants.php */