<?php

class MainMenuItem{
	
	private $id;

	private $order=null;
	
	private $name;

	private $icon;
	
	private $route;
	
	private $parent = NULL;
	
	private $children = NULL;
	
	private $permissions = array();
	
	public function __construct(){
		
	}
	
	public function setOrder($order){
		$this->order = $order;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function setIcon($icon)
	{
		$this->icon = $icon;
	}

	public function getIcon()
	{
		return $this->icon ? 'fa fa-'.$this->icon : 'fa fa-circle-o';
	}

	public function getRoute()
	{
	    return $this->route;
	}

	public function setRoute($route)
	{
	    $this->route = $route;
	}

	public function getPermissions()
	{
	    return $this->permissions;
	}

	public function setPermissions($permissions = array())
	{
	    $this->permissions = $permissions;
	}

	public function getParent()
	{
	    return $this->parent;
	}

	public function setParent(\MainMenuItem $parent)
	{
	    $this->parent = $parent;
	}

	public function getChildren()
	{
	    return $this->children;
	}
	
	public function addChild(\MainMenuItem $child){
		$this->children[] = $child;
	}
	
	public function removeChild(\MainMenuItem $child)
	{
		$key = array_search($child, $this->children, true);
	
		if ($key !== false) {
			unset($this->children[$key]);
	
			return true;
		}
	
		return false;
	}

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}
}