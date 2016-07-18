<?php

namespace ov\service;

/**
 * Finds products based upon the criteria. 
 * @author navid
 *
 */
interface IProductService {
	
	/**
	 * Returns a ItemList for the given search. 
	 * @param string $keyword
	 * @param int $page
	 * @param string $searchIndex
	 * @return ItemList
	 */
	public function getItemsByKeyword($keyword,$page,$searchIndex);
	
	/**
	 * Returns a Item for the given id. 
	 * @param string $id the 
	 * @return Item
	 */
	public function getItemForId($id);
	
	/**
 	 * Returns the related products for the given id.
 	 * @param string $id
 	 * @return ItemList
 	 */
 	public function getRelatedProducts($id);
	
}


?>