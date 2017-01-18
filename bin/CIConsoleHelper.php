<?php

use Symfony\Component\Console\Helper\Helper;

/**
 * CI CLI Instance Helper.
 */
class CIConsoleHelper extends Helper
{
    protected $_ci;

    public function __construct(CI_Controller $ci)
    {
        $this->_ci = $ci;
    }

    
    public function getInstance()
    {
        return $this->_ci;
    }

    /**
     * @see Helper
     */
    public function getName()
    {
        return 'ci instance';
    }
}
