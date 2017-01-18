<?php

namespace models;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\Common\Collections\ArrayCollection; 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_permissions")
 */
class Permissions
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=255, nullable=false, unique=true)
    */
    private $name;

    /**
    * @ORM\Column(type="string",length=255)
    */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="\models\Group", mappedBy="permissions")
     **/
    private $groups;

    public function __construct() {
        $this->groups = new ArrayCollection();
    }
    
    public function __toString(){
    	return $this->name;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    public function addGroup(\models\Group $group){
        $this->groups[] = $group;
    }

    public function removeGroup(\models\Group $group){
        $this->groups->removeElement($group);
    }
}