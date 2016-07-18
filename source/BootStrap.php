<?php 

use ov\Constants;
use slinks\Slinks;
use slinks\common\UniversalClassLoader;

class BootStrap {
	
	/**
	 * Intializes all class loaders. 
	 */
	public static function initialize(){
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'ov'.DIRECTORY_SEPARATOR.'Constants.php';
		
		require_once Constants::getSlinksDir().'/common/UniversalClassLoader.php';

		$loader = new UniversalClassLoader();
		$loader->registerNamespaces(array(
		    'ov'         => dirname(dirname(__FILE__)),
			'ovwp'       => dirname(dirname(__FILE__))
		));
		$loader->register();
		
		// initalize slinks 
		
		require_once(\ov\Constants::getSlinksDir().'/Slinks.php');
		Slinks::getInstance()->setDebugEnabled(true);
		Slinks::getInstance()->setCacheDirectory(Constants::getRootDir().'/cache');
		Slinks::getInstance()->initialize(Constants::getRootDir().'/services.xml',array('root.dir'=>Constants::getRootDir()));
		
	}
	
	
}

