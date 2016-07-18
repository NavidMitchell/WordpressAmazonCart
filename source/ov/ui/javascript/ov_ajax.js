jQuery(document).ready(function($) {
	
	//TODO: Add our own loading icon. 
	$(document)
	.bind("ajaxStart", function(){
		 $.fancybox.showActivity();
	 })
	.bind("ajaxComplete", function(){
		 $.fancybox.hideActivity();
	});
	

 
});
