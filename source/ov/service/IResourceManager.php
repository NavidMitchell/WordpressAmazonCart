<?php

namespace ov\service;

/**
 * This class handles web resource injection.
 * @author navid
 *
 */
interface IResourceManager {
	
	public function addScript($name,$path,$version,array $dependencies = array());
	
	public function addStyle($name, $path,$version,array $dependencies = array());
	
}


?>