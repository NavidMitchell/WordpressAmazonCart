<?php
namespace ov\model;

/*
 * Created on May 15, 2007
 *
 * Build Editorial Review from simple XMl Element
 */
 
  class EditorialReview {
 	
 	private $simpleXml;
 	
 	function __construct($amazonReview){
 		$this->simpleXml = $amazonReview;
 	}
 	
 	public function getSource(){
 		return $this->simpleXml->Source;
 	}
 	
 	public function getContent(){
 		return html_entity_decode ($this->simpleXml->Content);
 	}
 	
 
 	
 }
 
 
?>
