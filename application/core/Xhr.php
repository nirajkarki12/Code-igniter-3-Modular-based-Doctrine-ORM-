<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Xhr extends MY_Controller
{
	protected $ajax = array();

	// bad request status code 
	protected $errorStatus = 400;

	// ok success status code
	protected $successStatus = 200;

	public function __construct()
	{
		parent::__construct();
		if($this->input->is_ajax_request() == FALSE)
			die('Cannot access this page directly.');
		$this->ajax['error'] = false;
		$this->ajax['data'] = null;
		$this->ajax['message'] = null;

		if(!Current_User::user()){
            $this->ajax['error'] = true;
            $this->ajax['message'] = 'Session time out.';

            $this->message->set("Session timed out.", 'error', TRUE, 'feedback');
            return $this->response($this->ajax, 401);
        }
	}

	public function response($data = NULL, $http_code = 200)
    {
    	if($http_code == 204 || $data == NULL)
    	{
    		$data = null;
    		$statusCode = 204;
    	}else{
    		$statusCode = $http_code;
    	}
    	$data = json_encode($data);

    	$this->output->set_status_header($statusCode);
    	$this->output->set_content_type('application/json');       
       	$this->output->set_output($data);
    }

    public function errorResponse($data = NULL, $http_code = 400)
    {
    	$data = json_encode($data);

    	$this->output->set_status_header($http_code);
    	$this->output->set_content_type('application/json');       
       	$this->output->set_output($data);
    }
}