<!-- cart.tpl --> 
<!-- $Id: cart.tpl,v 1.1 2000/10/21 17:00:56 bf-cvs Exp $ -->

<h1>{intl-cart}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->



<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Bilde:</th>
	<th>Varenavn:</th>
	<th>Opsjoner:</th>
	<td class="path" align="right">Pris:</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	</td>
	<td class="{td_class}">
	{product_name}
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
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="2">&nbsp;</td>
	<th>Frakt:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<th>Totalt:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<!-- BEGIN cart_checkout_tpl -->
<form action="/trade/customerlogin/" method="post">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="Gå til kasse" />
</form>
<!-- END cart_checkout_tpl -->