<h1>Produktvisning</h1>

<hr noshade size="4"/>
<img src="/ezforum/images/path-arrow.gif" height="10" width="15" border="0">
<a class="path" href="/trade/productlist/0/">Hovedkategori</a>

<!-- BEGIN path_tpl -->
<img src="/ezforum/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/trade/productlist/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade size="4"/>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h2>{title_text}</h2>
	</td>
	<td align="right">
	<br />
	<span class="boxtext">Best. Nr:</span> [Bestillingsnummer]
	</td>
</tr>
<tr>
	<td colspan="2">

<br />
<!-- BEGIN main_image_tpl -->

<table align="right" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" />
	</td>
</tr>
<tr>
	<td class="pictext">
	{main_image_caption}
	</td>
</tr>
</table>

<!-- END main_image_tpl -->

<p>{intro_text}</p>

<p>{description_text}</p>

<table width="100%" cellspacing="0" cellpadding="7">
<tr>

<!-- BEGIN image_tpl -->
<td class="bglight">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<img src="{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/>
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
&nbsp;

</td>

<!-- END image_tpl -->

</tr>
</table>

<form action="/trade/cart/add/{product_id}/" method="post">

<!-- BEGIN option_tpl -->


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	<br />
	{option_name}
	</th>
</tr>
<tr>
	<td width="20%">

	<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
	<select name="OptionValueArray[]">
	
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

<!-- END option_tpl -->

	</td>
</tr>
</table>

<p class="boxtext">Pris:</p>
[PRIS!]

<br /><br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Cart" value="kjøp" />
<input class="okbutton" type="submit" name="WishList" value="Ønskeliste" />
</form>
