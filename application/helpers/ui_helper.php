<?php

function ui_icon($type){
	$map = array(	'edit'		=>	'ui-icon-pencil',
					'add'		=>	'ui-icon-plusthick',
					'delete'	=>	'ui-icon-trash',
					'view'		=>	'ui-icon-arrowthick-1-e'
				);
	
	$icon = $map[$type];
	
	echo '<span class="ui-icon '.$icon.'"></span>';
}

function action_button($type,$link,$attr = array()){
	$map = array(	
		'edit'			=>	'fa-pencil',
		'add'			=>	'fa-plus fa-lg',
		'delete'		=>	'fa-trash fa-lg',
		'delete-sm'		=>	'fa-trash',
		'view'			=>	'fa-eye fa-lg',
		'permissions'	=>	'fa-lock',
		'copy'			=>	'fa-copy',
		'block'			=>	'fa-ban',
		'unblock'		=>  'fa-check-square-o',
		'wrench'		=> 	'fa-wrench',
		'user'			=> 	'fa-user',
		'check'			=> 	'fa-check fa-lg',
		'excel'			=>	'fa-file-excel-o fa-lg',
		'print'			=>	'fa-print',
		'arrow'			=>	'fa-long-arrow-right',
		'key'			=>	'fa-key',
		'cross'			=>	'fa-times fa-lg',
		'upload'		=>	'fa-upload',
		'undo'			=>	'fa-undo fa-lg',
		
	);	
	$icon = isset($map[$type]) ? $map[$type] : 'fa-'.$type;
	
	//build attributes
	$attributes = '';
	$class = '';
	if(is_array($attr)){
		foreach($attr as $key => $value){
			if(strtolower(trim($key)) != 'class')
				$attributes .= $key.'="'.$value.'"';
			else
				$class .= ' '.$value;
		}
	}	
	echo '<a href="'.$link.'" '.$attributes.' class="'.$class.'"><i class="fa '.$icon.'"></i></a>';
}