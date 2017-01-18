<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	function set_success_flashdata($newdata = array(), $newval = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $this->flashdata_key.':new:'.$key;
                $this->set_userdata($flashdata_key, "<div class='flashdata alert alert-success'>".$val."</div>");
            }
        }
    }

    function set_error_flashdata($newdata = array(), $newval = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $this->flashdata_key.':new:'.$key;
                $this->set_userdata($flashdata_key, "<div class='flashdata alert alert-danger'>".$val."</div>");
            }
        }
    }
}