<?php

namespace App;

use Symfony\Component\EventDispatcher\Event;

class Events extends Event{

	protected $object;

	public function __construct($object)
	{
		$this->object = $object;
	}

	public function getObject()
	{
		return $this->object;
	}

	public function getClass()
	{
		return get_class($this->object);
	}

}
?>