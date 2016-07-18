<?php

/**
 * This class handles web resource injection. It delegates to Wordpress for actual functionality.  
 * @author navid
 * @Service("IResourceManager")
 */
class OVWP_Service_ResourceManager implements IResourceManager {
	
	public function addScript($name,$path,$version,array $dependencies = array()){
		wp_enqueue_script($name, $path, $dependencies, $version );		
	}
	
	public function addStyle($name, $path,$version,array $dependencies = array()){
		wp_enqueue_style($name, $path, $dependencies, $version);
	}
	
}


?>