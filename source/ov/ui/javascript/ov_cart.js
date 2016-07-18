var cartDialog = null;
var cartDiv = null;
var debug =false;

function isCartOpen(){
	return cartDialog.dialog('isOpen');
}

function openCart(){
	if(!isCartOpen()){
		cartDialog.dialog( 'open' );
	}
}

function closeCart(){
	if(isCartOpen()){
		cartDialog.dialog( 'close' );
	}
}

function OV_viewCart(){
	var data = {
			action: 'ov_ajax_view_cart'
		};
	
	// TODO: Check some type of response code.
	jQuery.get(ajaxurl, data, function(response) {
		cartDiv.html(response);
		openCart();
	});
	return false;
}

jQuery(document).ready(function($) {
	//Setup cart dialog. 
    cartDiv = $('<div id="cartDiv" style="display:hidden"></div>')
				.appendTo('body');
    
	cartDialog = cartDiv.dialog({
    	 	 			title	 : 'Shopping Cart',
    	 	 			width	 : 800,
    	 	 			autoOpen : false,
    	 	 			hide     : 'puff',
    	 	 			modal	 : true
					});
	
	// create view cart click functionality
    $('#ov_view_cart_btn').click(function() {
    	OV_viewCart();
        return false;
    });
  
});




function OV_addCart(asin,quantity){

	var data = {
		action: 'ov_ajax_add_cart',
		asin: asin,
		qty: quantity
	};
	// TODO: Check some type of response code.
	jQuery.get(ajaxurl, data, function(response) {
		if(isCartOpen()){
			cartDiv.html(response);
		}
	});
}

function OV_updateCart(formId){
	
	var asin = document.forms[formId].elements['asin'].value;
	var qty = document.forms[formId].elements['qty'].value;
	
	if(!qty){
		alert('Problem reading QTY');
	}
	
	OV_doUpdateCart(asin,qty,function(response){
		if(isCartOpen()){
			// update cart disp
			cartDiv.html(response);
		}
	});
}

function OV_doUpdateCart(asin,qty,callback){
	var data = {
			action: 'ov_ajax_update_cart',
			asin: asin,
			qty: qty
			};
	// TODO: Check some type of response code.
	jQuery.get(ajaxurl, data,callback);
}

function OV_deleteItem(asin){
	
	OV_doUpdateCart(asin,0,function(response) {
		var id = '#cartItem_'+asin; 
		// TODO: add some type of error handling
		jQuery(id).fadeOut(1000,function(){		
			cartDiv.html(response);
		});
	});	
	return false;
}

function OV_checkout(){
	
	// first we close the cart. 
	closeCart();
	
	var div = jQuery('<div id="pleaseWaitDiv" style="display:hidden">We are redirecting you to Amazon to complete your order.</div>')
				.appendTo('body');
	
		div.dialog({
 	 			title 		  : 'Please Wait',
 	 			modal 		  : true,
 	 			resizable 	  : false,
 	 			closeOnEscape : false
			});
	
	
	var data = {
			action: 'ov_ajax_checkout'
			};
	// TODO: Check some type of response code.
	jQuery.getJSON(ajaxurl, data,function (response){
		if(response.status == 'OK'){
			if(debug){
				div.html(response.url);
			}else{
				window.location = response.url;
			}
		}else{
			div.html(response.error);
		}
	});
}
