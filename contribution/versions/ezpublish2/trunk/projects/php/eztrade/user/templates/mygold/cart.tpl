<h1>{intl-cart}</h1>

<hr noshade="noshade" size="1" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->


<form action="/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr align="left">
	<th>{intl-product_image}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-product_options}:</th>
	<!-- BEGIN product_available_header_tpl -->
	<!-- END product_available_header_tpl -->
	<th align="right">{intl-product_price}:</th>
	<th>&nbsp;</th>
    </tr>
    <!-- BEGIN cart_item_tpl -->
    <tr align="left">
	<td class="{td_class}">
	    <!-- BEGIN cart_image_tpl -->
	    &nbsp;<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	    <!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	    <a href="/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}">
    	    <!-- BEGIN cart_item_option_tpl -->
	    {option_value}
	    <!-- BEGIN cart_item_option_availability_tpl -->
	    ({option_availability})
	    <!-- END cart_item_option_availability_tpl -->
    	    <!-- END cart_item_option_tpl -->
	    &nbsp;
	</td>
	<!-- BEGIN product_available_item_tpl -->
	<!-- END product_available_item_tpl -->
	<td class="{td_class}" align="right">
	    {product_price}
	</td>
	<td class="{td_class}" align="right">
	    <a href="/trade/cart/remove/{cart_item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztrade{cart_item_id}-slett','','/images/slettminimrk.gif',1)"><img name="eztrade{cart_item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>&nbsp;
	</td>
    </tr>
    <!-- END cart_item_tpl -->
    <tr>
	<td colspan="2">&nbsp;</td>
	<td>{intl-shipping}:</td>
	<td align="right">{shipping_sum}</td>
	<td>&nbsp;</td>
    </tr>
    <tr>
	<td colspan="2">&nbsp;</td>
	<td>{intl-vat}:</td>
	<td align="right">{cart_vat_sum}</td>
	<td>&nbsp;</td>
    </tr>
    <tr>
	<td colspan="2">&nbsp;</td>
	<td>{intl-total}:</td>
	<td align="right">{cart_sum}</td>
	<td>&nbsp;</td>
    </tr>
</table>
<!-- END cart_item_list_tpl -->

<hr noshade="noshade" size="1" />
<!-- BEGIN cart_checkout_tpl -->
<!-- BEGIN cart_checkout_button_tpl -->
    <input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
<!-- END cart_checkout_button_tpl -->
<!-- END cart_checkout_tpl -->
<input type="hidden" name="Action" value="Refresh" />
</form>
