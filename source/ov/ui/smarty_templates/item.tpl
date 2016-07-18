<div class="ov-product-container">
	<div class="ov-product-image-container">
		<a href="{$item->getLargeImage()}" class="ov-product-image-link" rel="{$item->getASIN()}" title="{$item->getTitle()}">
			<img class="ov-product-image" src="{$item->getMediumImage()}" alt="{$item->getTitle()}">
		</a>
		<div class="ov-product-image-list">
			{if $item->getImageSetsCount() >1}
				{foreach  from=$item->getImageSets() item=image}
					<!--*** rel attribute is used to group images for fancybox ***-->
					<a href="{$image[2]}" class="ov-product-image-link" rel="{$item->getASIN()}" title="{$item->getTitle()}" >
					   <img class="ov-product-image-small" src="{$image[0]}" border="0">
					</a>   
				{/foreach}
			{/if}
		</div>
	</div>

	<div class="ov-product-information-container">
		<span class="ov-product-price-label">Price:</span>&nbsp;<span class="ov-product-price">{$item->getFormatedPrice()}</span>
		&nbsp; 
		<input id="ov_view_cart_btn" class='ov-float-right-button ui-state-default ui-corner-all' type='button' onclick="OV_viewCart();" value='View Cart'/> 
		&nbsp;
		<input class="ov-float-right-button ui-state-default ui-corner-all" type='button' onclick="OV_addCart('{$item->getASIN()}',1);" value="Add to Cart">
		{if $item->getDescription() != ""} 
			<br></br>
			<hr class="ov-product-information-divider">
			<h2 class="ov-product-information-label ui-state-default">Product Description</h2>
			<div class="ov-product-description ov-product-information">						
				{$item->getDescription()}
			</div>
		{/if}
		{if $item->getFeatureCount() > 0}
			<br></br>
			<hr class="ov-product-information-divider">
			<h2 class="ov-product-information-label ui-state-default">Product Features</h2>
			<div class="ov-product-features ov-product-information">						
				<ul>									
					{foreach from=$item->getFeatures() item=feature}
						<li>{$feature}</li>
					{/foreach}
				</ul>
			</div>
		{/if}
		{*	Put Back if you want item attributes.
		{if $item->getItemAttributes() != null}
			<br></br>
			<hr class="ov-product-information-divider">	
			<h2 class="ov-product-information-label">Product Details</h2>
			<div class="ov-product-attributes ov-product-information">
				{foreach from=$item->getItemAttributes() key=k item=v}
					<b>{$k}: </b>{$v}
					<br/>
				{/foreach}
			</div>
		{/if}
		*}
		{if $item->getEditorialReviewCount() > 0}
			<br>
			<hr class="ov-product-information-divider">	
			<h2 class="ov-product-information-label">Editorial Review</h2>
			<div class="ov-product-editorial-review ov-product-information">									
				{$item->getFirstEditorialReview()}
			</div>
		{/if}
	</div>
	
</div>
