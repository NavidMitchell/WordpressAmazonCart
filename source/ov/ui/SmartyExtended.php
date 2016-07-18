<?php

namespace ov\ui;
use ov\Constants;

// load Smarty library
require_once Constants::getExternalDir() . '/smarty/Smarty.class.php';

/**
 * 
 * @author navid
 * 
 * @Service(id="Smarty",constructor="getInstance")
 */
class SmartyExtended extends \Smarty {

	/* @var $instance SmartyExtended */
	private static $instance;


	private function __construct(){
		$this->Smarty();

		$this->template_dir = Constants::getUIDir() 		 . '/smarty_templates/';
		$this->compile_dir  = Constants::getCacheDir() . '/smartycompile/';
		$this->config_dir   = Constants::getExternalDir() . '/smarty/configs/';
		$this->cache_dir    = Constants::getCacheDir() . '/smartycache/';

		$this->plugins_dir = array('plugins', // the default under SMARTY_DIR
		Constants::getUIDir() .'/smarty_plugins');

		$this->caching = Constants::isCachingEnabled();
		$this->cache_lifetime = 86400; // 24 hours

		$this->assign('JAVASCRIPT_URL',Constants::getJavaScriptURL());
		$this->assign('IMAGE_URL',Constants::getImageURL());
		$this->assign('CSS_URL',Constants::getCSSURL());

	}

	/**
	 * Returns the singleton instance of this object
	 * @return IContainer
	 */
	public static function getInstance(){
		// if not yet intialized
		if (!isset(self::$instance)) {
			self::$instance = new SmartyExtended();
		}
		return self::$instance;
	}
	
	public function getCurrentTemplate(){
		return '';
	}

}
?>