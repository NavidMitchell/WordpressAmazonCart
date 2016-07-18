<div class="wrap">
<h2>Amazon Store Options</h2>

<form method="post" action="options.php">
<input type='hidden' name='option_page' value='ov-aws' />
<input type="hidden" name="action" value="update" />
{$nonce}

<table class="form-table">

<tr valign="top">
<th scope="row">Amazon Access Key</th>
<td><input type="text" name="access_key" value="{$access_key}" /></td>
</tr>
 
<tr valign="top">
<th scope="row">Amazon Associate Tag</th>
<td><input type="text" name="associate_tag" value="{$associate_tag}" /></td>
</tr>

<tr valign="top">
<th scope="row">Amazon Secret Key</th>
<td><input type="password" name="secret_key" value="{$secret_key}" /></td>
</tr>

</table>

<p class="submit">
<input type="submit" class="button-primary" value="Save Changes" />
</p>

</form>
</div>