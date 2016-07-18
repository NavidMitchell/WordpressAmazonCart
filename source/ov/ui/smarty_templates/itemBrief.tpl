<div class="ov-item-list" >
 <div class="ov-wrapper">
	 <div class="ov-left">
	 <a id="product_detail_link" href="{$uri}{$item->getASIN()}" title="{$item->getTitle()}" >
		 <img class="ov-product-image-small" src="{$item->getSmallImage()}" onerror='src="{$IMAGE_URL}blankimg.gif";' border="0" alt="{$item->getTitle()}">
	 </a>
	 </div>
	 <div class="ov-middle">
		<a href="{$uri}{$item->getASIN()}">{$item->getTitle()}</a>
	 	<p class="result-description">
	 		{$item->getTeaser()}
	 	</p>
	 </div>
	 <div class="ov-left">
		 <div class="ov-product-price-brief">{$item->getFormatedPrice()}</div>
	 </div>
 </div>
 </div>
 