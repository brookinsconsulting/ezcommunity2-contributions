<form action="/trade/productedit/edit/{product_id}/" method="post">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h2>{title_text}</h2>
	</td>
	<td align="right">
	<br />
	<span class="boxtext">{intl-nr}:</span> {product_number}
	</td>
</tr>
<tr>
	<td colspan="2">

<br />

<!-- BEGIN main_image_tpl -->	
<table width="1%" align="right" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" />
	</td>
</tr>
<tr>
	<td class="pictext">
	{main_image_caption}
	<br /><br />
	</td>
</tr>
</table>
<!-- END main_image_tpl -->

<p>{intro_text}</p>

<p>{description_text}</p>

<br clear="all" />
<table width="100%" cellspacing="0" cellpadding="7">
<!-- BEGIN image_tpl -->
<tr>
<td class="bglight">
<table cellspacing="0" cellpadding="2" border="0" border="0">
<tr>
	<td valign="top">
	<img src="{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}" />
	</td>
</tr>
<tr>
	<td valign="top">
	<p class="pictext">
	{image_caption}
	</p>
	</td>
</tr>
</table>

</td>
</tr>
<!-- END image_tpl -->

</table>
<br />

<!-- BEGIN option_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	{option_name}:
	</th>
</tr>
<tr>
	<td width="20%">
	<select name="Options">
	<!-- BEGIN value_tpl -->
	<option value="{value_id}">{value_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>

	<td width="80%">
	{option_description}
	</td>
</tr>
</table>

<br />
<!-- END option_tpl -->

	</td>
</tr>
</table>

<!-- BEGIN attribute_list_tpl -->
<table width="60%" cellspacing="0" cellpadding="2" border="0" align="center">

<!-- BEGIN attribute_tpl -->
<tr>
	<th>
	{attribute_name}: 
	</th>
	<td align="right">
	{attribute_value}
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<br />

<div class="boxtext">{intl-price}:</div> 
<div class="p">{product_price}</div>
<br />

<div class="boxtext">{intl-price_ex_vat}:</div> 
<div class="p">{product_price_ex_vat}</div>

<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-edit}" />
</form>

