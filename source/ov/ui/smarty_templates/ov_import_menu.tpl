<div class="wrap">
<h2>Amazon Store Import</h2>

<form >

<table class="form-table">

<tr valign="top">
	<th scope="row">Category</th>
	<td>
		<select id="category">
			{foreach from=$categories item=category}
				<option value="{$category->cat_ID}">{$category->cat_name}</option>
			{/foreach}	
		</select>
		<span class="description">The Wordpress category to import products into.</span>
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
		<span class="description">The Amazon search index to use for the product search. (If you use All a maximum of 50 products will be imported)</span>
	</td>
</tr> 
 
<tr valign="top">
	<th scope="row">Amazon Keyword</th>
	<td>
		<input type="text" id="keyword" />
		<span class="description">Your Search Keywords ( To avoid errors please separate your keywords by commas )</span>
	</td>
</tr>


<tr valign="top">
	<th scope="row">Maximum Products</th>
	<td>
		<input type="text" id="max" value="200"/>
		<span class="description">This is the maximum number of products you would like to import.</span>
	</td>
</tr>
</table>

<p class="submit">
<input id="ov_import_btn" type="button" class="button-primary" value="Import Products" />
</p>

</form>
</div>