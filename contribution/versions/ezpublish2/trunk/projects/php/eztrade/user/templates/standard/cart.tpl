<!-- cart.tpl --> 
<!-- $Id: cart.tpl,v 1.4 2000/11/01 17:26:25 pkej-cvs Exp $ -->

<h1>{intl-cart}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->



<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-product_image}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-product_options}:</th>
	<td class="path" align="right">{intl-product_price}:</td>
	<td class="path" align="right">{intl-product_remove}:</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	</td>
	<td class="{td_class}">
	<a href="/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	{option_name}:
	{option_value}<br>
        <!-- END cart_item_option_tpl -->
	&nbsp;</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<td class="{td_class}">
	<a href="/trade/cart/remove/{cart_item_id}/">remove</a>
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="2">&nbsp;</td>
	<th>{intl-shipping}:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<th>{intl-total}:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<!-- BEGIN cart_checkout_tpl -->
<form action="/trade/customerlogin/" method="post">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-checkout}" />
</form>
<!-- END cart_checkout_tpl -->
