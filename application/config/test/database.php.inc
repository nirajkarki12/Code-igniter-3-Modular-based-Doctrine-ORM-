<?php defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'production';

// $active_record = TRUE;
$query_builder = TRUE;
if(DB_ACTIVE == 'mssql')
{
	$db['production']['hostname'] = '';
	$db['production']['username'] = '';
	$db['production']['password'] = '';
	$db['production']['database'] = '';
	$db['production']['dbdriver'] = 'sqlsrv';
	$db['production']['dbprefix'] = '';
	$db['production']['pconnect'] = FALSE;
	$db['production']['db_debug'] = TRUE;
	$db['production']['cache_on'] = FALSE;
	$db['production']['cachedir'] = '';
	$db['production']['char_set'] = 'utf8';
	$db['production']['dbcollat'] = 'utf8_general_ci';
	$db['production']['swap_pre'] = '';
	$db['production']['autoinit'] = TRUE;
	$db['production']['stricton'] = FALSE;
	$db['production']['port']	= '1433';
	$db['production']['driver'] = 'pdo_sqlsrv';

}elseif(DB_ACTIVE == 'mysql')
{

	$db['production']['hostname'] = 'localhost';
	$db['production']['username'] = 'root';
	$db['production']['password'] = 'root';
	$db['production']['database'] = 'custom_cms';
	$db['production']['dbdriver'] = 'mysqli';
	$db['production']['dbprefix'] = '';
	$db['production']['pconnect'] = FALSE;
	$db['production']['db_debug'] = TRUE;
	$db['production']['cache_on'] = FALSE;
	$db['production']['cachedir'] = '';
	$db['production']['char_set'] = 'utf8';
	$db['production']['dbcollat'] = 'utf8_general_ci';
	$db['production']['swap_pre'] = '';
	$db['production']['autoinit'] = TRUE;
	$db['production']['stricton'] = FALSE;
	$db['production']['port']	  = '3306';
	$db['production']['driver'] = 'pdo_mysql';

}
