<?php

use Symfony\Component\EventDispatcher\EventDispatcher;

class App{

	public $dispatcher;

	public function __construct(){		
		$this->dispatcher = new EventDispatcher;
		require_once(APPPATH."third_party/App/Events.php");
	}
}
