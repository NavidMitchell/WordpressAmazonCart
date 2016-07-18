<?php

namespace ov\model;

/*
 * Created on May 14, 2007
 *
 * Class allows all operations on a Item object
 */

 class Item{
 	
 	private $awsXml=null;
 	
 	private $itemAttirbutes=null;
 	
 	private $features=null;
 	
 	private $itemAttirbuteNames=null;
 	
 	private $editorialReviews=array();
 	
 	private $description=null;
 	
 	private $reviewsChecked = false;
 	
 	private $featuresBuilt = false;
 	
 	private $imageSets=null;
 	
 	private $imageSetsBuilt=false;
 	
 	
 	/**
 	 * Build Item object form simpleXmlElement with Amazon Item object. 
 	 * @param $amazonItem this is a SimpleXMlElement Object contianing the AWS Item xml object
 	 * @param $itemAttirbuteNames array of string containg all item attributes to return. 
 	 */
 	public function __construct(&$amazonItem,$itemAttirbuteNames=null){
 		$this->awsXml = $amazonItem;
 		$this->itemAttirbuteNames =$itemAttirbuteNames;
 	}
 	
 	/**
 	 * Returns URL to Large Image
 	 */
 	public function getLargeImage(){
 		return $this->awsXml->LargeImage->URL;
 	}
 	
 	/**
 	 * Returns URL to Small Image
 	 */
 	public function getSmallImage(){
 		return $this->awsXml->SmallImage->URL;
 	}
 	
 	/**
 	 * Returns URL to Meduim Image
 	 */
 	public function getMediumImage(){
 		return $this->awsXml->MediumImage->URL;
 	}
 	
 	
 	public function getTitle(){
 		return $this->awsXml->ItemAttributes->Title;
 	}
 	
 	/**
 	 * Returns a Breif bit of info either form the editorial summary or the 
 	 * Description.
 	 */
 	public function getTeaser(){
 		if(count($this->getEditorialReviews())>0){
 			$reviews = $this->getEditorialReviews();
 			return substr($reviews[0]->getContent(), 0, 150).'...';
 		}elseif($this->getDescription() != ""){
 			return substr($this->getDescription(), 0, 150).'...';
 		}else{
 			return " This is a Great Product";
 		}
 	}
 	
 	/**
 	 * Returns String Representing Rating. String has had decimal removed so rating of 4.5 is 45 
 	 */
 	public function getRating(){
 		$stars = str_replace(".", "", $this->awsXml->CustomerReviews->AverageRating);
		if ($stars == "")
			$stars = "00";
		return $stars;	
 	}
 	
 	/**
 	 * Returns the Total number of offers.
 	 */
 	public function getTotalOffers(){
 		 return $this->awsXml->Offers->TotalOffers;
 	}
 	
 	/**
 	 * Returns a String representing the object Availability 
 	 */
 	public function getAvailability(){
 		if($this->getTotalOffers()=="0"){
 			return "This item is currently unavailable.";
 		}
 		return $this->awsXml->Offers->Offer[0]->OfferListing->Availability;
 	}
 	
 	/**
 	 * Retunrs the price of the itme. This is the regular price or the sale price if one exists. 
 	 */
 	public function getFormatedPrice(){
 		$ret = "";
 		if($this->getTotalOffers() != "0"){
	 		$salePrice = $this->awsXml->Offers->Offer[0]->OfferListing->SalePrice;
	 		if($salePrice != null && $salePrice->FormattedPrice != null){
	 			$ret = $salePrice->FormattedPrice;
	 		}else{
	 			$ret = $this->awsXml->Offers->Offer[0]->OfferListing->Price->FormattedPrice;
	 		}
 		}
 		return $ret; 
 	}
 	
 	public function getListPrice(){
 		return $this->awsXml->ItemAttributes->ListPrice->FormattedPrice;
 	}
 	
 	public function getOfferListingId(){
 		return $this->awsXml->Offers->Offer[0]->OfferListing->OfferListingId;
 	}
 	
 	
 	public function getASIN(){
 		return $this->awsXml->ASIN;
 	}
 	
 	/**
 	 * Retunrs a Numerical Array of Strings with the products features 
 	 */
 	public function getFeatures(){
 		// if null then no attributes or features have been retrieved 
 		if($this->featuresBuilt == false){
 			$this->buildFeaturesAndAttributes();
 		}
 		return $this->features;
 	}
 	
 	/**
 	 * Returns the total number of features that exists. 
 	 * @return unknown_type
 	 */
 	public function getFeatureCount(){
 		return count($this->getFeatures());
 	}
 	
 	/**
 	 * Retunrs a String with the products Description. 
 	 */
 	public function getDescription(){
 		if($this->reviewsChecked == false){
 			$this->buildReviewAndDescription();
 		}
 		return $this->description;
 	}
 	
 	public function getEditorialReviews(){
 		if($this->reviewsChecked == false){
 			$this->buildReviewAndDescription();
 		}
 		return $this->editorialReviews;
 	}
 	
 	public function getEditorialReviewCount(){
		return count($this->getEditorialReviews());
 	}
 	
 	public function getFirstEditorialReview(){
 		$ret = null;
 		if($this->getEditorialReviewCount() > 0){
 			$reviews = $this->getEditorialReviews();
 			$ret = $reviews[0];
 		}
 		return $ret->getContent();
 	}
 	
 	public function getTotalCustomerReviews(){	
 		if($this->awsXml->CustomerReviews != null && $this->awsXml->CustomerReviews->TotalReviews != null){
 			return $this->awsXml->CustomerReviews->TotalReviews;
 		}else{
 			return "0";
 		}
 	}
 	
 	/**
 	 * Retunrs array of selected Item Attributes with the name of the attribute as the key and the value 
 	 */
 	public function getItemAttributes(){
 			// if null then no attributes or features have been retrieved 
 		if($this->featuresBuilt == false){
 			$this->buildFeaturesAndAttributes();
 		}
 		return $this->itemAttirbutes;
 	}
 	
 	public function getImageSets(){
 		if($this->imageSetsBuilt == false){
 			$this->buildImageSets();
 			$this->imageSetsBuilt = true;
 		}
 		return $this->imageSets;
 	}
 	
 	public function getImageSetsCount(){
 		return count($this->getImageSets());
 	}
 	
 	/**
 	 * Builds all a list of all images to displayed as variations the the item image. 
 	 */
 	private function buildImageSets(){
 		$this->imageSets = array();
 		
 		if($this->awsXml->ImageSets != null && $this->awsXml->ImageSets->ImageSet != null){
	 		foreach($this->awsXml->ImageSets->ImageSet as $image){
	 		
	 			// add images to an array and store in the array of imageSets
	 			if($image->SwatchImage != null){
	 				$imageSet = array();
	 				$imageSet[0] = 	$image->SwatchImage->URL;
	 				$imageSet[1] =  $image->MediumImage->URL;
	 				$imageSet[2] =  $image->LargeImage->URL;
	 				$this->imageSets[count($this->imageSets)] = $imageSet;
	 			}			
	 		}
 		}
 	}
 	
 	
	private function buildFeaturesAndAttributes(){
		
		$this->itemAttirbutes = array();
		
		if($this->awsXml->ItemAttributes != null){
			// loop thur all children of item attribute building arrays
			foreach($this->awsXml->ItemAttributes->children() as $var){
				
				$name =$var->getName();
				$value = $var->asXML();	
			
				if($name == "Feature"){
					if($this->features == null){
						$this->features = array(html_entity_decode ($value));
					}else{
						$this->features[count($this->features)] = html_entity_decode ($value);
					}
				}elseif($this->itemAttirbuteNames == null || in_array($name,$this->itemAttirbuteNames)){
					// we filter by the Attribute name list or store all if no list. 
					if($this->itemAttirbutes == null){
						$this->itemAttirbutes = array($name => $value);
					}else{	
						// make sure attribute does not already have a value
						// if already exist then append
						if(array_key_exists($name,$this->itemAttirbutes)){
							$this->itemAttirbutes[$name] = $this->itemAttirbutes[$name] . "," . html_entity_decode ($value);
						}else{
							$this->itemAttirbutes[$name] = html_entity_decode ($value);
						}
					}
				}
	 		}
		}
 		$this->featuresBuilt = true;
	}
 	
 	private function buildReviewAndDescription(){
 		
 		$this->description = "";
 		
 		if($this->awsXml->EditorialReviews != null && $this->awsXml->EditorialReviews->EditorialReview != null){
 			foreach($this->awsXml->EditorialReviews->EditorialReview as $review){
 				if(strcmp($review->Source ,"Product Description")== 0){
 					$this->description = html_entity_decode ($review->Content,ENT_QUOTES);
 				}else{
 					if($this->editorialReviews == null){
 						$this->editorialReviews = array( new EditorialReview($review));
 					}else{
 						$this->editorialReviews[count($this->editorialReviews)] = new EditorialReview($review);
 					}
 				}
 			}	
 		}
 		$this->reviewsChecked = true;	
 	}
 	
 }
 
 
 
?>
