<div class="wrap">
<h2>Amazon Store Import</h2>

<form id="ajaxForm" method="post">

<table class="form-table">

<tr valign="top">
	<th scope="row">Page Title</th>
	<td>
		<input type="text" id="page" />
		<span class="description">The title of the new page</span>
	</td>
</tr>
 
<tr valign="top">
	<th scope="row">Amazon Search Index</th>
	<td>
		<select id="searchIndex">
			{foreach from=$searchIndexes item=searchIndex}
				<option value="{$searchIndex}">{$searchIndex}</option>
			{/foreach}
		</select>
		<span class="description">The Amazon search index to use for the product search. (If you use All a maximum of 50 products will be listed)</span>
	</td>
</tr> 
 
<tr valign="top">
	<th scope="row">Amazon Keyword</th>
	<td>
		<input type="text" id="keyword" />
		<span class="description">Your Search Keywords ( To avoid errors please separate your keywords by commas )</span>
	</td>
</tr>


</table>

<p class="submit">
<input id="ov_create_page_btn" type="button" class="button-primary" value="Create Product Page" />
</p>

</form>
</div>