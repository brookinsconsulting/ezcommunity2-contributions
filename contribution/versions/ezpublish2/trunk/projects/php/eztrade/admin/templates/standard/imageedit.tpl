<form method="post" action="{www_dir}{index}/trade/productedit/imageedit/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-imageupload}: {product_name}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN image_tpl -->
<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END image_tpl -->

<p class="boxtext">{intl-title}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>
	
<p class="boxtext">{intl-caption}:</p>
<input class="box" type="text" size="40" name="Caption" value="{caption_value}"/>
	
<p class="boxtext">{intl-photographer}:</p>
<select name="PhotoID">
<!-- BEGIN photographer_item_tpl -->
<option value="{photo_id}" {selected}>{photo_name}</option>
<!-- END photographer_item_tpl -->
</select>
<br /><br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-new_photographer_name}:</p>
	<input class="halfbox" type="text" name="NewPhotographerName" size="20" value="" />
	</td>
	<td>
	<p class="boxtext">{intl-new_photographer_email}:</p>
	<input class="halfbox" type="text" name="NewPhotographerEmail" size="20" value="" />
	</td>
</tr>
</table>

<p class="boxtext">{intl-file}:</p>
<input size="40" name="userfile" type="file" />

<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input type="hidden" name="ImageID" value="{image_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>

	<form method="post" action="{www_dir}{index}/trade/productedit/imagelist/{product_id}/">
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</form>

	</td>

</tr>
</table>



