<table class="middleboxnotop" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td align="left" valign="top">
				<table cellpadding="4" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td colspan="3" style="background:#eeeeee;">
								<b>Items</b>
							</td>
							<td style="background:#eeeeee;width:100px;">
								<b>Sub Total</b>
							</td>
						</tr>
						<!-- BEGIN: items -->
						{if $cart->getCartItemsCount() > 0 } 
						{foreach from=$cart->getCartItems() item=data}
						<tr id="cartItem_{$data->ASIN}">
							<td style="border-right:1px solid black;">
								<a onClick="OV_deleteItem('{$data->ASIN}');"><img class="ov-small-button" alt="Trash" src="{$IMAGE_URL}/trash.png" /></a>
							</td>
							<td align="center" valign="top" width="90px">
								<img src="{$cart->getImageUrlForItem($data->ASIN)}"
									 onerror='src="{$IMAGE_URL}/blankimg.gif";' border="0"/>
							</td>
							<td valign="top" style="border-right:1px solid black;">
								{$data->Title}
								<table cellspacing="5px" width="100%">
									<tr>
										<td width="140px">
											<small><b>Price: <span class="ov-product-price">{$data->Price->FormattedPrice}</span>
											</b>
											</small>
										</td>
										<td>
											<form id="updForm_{$data->ASIN}" >
												<input type="hidden" id="asin" value="{$data->ASIN}">
												<small>
													<b>QTY: </b>
													<input id="qty" 
														   type="text"
													       style="width:25px;"
														   value="{$data->Quantity}"> 
														   
													<input id="ov_update_cart_btn" 
														   type="button" 
														   class="ui-state-default ui-corner-all ov-small-button" 
														   value="Update Cart"
														   onClick="OV_updateCart('updForm_{$data->ASIN}');"> 
												</small>
											</form>
										</td>
									</tr>
								</table>
							</td>
							<td width="50px" align="center">
								<small><span class="ov-product-price">{$data->ItemTotal->FormattedPrice}</span>
								</small>
							</td>
						</tr>
						{/foreach}
						<!-- END: items -->
						{else}
						<tr>
							<td colspan='4'>
								You have nothing in your shopping cart.
							</td>
						</tr>
						{/if}
						<tr style="background:#eeeeee; text-align:right;" >
							<td colspan="4">
								<b>Total:&nbsp;&nbsp;</b> <span id="totalDisp" class="ov-product-price">{$cart->getCartTotal()}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<br>
<div style="width:100%;text-align:center;">
<input class='ov-button ui-state-default ui-corner-all' type='button' value="Checkout" onClick="OV_checkout();"/>
</div>
