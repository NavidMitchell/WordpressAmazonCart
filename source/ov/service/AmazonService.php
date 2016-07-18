<?php 
namespace ov\service;


/**
 * Handles Translation between Amazon Web service related details and our model. 
 * @author Navid Mitchell
 * 
 *
 * @Service("IProductService")
 */
class AmazonService implements IProductService{
	
    
	public $awsService = null;
	
	/**
	 * 
	 * Returns a ItemList.class.php for the given Amazon search. 
	 * @param string $keyword
	 * @param int $page
	 * @param string $searchIndex
	 * @return ItemList
	 * 
	 * 
	 */
 	public function getItemsByKeyword($keyword,$page,$searchIndex){
 	
	 	if($searchIndex != "" && $searchIndex != "All"){
	 		
	 		$res = AWSHelper::makeAWSRequest(array(
									 		'Operation' => 'ItemSearch',
									 		'Sort' => 'salesrank',
									 		'MerchantId' => 'All',
									 		'Condition' => 'New',
									 		'ResponseGroup' => 'Medium,Offers',
									 		'Keywords' => $keyword,
									 		'ItemPage' => $page,
									 		'SearchIndex' => $searchIndex
	 										));
	 		return new ItemList($res);
		}else{
			$res = AWSHelper::makeAWSRequest(array(
									 		'Operation' => 'ItemSearch',
									 		'MerchantId' => 'All',
									 		'Condition' => 'New',
									 		'ResponseGroup' => 'Medium,Offers',
											'SearchIndex' => 'All',
									 		'Keywords' => $keyword,
									 		'ItemPage' => $page
											));
											
			return new ItemList($res,5);								
		}
 	}
	
 	/**
 	 * Returns the Item for the given id.
 	 * @param string $id
 	 * @return Item
 	 */
 	public function getItemForId($id){
 		$res = AWSHelper::makeAWSRequest(array(
 										 'Operation' => 'ItemLookup',
									 	 'MerchantId' => 'All',
									 	 'Condition' => 'New',
									 	 'ResponseGroup' => 'Medium,Offers,Reviews',
									 	 'ItemId' => $id
										));
		return new Item($res->Items->Item);
 	}
 	
 	/**
 	 * Returns the related products for the given id.
 	 * @param string $id
 	 * @return ItemList
 	 */
 	public function getRelatedProducts($id){
  		$res = AWSHelper::makeAWSRequest(array(
 								 'Operation' => 'SimilarityLookup',
							 	 'MerchantId' => 'All',
							 	 'Condition' => 'New',
							 	 'ResponseGroup' => 'Medium,Offers',
							 	 'ItemId' => $id
								));
		return new ItemList($res);						
 	}
 	
}



?>