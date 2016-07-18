<?php
/**
 * 
 * @author navid
 * @Service("myPlugin")
 */
class OVP_RelatedProducts_Plugin implements IPlugin {
	
	public function intializeAdmin(){
		
	}
	
	public function intializeClient(){
		
		$locator = Locator::getInstance();
		
		// add needed Javascript for the client 
		/* @var $resourceManager IResourceManager */
		$resourceManager = $locator->getService('IResourceManager');
		$resourceManager->addStyle('gallery-css',Constants::getPluginURL().'/RelatedProducts/javascript/gallery-shoveler/assets/shoveler-core.css','1');
		$resourceManager->addScript('yui-3','http://yui.yahooapis.com/3.1.0/build/yui/yui-min.js','3.1.0');
		$resourceManager->addScript('gallery-shovler',Constants::getPluginURL() . '/RelatedProducts/javascript/gallery-shoveler/gallery-shoveler.js','3');
		$resourceManager->addScript('rp-script',Constants::getPluginURL() . '/RelatedProducts/javascript/rpscript.js','1.0.1');
		
		// now add some decorators to the smarty output to show related products on all product pages. 
		// register smarty prefilter
		$smarty = $locator->getService('Smarty');
		$smarty->register_prefilter('OVP_RelatedProducts_Plugin::addShoveler');
		
	}
	
	/**
	 * Adds the shoveler display to the output. 
	 * @param string $tpl_source
	 * @param SmartyExtended $smarty
	 * @return string
	 */
	public static function addShoveler($tpl_source, &$smarty){
		if($smarty->_current_file == 'item.tpl'){
			$shoveler = '<div style="width:100%" id="shoveler">' .
				'<div class="yui3-shoveler-body">' . 
				'<div class="yui3-shoveler-button-left"></div>' . 
				'<ul class="yui3-shoveler-cells">' .  
				'</ul>' . 
				'<div class="yui3-shoveler-button-right"></div>' . 
				'</div>' . 
				'</div>' ;
			$tpl_source .= $shoveler;
		}
		return $tpl_source;
	}
	
} 


?>