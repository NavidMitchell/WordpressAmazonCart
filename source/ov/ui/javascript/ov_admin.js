var working = false; 
jQuery(document).ready(function($) {

	    $('#ov_import_btn').click(function() {
	    	if(!working){
	    		working == true;
		    	var category = $('#category').val();
		    	var searchIndex = $('#searchIndex').val();
		    	var keyword = $('#keyword').val();
		    	var max = $('#max').val();
		    	
		    	var data = {
		    			action: 'ov_ajax_import_products',
		    			category : category,
		    			searchIndex : searchIndex,
		    			keyword : keyword,
		    			max : max
		    			};
		    	
		    	// TODO: Check some type of response code.
		    	jQuery.post(ajaxurl, data, function(response) {
		    		working = false;
		    		
		    		if(response != 'OK'){
		    		    var div = $('<div id="div" style="display:hidden">'+response+'</div>').appendTo('body');
		    
		    		    var dialog = div.dialog({
		    	 	 			title	 : 'Error has occured',
		    	 	 			width 	 : 800,
		    	 	 			hide     : 'puff',
		    	 	 			modal	 : true
							});
		    		}
		    		
		    	});
	    	}
	        return false;
	    });
	    
	    $('#ov_create_page_btn').click(function() {
	    	if(!working){
	    		working == true;
		    	var page = $('#page').val();
		    	var searchIndex = $('#searchIndex').val();
		    	var keyword = $('#keyword').val();
		    	
		    	var data = {
		    			action: 'ov_ajax_create_page',
		    			page : page,
		    			searchIndex : searchIndex,
		    			keyword : keyword
		    			};
		    	
		    	// TODO: Check some type of response code.
		    	jQuery.post(ajaxurl, data, function(response) {
		    		working = false;
		    		
		    		if(response != 'OK'){
		    		    var div = $('<div style="display:hidden">'+response+'</div>').appendTo('body');
		    
		    		    var dialog = div.dialog({
		    	 	 			title	 : 'Error has occured',
		    	 	 			width 	 : 800,
		    	 	 			hide     : 'puff',
		    	 	 			modal	 : true
							});
		    		}else{
		    			var div = $('<div style="display:hidden">Page Added Successfully</div>').appendTo('body');
		    		    
		    		    var dialog = div.dialog({
		    	 	 			title	 : 'Done',
		    	 	 			hide     : 'puff',
		    	 	 			modal	 : true
							});
		    		}
		    		
		    	});
	    	}
	        return false;
	    });
	    
			
 });