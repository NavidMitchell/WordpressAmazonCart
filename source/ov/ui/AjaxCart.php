<?php

namespace ov\ui;

/**
 * Allows For UI cart functionality. Expects needed parameters to be part of the $_REQUEST obejct. 
 * @author navid
 *
 */
class AjaxCart {
	
	
	public static function addCart(){
		self::addUpateCart(false);
	}
	
	public static function updateCart(){
		self::addUpateCart(true);
	}
	
	private function addUpateCart($update){
		$asin = $_REQUEST['asin'];
		$qty = $_REQUEST['qty'];
		$listId = $_REQUEST['listId'];
	
		if(!is_numeric($qty)){
			$qty = 1;
		}
		
		$cart = new Cart();
		$cart->addAlterItem($asin,$listId,$qty,$update);
		
		// return the cart so the ui can be updated.
		/* @var $smarty SmartyExtended */
		$smarty = Locator::getInstance()->getService('Smarty');
		$smarty->clear_cache('cart.tpl');
		$smarty->assign("cart",$cart);
		$html = $smarty->fetch('cart.tpl');
		
		echo $html;
		die();
	}
	
	public static function checkout(){
		$data = array();
		try {
			$cart = new Cart();
			$data = array("status" => "OK","url" => $cart->getPurchaseUrl());
		} catch (Exception $e) {
			$data = array("status" => "BAD","error" =>  $e->getMessage());
		}
		
		echo json_encode($data);
		
		die();
	}
	
	public static function viewCart(){
		/* @var $smarty SmartyExtended */
		$smarty = Locator::getInstance()->getService('Smarty');
		
		if(!$smarty->is_cached('cart.tpl')){
			$cart = new Cart();
			$smarty->assign("cart",$cart);
		}
		
		$html = $smarty->fetch('cart.tpl');
		echo $html;
		die();
	}
	
	
	
}


?>