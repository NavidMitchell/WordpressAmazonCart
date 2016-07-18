<?php
/**
 * 
 * Version: 1.3.1
 * Author: Navid Mitchell
 **/

use ov\Constants;

class OVAmazonStore {

	/**
	 * Initializes needed global vars. 
	 * TODO: remove usage of globals except in include paths. 
	 */
	private static function initializeGlobals(){
		// Define Globals
		$url = $_SERVER["HTTP_HOST"];
		$idx = strpos($url, "www.");
		$domain = null;
		$domainName = null;
		// see how domain looks
		if ($idx === false) {
			// find end of domain name
			$lidx = strpos($url, ".");
			$domain = $url;
		} else {
			// start after www. 
			$lidx = strpos($url, ".", 4);
			$domain = substr($url, 4, strlen($url) - 4);
		}
	
		$endStr = substr($url, $lidx, strlen($url) - $lidx);
		$domainName = substr($domain, 0, strlen($domain) - strlen($endStr));
	
		if ( ! defined( 'OV_DOMAIN' ) )
			define("OV_DOMAIN", $domain);
		if ( ! defined( 'OV_DOMAINNAME' ) )	
			define("OV_DOMAINNAME", $domainName);
		
		// These globals must be defined for the Constants and registerOVLoader() (below) to work properly. 
		// The rest of the app uses constants from there. 
		// TODO: it would better to remove all globals. 

		if( ! defined('OVELOCITY_ROOT_URL')){
			if(OVELOCITY_DEBUG){
				define('OVELOCITY_ROOT_URL','http://localhost/'.OVELOCITY_ROOT);
			}else{
				define('OVELOCITY_ROOT_URL',WP_PLUGIN_URL.OVELOCITY_ROOT);
			}	
		}
			
		if( ! defined('OVELOCITY_PLUGIN_DIR'))
			define('OVELOCITY_PLUGIN_DIR',(OVELOCITY_DEBUG ? OVELOCITY_ROOT_DIR . '/OVP' : WP_CONTENT_DIR . '/online-velocity-plugins'));
				
		if( ! defined('OVELOCITY_PLUGIN_URL'))
			define('OVELOCITY_PLUGIN_URL',(OVELOCITY_DEBUG ? OVELOCITY_ROOT_URL . '/OVP' : WP_CONTENT_URL . '/online-velocity-plugins'));
	}
		
	/**
	 * Initializes Plugin
	 * 
	 * @return unknown_type
	 */
	public static function initialize(){
		
		
		
		self::initializeGlobals();

		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'BootStrap.php';
		BootStrap::initialize();
		
		// if no caching clear compiled smart templates
		// this is helpful during debugging. 
		if(!Constants::isCachingEnabled()){
			/* @var $smarty SmartyExtended */
			$smarty = Locator::getInstance()->getService('Smarty');
			$smarty->clear_all_cache();
			$smarty->clear_compiled_tpl();
		}
		
		// load all needed css && javascript files 
		// currently all of these are used by both admin and client side. 
		// The fancybox stuff is used for the loading icon. 
		wp_enqueue_style('ov_css',Constants::getCSSURL().'/styles.css',false,'1.0');
		
		// Fancybox
		wp_enqueue_style('fancybox_css',Constants::getJavaScriptURL().'/fancybox/jquery.fancybox-1.3.1.css',false,'1.0');				 
		wp_enqueue_script('fancybox_script',Constants::getJavaScriptURL().'/fancybox/jquery.fancybox-1.3.1.pack.js',array('jquery'),'1.0' );	
		wp_enqueue_script('fancybox_easing_script',Constants::getJavaScriptURL().'/fancybox/jquery.easing-1.3.pack.js',array('jquery'),'1.0' );
		wp_enqueue_script('fancybox_mouse_script',Constants::getJavaScriptURL().'/fancybox/jquery.mousewheel-3.0.2.pack.js',array('jquery'),'1.0' );	
								  				  			  
		// ov client and admin 
		wp_enqueue_style('jquery_ui_css',Constants::getCSSURL().'/start/jquery-ui-1.7.2.custom.css',false,'1.0');	
		wp_enqueue_script('ov_ajax_script',Constants::getJavaScriptURL().'/ov_ajax.js', array('jquery'),'1.2' );	
		 
						  
		// Client Side Stuff
		wp_enqueue_script('ov_cart_script',Constants::getJavaScriptURL().'/ov_cart.js',
						  array('jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-draggable','jquery-ui-resizable'),'1.2' );	
		wp_enqueue_script('ov_image_script',Constants::getJavaScriptURL().'/ov_image.js',
						  array('jquery'),'1.2' );
						  
		// Adds Needed Javascript Global vars.
		add_action('wp_head', 'OVAmazonStore::addJavascriptVars' );
		
		// client side ajax callback actions
		add_action('wp_ajax_ov_ajax_add_cart', 'AjaxCart::addCart');
		add_action('wp_ajax_nopriv_ov_ajax_add_cart', 'AjaxCart::addCart');
		
		add_action('wp_ajax_ov_ajax_view_cart', 'AjaxCart::viewCart');
		add_action('wp_ajax_nopriv_ov_ajax_view_cart', 'AjaxCart::viewCart');
		
		add_action('wp_ajax_ov_ajax_update_cart', 'AjaxCart::updateCart');
		add_action('wp_ajax_nopriv_ov_ajax_update_cart', 'AjaxCart::updateCart');
		
		add_action('wp_ajax_ov_ajax_checkout', 'AjaxCart::checkout');
		add_action('wp_ajax_nopriv_ov_ajax_checkout', 'AjaxCart::checkout');
		
		// short codes 
		add_shortcode('ov_add_cart', 'OVAmazonStore::add_cart_sc');
		add_shortcode('ov_view_cart', 'OVAmazonStore::view_cart_sc');
		add_shortcode('ov_products', 'OVAmazonStore::productsShortCode');
		
		\ovwp\ui\AdminMenu::initialize();
		
		// now intialize any plugins. 
		$pluginLoader = PluginLoader::getInstance();
		$ids = $pluginLoader->getServiceIds();
		foreach ($ids as $id){
			/* @var $plugin IPlugin */
			$plugin = $pluginLoader->getService($id);
			// for some reason the sfServiceContainerBuilder id is returned. 
			if($plugin instanceof IPlugin){
				$plugin->intializeClient();
			}
		}
		
	}
	
	
	public static function postInstall(){
		// clear the smarty cache. 
		self::registerOVLoader();
		$smarty = Locator::getInstance()->getService('Smarty');
		$smarty->clear_all_cache();	
	}
	
	/**
	 * Adds Needed Javascript Global vars.
	 * @return unknown_type
	 */
	public static function addJavascriptVars(){
		echo "<script type='text/javascript'>";
		echo "var PLUGIN_URL ='".OVELOCITY_ROOT_URL."';";
		echo "var ajaxurl ='".admin_url('admin-ajax.php')."';";
		echo "</script>";
	}
	
	/*** Cart Functions ***********************/
	
	/**
	 * Deprecated
	 * @param unknown_type $atts
	 */
	public static function add_cart_sc($atts){
		extract(shortcode_atts(array(
				'asin' => '-1',
				'qty' => '1',
				), $atts));
		
		return "<input class='ov-float-right-button ui-state-default ui-corner-all' type='button' onclick='OV_addCart(\"{$asin}\",{$qty})' value='Add to Cart'>";
	}
	
	public static function view_cart_sc($atts){	
		extract(shortcode_atts(array(
				'label' => 'View Cart',
				), $atts));
		
		return self::view_cart_html($label);
	}
	
	
	
	public static function view_cart_html($label){
		return "<li><span class='view_cart'><a href='#' id='ov_view_cart_btn'>{$label}</a></span></li>";
	}
	
	/**************** Category displ funcs ****************/
	
	public static function productsShortCode($atts){
		
		$display = new ProductDisplay();
		
		extract(shortcode_atts(array(
								'asin' => '',
								'keyword' => '',
								'searchindex' => 'All',
								'page' => '1'
								), $atts));
		
		if( isset($_REQUEST['asin']) && !empty($_REQUEST['asin']) ){
			$asin = $_REQUEST['asin'];
		}
		
		if( isset($_REQUEST['page']) && !empty($_REQUEST['page']) ){
			$page = $_REQUEST['page'];
		}
		
		if( isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword']) ){
			$keyword = $_REQUEST['keyword'];
		}
		
		if( isset($_REQUEST['searchindex']) && !empty($_REQUEST['searchindex']) ){
			$searchindex = $_REQUEST['searchindex'];
		}
					
		if( $asin != ''){
			$display->echoProductDetail($asin,true);
		}else{
			$display->echoProductList($keyword,$searchindex,$page,get_page_link());	
		}
	}


}

add_action('init', 'OVAmazonStore::initialize');

/**
 * Adds a view cart link to the HTML response
 * TODO: move to more appropriate location
 * @param unknown_type $label
 */
function ov_view_cart_tag($label='View Cart'){
	echo OVAmazonStore::view_cart_html($label);
}


?>