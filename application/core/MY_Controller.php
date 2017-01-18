<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends MX_Controller
{
	
	var $_CONFIG ;
	
	public function __construct()
	{

		parent::__construct();
		
		// see if the options table exists
		//if not create it.
		if(!$this->db->table_exists($this->config->item('options_table')))
			$this->createOptionsTable();
			
		if(!$this->db->table_exists($this->config->item('sess_save_path')))
			$this->createSessionTable();
		
		System::themeOK();
		
		//get the current theme settings
		$current_theme = $this->config->item('current_theme');
		$template_path = './assets/themes/'.$current_theme.'/';
		
		//load the template definitions
		Modules::load_file('template'.EXT,$template_path);
		
		//initialize mainmenu
		\MainMenu::init();
		
		foreach (Modules::$locations as $location => $offset)
		{		
			$dh = opendir($location);
			while($file = readdir($dh))
			{
				$path = $location.$file;
				if($file != "." AND $file != ".." AND is_dir($path))
				{
					$module = $file;
					if(file_exists($path."/setup.php"))
					{
						Modules::load_file("setup.php",$path.'/');
					}
				}
			}
		}
	}
	
	public function addFilter($key,$value)
	{
		global $db_filters;
		array_push($db_filters,array($key => $value));
	}
	
	
	function createOptionsTable()
	{
		$this->load->dbforge();
		$this->dbforge->add_field("id");
		$this->dbforge->add_field("`option_name` VARCHAR(100) NOT NULL");
		$this->dbforge->add_field("`option_value` TEXT NOT NULL");
		$this->dbforge->add_field("`autoload` TINYINT(1) NOT NULL DEFAULT '1'");
		$this->dbforge->add_key('option_name', TRUE);
		
		$this->dbforge->create_table($this->config->item('options_table'));
		
		$this->db->query("CREATE UNIQUE INDEX IDX_option_name ON tbl_options (option_name); ");
	}
	
	function createSessionTable()
	{
		$this->load->dbforge();
		$this->dbforge->add_field("`id` VARCHAR(40) NOT NULL");
		$this->dbforge->add_field("`ip_address` VARCHAR(45) NOT NULL");
		$this->dbforge->add_field("`timestamp` INT(10) UNSIGNED NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`data` blob NOT NULL");
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('timestamp');
		
		$this->dbforge->create_table($this->config->item('sess_save_path'));
		
	}
}