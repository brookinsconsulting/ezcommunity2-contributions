
<!-- cart.tpl --> 



<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->


<form action="/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<td class="menuhead" bgcolor="#323296">{intl-cart}</td>
</tr>
<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>

</tr>
<!-- END cart_item_tpl -->
<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-shipping}:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-total}:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<hr noshade="noshade" size="4" />
<table border="0">
<tr>
	<!-- BEGIN cart_checkout_tpl -->
	<td>
	<input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
	<!-- END cart_checkout_tpl -->
</td>
</table>

<input type="hidden" name="Action" value="Refresh" />

</form>
