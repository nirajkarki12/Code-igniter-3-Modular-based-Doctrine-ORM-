<?php

function slider_permissions()
{
	return array(
		'view sliders'		=>	'View sliders.',
		'add slider'		=>	'Add slider.',
		'modify slider'		=>	'Edit/delete slider.',
	);
}

if(\Options::get('slider'))
{
	$sliderMenu = new MainMenuItem();
	$sliderMenu->setName('Slider');
	$sliderMenu->setIcon('newspaper-o');
	$sliderMenu->setId('MM_SLIDER');
	$sliderMenu->setOrder(3);
	$sliderMenu->setPermissions(array('view slider','add slider'));
	$sliderMenu->setRoute('javascript:void(0);');
	MainMenu::register($sliderMenu);

	$listSliderMenu = new MainMenuItem();
	$listSliderMenu->setName('View Slider');
	$listSliderMenu->setIcon('caret-right');
	$listSliderMenu->setId('MM_SLIDER_LIST');
	$listSliderMenu->setOrder(1);
	$listSliderMenu->setParent($sliderMenu);
	$listSliderMenu->setPermissions(array('view slider'));
	$listSliderMenu->setRoute(site_url('slider/list'));
	\MainMenu::register($listSliderMenu);

}
