<?php

function user_permissions()
{
	return array(
		'view users'		=>	'View users.',
		'add user'			=>	'Add user.',
		'modify user'		=>	'Edit, block, unblock and delete user.',
		'allow user switching'=>'Allow user Switch',
		
		'view user groups'	=>	'View user groups.',
		'add user group'	=>	'Add group.',
		'modify user group'	=>	'Edit, block, unblock and delete group.',

		'moderate permission' => 'Moderate group permissions.',
		'force change password' => 'Force Change Password.',

		'reset password'	=> 'Reset other user\'s password.'
	);
}

// if(!(\Current_User::isSuperUser()))
// {
	$userMenu = new MainMenuItem();
	$userMenu->setName('Users');
	$userMenu->setIcon('user');
	$userMenu->setId('MM_USER');
	$userMenu->setOrder(2);
	$userMenu->setPermissions(array('view users'));
	$userMenu->setRoute('javascript:void(0);');
	MainMenu::register($userMenu);

	$listUserMenu = new MainMenuItem();
	$listUserMenu->setName('View Users');
	$listUserMenu->setIcon('caret-right');
	$listUserMenu->setId('MM_USER_LIST');
	$listUserMenu->setOrder(2);
	$listUserMenu->setParent($userMenu);
	$listUserMenu->setPermissions(array('view users'));
	$listUserMenu->setRoute(site_url('user'));
	\MainMenu::register($listUserMenu);

	$createUserMenu = new \MainMenuItem();
	$createUserMenu->setIcon('caret-right');
	$createUserMenu->setId('MM_USER_CREATE');
	$createUserMenu->setOrder(1);
	$createUserMenu->setName('Add User');
	$createUserMenu->setPermissions(array('view users','add user'));
	$createUserMenu->setParent($userMenu);
	$createUserMenu->setRoute(site_url('user/add'));
	\MainMenu::register($createUserMenu);

	$groupMenu = new \MainMenuItem();
	$groupMenu->setIcon('caret-right');
	$groupMenu->setId('MM_USER_GROUP');
	$groupMenu->setOrder(3);
	$groupMenu->setName('View Groups');
	$groupMenu->setPermissions(array('view user groups'));
	$groupMenu->setParent($userMenu);
	$groupMenu->setRoute(site_url('user/group'));
	\MainMenu::register($groupMenu);

// }

?>