<?php

namespace models\Common;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\Common\Collections\ArrayCollection; 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="models\Repository\Common\ReportRepository")
 * @ORM\Table(name="tbl_report_groups")
 */
class ReportGroup{
	
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=false, unique = true)
     */
    private $slug;
    
    public function __construct($name = NULL){
    	if ($name) $this->name = $name;
    }

	public function id()
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

	public function getSlug(){
		return $this->slug;
	}

}