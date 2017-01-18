<?php

namespace models\Common;

use Doctrine\ORM\Mapping as ORM; 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_options")
 */
class Options{
    
    /**    
     * @ORM\Id
     * @ORM\Column(type="string", name="`option_name`", length=255, unique=true, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", name="`option_value`", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="smallint", options={"default": 1})
     */
    private $autoload = 1;  

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name= $name;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($v)
    {
        $this->value= $v;
    }

    public function setAutoload($autoload)
    {
        $this->autoload = $autoload;
        return $this;
    }

    public function getAutoload()
    {
        return $this->autoload;
    }
}
