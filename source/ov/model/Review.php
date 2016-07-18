<?php

namespace ov\model;

/*
 * Created on May 14, 2007
 *
 * Adapter to allow conversion of Amazon Review objects stripped of Any HTML
 */
 
 class Review {
 	
 	private $simpleXml;
 	
 	function __construct($amazonReview){
 		$this->simpleXml = $amazonReview;
 	}
 	
 	public function getReviewDate(){
 		return $this->simpleXml->Date;
 	}
 	
 	public function getRating(){
 		return $this->simpleXml->Rating;
 	}
 	
 	public function getSummary(){
 		return html_entity_decode ($this->simpleXml->Summary);
 	}
 	
 	public function getContent(){
 		return html_entity_decode ($this->simpleXml->Content);
 	}
 	
 
 	
 }
 
?>
