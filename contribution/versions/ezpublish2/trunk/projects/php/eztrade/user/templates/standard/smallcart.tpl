<!-- BEGIN empty_cart_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menuhead">{intl-cart}</td>
</tr>
<tr>
	<td class="menutext">{intl-empty_cart}</td>
</tr>
</table>
<!-- END empty_cart_tpl -->


<form action="/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="4" class="menuhead">{intl-cart}</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td colspan="4" class="{td_class}">
	<a class="menulink" href="/trade/productview/{product_id}/">{product_name}</a>
	<div class="small" align="right">{product_price}</div>
	</td>

</tr>
<!-- END cart_item_tpl -->
<tr>
	<td class="small" colspan="3">{intl-shipping}:</td>
	<td class="small" align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td class="small" colspan="3">{intl-total}:</td>
	<td class="small" align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<table border="0">
<tr>
	<!-- BEGIN cart_checkout_tpl -->
	<td>
	<input type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
</tr>
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
<tr>
	<td>
	<a class="menu" href="/trade/cart/">{intl-allcart}</a>
	</td>
	<!-- END cart_checkout_tpl -->
</tr>
</table>

<input type="hidden" name="Action" value="Refresh" />

</form>
