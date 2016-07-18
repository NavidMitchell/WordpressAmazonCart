<?php 

namespace ovwp\ui;
use ov\Constants;


class AdminMenu {

	
	public static function initialize(){
		
		//Admin Side Hooks
		add_action('admin_menu', '\ovwp\ui\AdminMenu::createMenu');
		add_action('wp_ajax_ov_ajax_import_products', '\ovwp\ui\AjaxAdminAction::importProducts');
		add_action('wp_ajax_ov_ajax_create_page','\ovwp\ui\AjaxAdminAction::createPage');
		
		wp_enqueue_script('ov_admin_script',Constants::getJavaScriptURL().'/ov_admin.js',
						  array('jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-draggable','jquery-ui-resizable'),'1.2' );	
	}	
	
	public static function createMenu() {
	
		//create new sub menu 
		add_options_page('Amazon Store Options', 'Amazon Store Options', 'manage_options', 'ov-aws-menu', '\ovwp\ui\AdminMenu::displayAwsMenu');
		add_management_page('Amazon Store Import', 'Amazon Store Import', 'import', 'ov-import-menu', '\ovwp\ui\AdminMenu::displayImportMenu');
		add_management_page('Amazon Store Create Pages', 'Amazon Store Pages', 'import', 'ov-page-menu', '\ovwp\ui\AdminMenu::displayPageMenu');
	
		//call register settings function
		add_action( 'admin_init', '\ovwp\ui\AdminMenu::registerSettings' );
	}
	
	
	public static function registerSettings() {
		//register our settings
		register_setting( 'ov-aws', 'access_key' );
		register_setting( 'ov-aws', 'associate_tag' );
		register_setting( 'ov-aws', 'secret_key' );
	}
	
	
	public static function displayAwsMenu(){
		$smarty = Locator::getInstance()->getService('Smarty');
	
		$smarty->assign("nonce",wp_nonce_field('ov-aws-options','_wpnonce',true,false));
		$smarty->assign("access_key",get_option('access_key'));
		$smarty->assign("associate_tag",get_option('associate_tag'));
		$smarty->assign("secret_key", get_option('secret_key'));
		
		$smarty->clear_cache('ov_aws_menu.tpl');
		$html = $smarty->fetch('ov_aws_menu.tpl');
		echo $html;
	}


	public static function displayImportMenu(){
		$smarty = Locator::getInstance()->getService('Smarty');
	
		$smarty->assign("searchIndexes",array('All','Apparel','Automotive','Baby','Beauty','Blended','Books','Classical','DigitalMusic','MP3Downloads','DVD','Electronics','GourmetFood','HealthPersonalCare','HomeGarden','Industrial','Jewelry','KindleStore','Kitchen','Magazines','Merchants','Miscellaneous','Music','MusicalInstruments','MusicTracks','OfficeProducts','OutdoorLiving','PCHardware','PetSupplies','Photo','Shoes','SilverMerchants','Software','SportingGoods','Tools','Toys','UnboxVideo','VHS','Video','VideoGames','Watches','Wireless','WirelessAccessories'));
		$smarty->assign("categories",get_categories("hide_empty=0"));
		
		$smarty->clear_cache('ov_import_menu.tpl');
		$html = $smarty->fetch('ov_import_menu.tpl');
		echo $html;
	}

	
	public static function displayPageMenu(){
		$smarty = Locator::getInstance()->getService('Smarty');
	
		$smarty->assign("searchIndexes",array('All','Apparel','Automotive','Baby','Beauty','Blended','Books','Classical','DigitalMusic','MP3Downloads','DVD','Electronics','GourmetFood','HealthPersonalCare','HomeGarden','Industrial','Jewelry','KindleStore','Kitchen','Magazines','Merchants','Miscellaneous','Music','MusicalInstruments','MusicTracks','OfficeProducts','OutdoorLiving','PCHardware','PetSupplies','Photo','Shoes','SilverMerchants','Software','SportingGoods','Tools','Toys','UnboxVideo','VHS','Video','VideoGames','Watches','Wireless','WirelessAccessories'));
		
		$smarty->clear_cache('ov_page_menu.tpl');
		$html = $smarty->fetch('ov_page_menu.tpl');
		echo $html;
	}



}

?>