<?php

namespace ov;

class Constants {
	
	public static function getUIDir(){
		return OVELOCITY_ROOT_DIR . '/OV/UI';
	}
	
	public static function getExternalDir(){
		return dirname(__FILE__). "/external";
	}
	
	public static function getSlinksDir(){
		return self::getExternalDir() . "/slinks"; 
	}
	
	public static function getModelDir(){
		return OVELOCITY_ROOT_DIR . '/OV/Model';
	}
	
	public static function getServiceDir(){
		return self::getRootDir() . '/ov/service';
	}
	
	public static function getRootDir(){
		return dirname(dirname(__FILE__));
	}
	
	public static function getPluginDir(){
		return OVELOCITY_PLUGIN_DIR;
	}
	
	public static function getCacheDir(){
		return OVELOCITY_ROOT_DIR . '/OV/Cache';
	}
	
	public static function getPluginURL(){
		return OVELOCITY_PLUGIN_URL;
	}
	
	public static function getJavaScriptURL(){
		return OVELOCITY_ROOT_URL . '/OV/UI/javascript';
	}
	
	public static function getCSSURL(){
		return OVELOCITY_ROOT_URL . '/OV/UI/css';
	}
	
	public static function getImageURL(){
		return OVELOCITY_ROOT_URL . '/OV/UI/images';
	}
	
	public static function isCachingEnabled(){
		return OVELOCITY_CACHING_ENABLED;
	}
	
	public static function isDebugPathsEnabled(){
		return OVELOCITY_DEBUG;	
	}
	
}

?>