<?php

namespace RQL;

abstract class RQLFilter{
	public $name;
	
	public function __construct($name){
		$this->name = $name;
	}
	
	abstract public function getSql();
	
	abstract public function parse(\RQL\Parser $parser);
}