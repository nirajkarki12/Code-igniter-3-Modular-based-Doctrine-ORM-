<?php

class WidgetManager
{
	static $widgets = array();
	
	/**
	 * 
	 * @param array $widget
	 * 
	 * Example:
	 * 
	 * [php]
	 * $widget = array(	'name'			=>	"Today's Transaction",
	 * 					'ID'			=>	"WD_TODAY_TXN",
	 * 					'script'		=>	"transaction/TxnAmount",
	 * 					'description'	=>	"Shows todays transaction summary"
	 * 					'permissions'	=>	"transaction status" //ANDed or ORed permission list
	 * 				)
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public static function register(array $widget){
		if(!isset($widget['name']) || 
			!isset($widget['ID']) ||
			!isset($widget['script'])){
			
				throw new \InvalidArgumentException('Incomplete widgets definition.');
		}
		
		$ID = $widget['ID'];
		if(isset(self::$widgets[$ID]))
			throw new \Exception("A widget with ID :: {$ID} is already registered.");
		$permission = TRUE;
		if(isset($widget['permissions']) && !user_access($widget['permissions'])) $permission = FALSE;

		if ($permission == TRUE) 
			self::$widgets[$ID] = $widget;
	}
	
	public static function render(){
		if(count(self::$widgets))
		{
			$optionArray = \Options::get('widgets');
			if($optionArray)
			{
				foreach(self::$widgets as $widget){
					if(isset($optionArray) && in_array($widget['ID'], $optionArray))
					{
						self::renderWidget($widget);
					}
				}
			}
			
		}
	}

	public static function view(){
		return count(self::$widgets) ? self::$widgets : false;
	}
	
	private static function renderWidget($widget){
		$newwidget = new \Widget;

		echo '<div class="col-md-4">';
		echo '<div class="box box-warning direct-chat direct-chat-warning">';
		echo '<div class="box-header with-border">';
		echo '<h3 class="box-title">'.$widget['name'].'</h3>';
		echo '<div class="box-tools pull-right">';
		echo '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		</button>';
		echo '<button type="button" class="btn btn-box-tool" data-widget="remove" id="remove"><i class="fa fa-times"></i></button>';
		echo '</div>';
		echo '</div>';
		echo '<div class="box-body">';
			$newwidget->run($widget['script']);
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	
}