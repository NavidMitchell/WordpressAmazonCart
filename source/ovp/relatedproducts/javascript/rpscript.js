YUI({
    combine: false, 
    debug: true, 
    filter:"RAW"
}).use('gallery-shoveler', function(Y) {

	shoveler = new Y.Shoveler( {
        contentBox: "#shoveler",
		numberOfVisibleCells: 4,
		cyclical: true,
		numberOfCells: 20,
		dynamic: true,
		renderFunctionName: "renderCellsWithPop",
		prefetch: true,
		contructDataSrc: function(start, numberOfVisibleCells) {
			var url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20flickr.photos.search("+start+"%2C"+numberOfVisibleCells+")%20where%20user_id%20%3D%20%2217004938%40N00%22&format=json&callback=shoveler.handleDataRetrieval";
			return url;
		},
 
		handleData: function(data) {
			var photos = data.query.results.photo, imageUrl, photo, i, len;
			for(i = 0, len = photos.length; i < len; i++) {
				photo = photos[i];
 
				imageUrl = "http://farm"+photo.farm+".static.flickr.com/"+photo.server+"/"+photo.id+"_"+photo.secret+"_t.jpg";

				this.replaceCell("<img src='"+imageUrl+"'/>"+photo.title, this.get("fetchStart")+i);
 
			}
		}
	});
	
	shoveler.render();
	
});	