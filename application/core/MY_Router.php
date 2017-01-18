<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {
public function locate($segments) {        
        
        $this->module = '';
        $this->directory = '';
        $ext = $this->config->item('controller_suffix').EXT;
        
        /* use module route if available */
        if (isset($segments[0]) AND $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
            $segments = $routes;
        }
        
        /**
        *    edit to route the administrative controllers
        *    without needing to go through the uri module/admin/module
        *    if the 1st segment is the administrative directory mask automatically routes to the 
        *    administrative controller
        */
        if($segments[0] == $this->config->item('admin_dir_mask'))
        {
            $segments[0] = $this->config->item('admin_dir');
            $module = $segments[1];
            array_unshift($segments,$module);
		/*	echo "<pre>";
			print_r($segments);
			echo "</pre>";*/
			
			//quick fix
			
			if(($segments[0] == $segments[2])){
				unset($segments[2]);
				$segments = array_values($segments);
			}/**/
			
			
        }
        /* get the segments array elements */
        list($module, $directory, $controller) = array_pad($segments, 3, NULL);
		$controller = ucfirst($controller);
		//echo $controller.$ext;
      /*  check modules */
        foreach (Modules::$locations as $location => $offset) {
        
            /* module exists? */
            if (is_dir($source = $location.$module.'/controllers/')) {
                
				
                $this->module = $module;
                $this->directory = $offset.$module.'/controllers/';
                
                /* module sub-controller exists? */
				$subcontrollerFile = $source.ucfirst($directory).$ext;
				//echo $subcontrollerFile;
                if($directory AND is_file($subcontrollerFile)) {
               
                    return array_slice($segments, 1);
                }
                    
                /* module sub-directory exists? */
                if($directory AND is_dir($source.$directory.'/')) {

                    $source = $source.$directory.'/'; 
                    $this->directory .= $directory.'/';
					$module = ucfirst($module);
					
				//	echo " Admin directory found.\n";
					//echo $source.$module.$ext;
					
                    /* module sub-directory controller exists? */
					// The ajax controllers are loaded here.
                    if(is_file($source.$directory.$ext)) {
                        return array_slice($segments, 1);
                    }
               		
					
                    /* module sub-directory sub-controller exists? */
                    if($controller AND is_file($source.$controller.$ext))    {
						//echo " Main controller found.\n";
                        return array_slice($segments, 2);
                    }
					
					
					/* module controller exists? */            
					if(is_file($source.$module.$ext)) {
						$segments =  array_slice($segments, 2);
						array_unshift($segments,$module);
						return $segments;
					}
                }
                
                /* module controller exists? */ 
				$module = ucfirst($module);           
                if(is_file($source.$module.$ext)) {
					//echo $source.$module.$ext;
					//show_pre($segments);
                    return $segments;
                }
            }
        }
		//if the controller is not from the modules
		//remove the module parameter and search for application controllers
		if(isset($segments[1]) && ($segments[1] == $this->config->item('admin_dir')))
			array_shift($segments);
			
		$module = ucfirst($module);
		
		
        /* application controller exists? */            
        if (is_file(APPPATH.'controllers/'.$module.$ext)) {
			//echo $module.$ext;
            return $segments;
        }
        
		/* application controller exists? */            
        if (is_file(APPPATH.'controllers/'.$directory.'/'.$module.$ext)) {
            $this->directory = $directory.'/';
            return array_slice($segments, 1);
        }
		
        /* application sub-directory controller exists? */
        if($directory AND is_file(APPPATH.'controllers/'.$module.'/'.$directory.$ext)) {
            $this->directory = $module.'/';
            return array_slice($segments, 1);
        }
        
        /* application sub-directory default controller exists? */
        if (is_file(APPPATH.'controllers/'.$module.'/'.$this->default_controller.$ext)) {
            $this->directory = $module.'/';
            return array($this->default_controller);
        }
    } 	
}
