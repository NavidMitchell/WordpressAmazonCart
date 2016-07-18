<?php

namespace ov\ui;

/**
 * Displays 
 * @author navid
 *
 */
class ProductDisplay {
	
	private $smarty = null;

	function __construct() {
		$this->smarty = Locator::getInstance()->getService('Smarty');
	}
	
	/**
	 * Sends the Product Detail for the given asin to the Response Buffer. 
	 * @param String $asin the product unique identifer. 
	 * @param bool $removeFormating if true then certain formating characters should be removed prior to sending to the response buffer. 
	 */
	public function echoProductDetail($id,$removeFormating=false){
		// first check if we have the data in the cache if so we can skip this.
		if(!$this->smarty->is_cached('item.tpl','item'.$id)){
			
			$amazon_service = Locator::getInstance()->getService('IProductService');
			$item = $amazon_service->getItemForId($id);
			$this->smarty->assign('item',$item);
		}
		
		if($removeFormating){
			// remove some characters that could interfere with output
			$to_replace = array("\r\n", "\n", "\r","\t");
			echo str_replace($to_replace, "",$this->smarty->fetch('item.tpl','item'.$id));
		}else{
			$this->smarty->display('item.tpl','item'.$id);
		}
	}
	
	/**
	 * Echos a list of products to the output buffer for the given parameters. 
	 * @param unknown_type $keyword
	 * @param unknown_type $searchIndex
	 * @param unknown_type $page
	 * @param unknown_type $displayURI
	 */
	public function echoProductList($keyword,$searchIndex,$page=1,$displayURI=''){
		$cached = false;
		$productsToDisplay = false;
		
		if($this->smarty->is_cached('itemList.tpl','itemList_'.$keyword.'_'.$searchIndex.'_'.$page)){
			$cached = true;
			$productsToDisplay = true;
		}
		
		if(!$cached){
			$amazon_service = Locator::getInstance()->getService('IProductService');
			
			$itemList = $amazon_service->getItemsByKeyword($keyword,$page,$searchIndex);
			
			if($itemList != null){	
				$asinPart = '';
				$urlBegin = '';
				if(strstr($displayURI,'?')){
					$asinPart = '&asin='; 
					$urlBegin = '&';
				}else{
					$asinPart = '?asin=';
					$urlBegin = '?'; 
				}
				
				$url = $displayURI.$urlBegin.'keyword='.urlencode($keyword).'&searchindex='.urlencode($searchIndex).'&page=';
	
				// assign needed paginator vars
				$this->smarty->assign('baseURL',$url);
				$this->smarty->assign('currentPage',$page);
				$this->smarty->assign('totalPages',$itemList->getTotalPages());
				
				// assign needed item list vars
				$this->smarty->assign('uri',$displayURI.$asinPart);
				$this->smarty->assign('items',$itemList);
				$productsToDisplay = true;
			}
		}
				
		if($productsToDisplay){
			$this->smarty->display('itemList.tpl','itemList_'.$keyword.'_'.$searchIndex.'_'.$page);
		}else{
			// echo no products. 
			echo 'There are no products in this category';	
		}
	}
	
}


?>