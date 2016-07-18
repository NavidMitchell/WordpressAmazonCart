<?php
namespace ov\model;

/*
 * Created on May 15, 2007
 *
 */

class Cart {

	private $cartId = null;
	private $hmac = null;
	private $cartXml = null;
	
	/**
	 * Allows items in cart to be added or updated 
	 * @param $itemId is the itemto add or update.
	 * @param $listId if not blank or null will be used instead of itemId when adding a new item. 
	 * @param $qty this is the quantity to set or to add depending if ther ar other items and the value of $total
	 * @param $total if total is true and a item is found the quantity will be set to $qty if $total is false
	 *  	  and a item is found the $qty will be added to the existing quanitity.
	 */
	public function addAlterItem($itemId,$listId,$qty ,$total) {
		// if we do not currently have a cart create one. 
		if ($this->isCartExist() == false) {

			// create cart
			$this->createCart($itemId,$listId, $qty);

		} else { // add item to cart

			$this->modifyCart($itemId,$listId,$qty,$total);

		}
	}

	public function isCartEmpty() {
		if ($this->isCartExist() == false) {
			return true;
		} else {
			return false;
		}
	}

	public function clearCart() {
		if ($this->isCartExist()) {
			$this->cartXml = AWSHelper::makeAWSRequest(array(
									  'Operation' 		=> 'CartClear',
									  'ResponseGroup'	=> 'Cart',
									  'CartId' 			=> $this->cartId,
									  'HMAC'			=> $this->hmac));
		}
	}

	/**
	 * Returns simple xml object contiang cart items
	 */
	public function getCartItems() {
		return $this->getCartXML()->Cart->CartItems->CartItem;
	}

	/**
	 * Returns count of cart items
	 */
	public function getCartItemsCount() {
		$ret = 0;
		if($this->getCartXML()->Cart->CartItems != null && $this->getCartXML()->Cart->CartItems->CartItem != null){
			$ret = count($this->getCartXML()->Cart->CartItems->CartItem);
		}
		return $ret;
	}

	public function getCartTotal() {
		$ret = '$0.00';
		if($this->getCartXML()->Cart->SubTotal != null && $this->getCartXML()->Cart->CartItems->CartItem != null){
			$ret = $this->getCartXML()->Cart->SubTotal->FormattedPrice;
		}
		return $ret;
	}

	public function getPurchaseUrl() {
		include_once(Constants::getExternalDir().'/nusoap/nusoap.php');
		
		$OV_RPC_URL =  (Constants::isDebugPathsEnabled() ? 'http://localhost/ovstoretracker/rpc.php' :'http://tracker.onlinevelocity.com/ovstoretracker/rpc.php');
		
		$ret = '';
		$client = new nusoap_client($OV_RPC_URL);
		$ret = $client->call('getCartUrl', array('domain' => OV_DOMAIN,'cartXml' => $this->getCartXML()->asXML()));
		
		// remove '
		$ret = substr($ret,1,-1);
		
		// Check for a fault
		if ($client->fault) {
			throw new Exception($ret);
		} else {
		    // Check for errors
		    $err = $client->getError();
		    if ($err) {
		        throw new Exception($err . $client->response, ENT_QUOTES);
		    }
		}
		
		// Clear the cookies 
		setcookie(OV_DOMAINNAME."-cartid", "", time() + 60 * 60 * 24 * 60, "/");
		setcookie(OV_DOMAINNAME."-hmac", "", time() + 60 * 60 * 24 * 60, "/");
		
		return $ret;
	}

    public function getImageUrlForItem($asin){
    	$result = AWSHelper::makeAWSRequest(array(
						  'Operation' 		=> 'ItemLookup',
						  'ResponseGroup'	=> 'Images',
    					  'MerchantId'		=> 'All',
    	    			  'Condition'		=> 'New',
						  'ItemId' 			=> $asin));
		
		$dat = new Item($result->Items->Item);

    	return $dat->getSmallImage();
    }
	
	/**
	 * Returns the carts xml object will fetch from Amazon if needed 
	 * if no cart is avialable null is returned
	 */
	private function getCartXML() {
		if ($this->isCartExist() == true and $this->cartXml == null) {
			$this->loadCart();
		}
		return $this->cartXml;
	}

	private function loadCart() {
		// found a cart so get contents
		$this->cartXml = AWSHelper::makeAWSRequest(array(
								  'Operation' 		=> 'CartGet',
								  'ResponseGroup'	=> 'Cart',
								  'CartId' 			=> $this->cartId,
								  'HMAC'			=> $this->hmac));
	}

	private function createCart($itemId,$listId, $qty) {
		//Test ULR
		// http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&AWSAccessKeyId=1PQSVWZ4970AJN9ZRQ02&Operation=CartCreate&Item.1.ASIN=B000BWZY7Q&Item.1.Quantity=1&AssociateTag=melonfire-20;
		if($listId == "" or $listId == null ){
			$this->cartXml = AWSHelper::makeAWSRequest(array(
									  'Operation' 		=> 'CartCreate',
									  'Item.1.ASIN' 	=> $itemId,
									  'Item.1.Quantity'	=> $qty));
		}else{
			$this->cartXml = AWSHelper::makeAWSRequest(array(
									  'Operation' 			  => 'CartCreate',
									  'Item.1.OfferListingId' => $listId,
									  'Item.1.Quantity'		  => $qty));
			
		}
	
		// store needed cart values
		$this->cartId = $this->cartXml->Cart->CartId;
		$this->hmac = $this->cartXml->Cart->HMAC;

		// store in cookie
		setcookie(OV_DOMAINNAME."-cartid", $this->cartId, time() + 60 * 60 * 24 * 60, "/");
		setcookie(OV_DOMAINNAME."-hmac", $this->hmac, time() + 60 * 60 * 24 * 60, "/");
	}
	
	/**
	 * Allows items in cart to be updated. The cart must exist before this method is called.  
	 * @param $itemId is the itemto add or update.
	 * @param $listId if not blank or null will be used instead of itemId when adding a new item. 
	 * @param $qty this is the quantity to set or to add depending if ther ar other items and the value of $total
	 * @param $total if total is true and a item is found the quantity will be set to $qty if $total is false
	 *  	  and a item is found the $qty will be added to the existing quanitity.
	 */
	private function modifyCart($itemId,$listId,$qty,$total){
		// now see if the item is already in the cart 
			$found = false;
			if($this->getCartXML()->Cart->CartItems->CartItem != null){
				foreach ($this->getCartXML()->Cart->CartItems->CartItem as $item) {
					if ($item->ASIN == $itemId) {
						
						$found = true;
						$itemId = $item->CartItemId;
	
						// see if qty should be addded to existing 
						if($total == false){
							$qty = $item->Quantity + $qty;
						}
						
						break;
					}
				}
			}
			if ($found == false and $qty != 0) { // add new Item 
				if($listId == "" or $listId == null){
					$res = AWSHelper::makeAWSRequest(array(
											  'Operation' 		=> 'CartAdd',
											  'ResponseGroup'	=> 'Cart',
										  	  'CartId' 			=> $this->cartId,
										  	  'HMAC'			=> $this->hmac,
											  'Item.1.ASIN' 	=> $itemId,
											  'Item.1.Quantity'	=> $qty));
					$this->cartXml = $res;
				}else{
					$res = AWSHelper::makeAWSRequest(array(
											  'Operation' 				=> 'CartAdd',
											  'ResponseGroup'			=> 'Cart',
										  	  'CartId' 					=> $this->cartId,
										  	  'HMAC'					=> $this->hmac,
											  'Item.1.OfferListingId' 	=> $listId,
											  'Item.1.Quantity'			=> $qty));
					$this->cartXml = $res;
				}	
  
			}elseif ($found == true) { // alter existing Item
				$res = AWSHelper::makeAWSRequest(array(
										  'Operation' 			=> 'CartModify',
										  'ResponseGroup'		=> 'Cart',
									  	  'CartId' 				=> $this->cartId,
									  	  'HMAC'				=> $this->hmac,
										  'Item.0.CartItemId' 	=> $itemId,
										  'Item.0.Quantity'		=> $qty));
				$this->cartXml = $res;
			}

			// re add to cookie to keep from expiring
			setcookie(OV_DOMAINNAME."-cartid", $this->cartId, time() + 60 * 60 * 24 * 60, "/");
			setcookie(OV_DOMAINNAME."-hmac", $this->hmac, time() + 60 * 60 * 24 * 60, "/");
	}

	private function isCartExist() {
		// first check if we have a existing cart. 
		$this->cartId = $_COOKIE[OV_DOMAINNAME.'-cartid'];
		$this->hmac = $_COOKIE[OV_DOMAINNAME.'-hmac'];

		if ($this->cartId == "" or $this->hmac == "") {
			return false;
		} else {
			return true;
		}
	}

}
?>
