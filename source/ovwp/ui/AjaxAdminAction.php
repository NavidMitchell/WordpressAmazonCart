<?php
namespace ovwp\ui;

/**
 * 
 * @author navid
 *
 */
class AjaxAdminAction{
	
	
	public static function createPage(){
		$searchIndex = $_REQUEST['searchIndex'];
		$keyword = $_REQUEST['keyword'];
		$page =$_REQUEST['page'];
		
		// create page with our products shortcode
		$post_id = wp_insert_post( array(
		'post_author'	=> 1,
		'post_title'	=> $page,
		'post_content'	=> '[ov_products searchindex="'.$searchIndex.'" keyword="'.$keyword.'"]',
		'post_type' 	=> 'page',
		'post_status' 	=> 'publish'
		));
		
		if(is_wp_error($post_id)){
			echo 'Error : '. $post_id->get_error_message();
		}else{
			echo 'OK';
		}
		die();
	}
	
	
	/**
	 * Imports products into the desired category. 
	 * @deprecated This method has been depricated its functionality will no longer be supported. 
	 */
	public static function importProducts(){
		try{
			
			$categoryId = $_REQUEST['category'];
			$searchIndex = $_REQUEST['searchIndex'];
			$keyword = $_REQUEST['keyword'];
			$max = $_REQUEST['max'];
		
			$amazon_service = Locator::getInstance()->getService('IProductService');
			// since this is a amazon category update the posts in the db. 
			$itemList = $amazon_service->getItemsByKeyword($keyword,1,$searchIndex);
			
			$smarty = Locator::getInstance()->getService('Smarty');
			
			$totalPages = $itemList->getTotalPages();
			$productsImported = 0;
			
			// set max to 50 if SearchIndex is All 
			if($searchIndex == "" || $searchIndex == "All"){
				$max = 50;
			}
			
			for($i = 1; $i<=$totalPages && $productsImported < $max; $i++){
			
				if($itemList == null){
					$itemList = $amazon_service->getItemsByKeyword($keyword,$i,$searchIndex);
					
					// if no prouducts stop 
					if($itemList == null){
						break;
					}
				}
				
				foreach ($itemList as $item) {
					
					$smarty->assign('item',$item);
					
					// remove some characters that wordpress adds formatting for. 
					$to_replace = array("\r\n", "\n", "\r","\t");
					
					$smarty->clear_cache('item.tpl','import-item'.$item->getASIN());
					$post_content =str_replace($to_replace, "",$smarty->fetch('item.tpl','import-item'.$item->getASIN()));
					
					// now create post for a item
					$post_id = wp_insert_post( array(
					'post_author'	=> 1,
					'post_title'	=> $item->getTitle(),
					'post_content'	=> $post_content,
					'post_category'	=> array($categoryId),
					'post_type' 	=> 'post',
					'post_status' 	=> 'publish',
					'post_excerpt'	=> $item->getTeaser()
					));
					
					$productsImported++;
					
					if($productsImported >= $max){
						break;
					}
				}
				
				$itemList = null;
			}
			echo "OK";
		}catch(Exception $e){
			echo $e->getMessage();
		}		
		
		die();
	}
	
}