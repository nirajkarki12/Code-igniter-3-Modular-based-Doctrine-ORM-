<?php
function widget_permissions()
{
	return array(
		'card in progress'	=>	'Card In Progress.',
		'card info widget'	=>	'Customer and Card Info.',
	);
}

if (user_access('card in progress')){

	$cardProgressWidget = array('name'		=>	"Card(s) in Progress",
							'ID'			=>	"WD_CARD_PROGRESS",
							'script'		=>	'card/CardProgress',
							'description'	=>	"List of Card(s) in Progress",
	);
	WidgetManager::register($cardProgressWidget);
}

if (user_access('card info widget')){

	$cardInfoWidget = array('name'			=>	"Customer/Card Info",
							'ID'			=>	"WD_CARD_INFO_WIDGET",
							'script'		=>	'card/CardInfo',
							'description'	=>	"Card Info",
	);
	WidgetManager::register($cardInfoWidget);
}