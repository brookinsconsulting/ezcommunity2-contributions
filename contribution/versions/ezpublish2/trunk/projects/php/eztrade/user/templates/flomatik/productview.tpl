<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/{module}/{module_list}/0/">{intl-top}</a>

<!-- BEGIN path_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/{module}/{module_list}/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade size="4"/>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h2>{title_text}</h2>
	</td>
	<td align="right">
	<br />
	<!-- BEGIN product_number_item_tpl -->
	<span class="boxtext">{intl-nr}:</span> {product_number}
	<!-- END product_number_item_tpl -->
	</td>
</tr>
<tr>
	<td colspan="2">

<br />
<!-- BEGIN main_image_tpl -->

<table align="right" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a href="/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/">
	<img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" /></a>
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

<br clear="all" />

<!-- BEGIN image_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="7">
<tr>
<!-- BEGIN image_tpl -->
<td class="bglight">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<a href="/imagecatalogue/imageview/{image_id}/?RefererURL=/{module}/{module_view}/{product_id}/">
	<img src="{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/></a>
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
<br />
<!-- END image_list_tpl -->

<!-- BEGIN attribute_list_tpl -->
<table width="70%" cellspacing="0" cellpadding="2" border="0" align="center">
<!-- BEGIN attribute_tpl -->
<tr>
	<th>
	{attribute_name}: 
	</td>
	<td align="right">
	{attribute_value}
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<form action="/{module}/cart/add/{product_id}/" method="post">

<!-- BEGIN option_tpl -->


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	<br />
	{option_name}:
	</th>
</tr>
<tr>
	<td>

<table width="100%" cellpadding="2" cellspacing="0" border="0">
	<!-- BEGIN value_price_header_tpl -->
	<tr>
		<th>
		Art. Nr:
		</th>
		<th>
		Price:
		</th>
		<th colspan="{currency_count}"> 
		Alternative currency:
		</th>
	</tr>

	<!-- END value_price_header_tpl -->
	<tr>
	<!-- BEGIN value_tpl -->
	<td class="{value_td_class}">
	{value_name}&nbsp;&nbsp;
	</td>
	<!-- BEGIN value_price_item_tpl -->
	<td class="{value_td_class}">
	{value_price}
	</td>
	<!-- END value_price_item_tpl -->

	<!-- BEGIN value_price_currency_list_tpl -->

	<!-- BEGIN value_price_currency_item_tpl -->
	<td class="{value_td_class}">
	{alt_value_price}
	</td>
	<!-- END value_price_currency_item_tpl -->

	<!-- END value_price_currency_list_tpl -->

	</tr>
	<!-- END value_tpl -->
</table>

	</td>
</tr>
<tr>
	<td>
	{option_description}
	</td>
</tr>
</table>

<!-- END option_tpl -->

	</td>
</tr>
</table>
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<!-- BEGIN price_tpl -->
	<p class="boxtext">{intl-price}:</p>
	{product_price}

	<!-- BEGIN alternative_currency_list_tpl -->
	<p class="boxtext">{intl-alternative_currency}:</p>
	<!-- BEGIN alternative_currency_tpl -->
	{alt_price}<br />
	<!-- END alternative_currency_tpl -->

	<!-- END alternative_currency_list_tpl -->

	<!-- END price_tpl -->	
	</td>
	<td align="right" valign="top">
	<!-- BEGIN external_link_tpl -->
	<p class="boxtext">{intl-external_link}:</p>
	<a href="{external_link_url}" target="_blank">{external_link_url}</a>
	<!-- END external_link_tpl -->
	</td>
</tr>
</table>
<br />

{extra_product_info}
<br />

<!-- BEGIN add_to_cart_tpl -->
<!--

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />

<input class="okbutton" type="submit" name="WishList" value="{intl-wishlist}" />
-->
<!-- END add_to_cart_tpl -->

<br /><br />

<!-- BEGIN numbered_page_link_tpl -->
<div align="center"><a class="path" href="/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
<div align="center"> <a class="path" href="/{module}/{module_print}/{product_id}/{category_id}/">| {intl-print_page} |</a></div>
<!-- END print_page_link_tpl -->

</form>
