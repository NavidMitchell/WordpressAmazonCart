<?php 

namespace ov\model;

class ItemList implements Iterator{

	private $awsXML;
	private $items = array();
	private $totalPages;

	public function __construct(&$amazonItemResponse,$totalPages = -1){
		$this->awsXML = $amazonItemResponse;
		
		// FIXME: do this lazily later we are doing it really lazy now which is really eager
		 foreach($this->awsXML->Items->Item as $awsItem){
	 		$this->items[] = new Item($awsItem);
	 	}
	 	
	 	$this->totalPages = $totalPages;
	}

	
	public function getTotalPages(){
		if($this->totalPages != -1){
			return $this->totalPages;
		}else{
			return $this->awsXML->Items->TotalPages;
		}
	}

	public function rewind() {
		return reset($this->items);
	}
	
	public function current() {
		return current($this->items);
	}
	
	public function key() {
		return key($this->items);
	}
	
	public function next() {
		return next($this->items);
	}
	
	public function valid() {
		return current($this->items) !== false;
	}

	/**
	 * Returns the xml data as a Item.class.php if posible.
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	private function makeItem($value){
		$ret = $value;
		
		if(!is_bool($ret) && $ret != null){
			$ret = new Item($ret);
		}
		
		return $ret;
	}
	
}

?>