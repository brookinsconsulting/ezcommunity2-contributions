<h1>{intl-cart}</h1>

<hr noshade="noshade" size="1" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->


<form action="{www_dir}{index}/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr align="left">
	<th width="1%">&nbsp;{intl-product_image}:</th>
	<th>{intl-product_name}:</th>
	<th width="1%">{intl-product_options}:</th>
	<!-- BEGIN product_available_header_tpl -->
	<th width="1%">&nbsp;</th>
	<!-- END product_available_header_tpl -->
	<th align="right" width="10%">{intl-product_price}:</th>
	<th width="1%">&nbsp;</th>
    </tr>
    <!-- BEGIN cart_item_tpl -->
    <tr align="left">
	<td class="{td_class}">
	    <!-- BEGIN cart_image_tpl -->
	    &nbsp;<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	    <!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	    <a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}">
    	    <!-- BEGIN cart_item_option_tpl -->
	    {option_value}
	    <!-- BEGIN cart_item_option_availability_tpl -->
	    &nbsp;
	    <!-- END cart_item_option_availability_tpl -->
    	    <!-- END cart_item_option_tpl -->
	    &nbsp;
	<td class="{td_class}">
	    <!-- BEGIN product_available_item_tpl -->
	    &nbsp;
	    <!-- END product_available_item_tpl -->
	</td>
	<td class="{td_class}" align="right">
	    {product_price}
	</td>
	<td class="{td_class}" align="right">
	    <a href="{www_dir}{index}/trade/cart/remove/{cart_item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztrade{cart_item_id}-slett','','/images/slettminimrk.gif',1)"><img name="eztrade{cart_item_id}-slett" border="0" src="{www_dir}/images/slettmini.gif" width="16" height="16" align="top"></a>&nbsp;
	</td>
    </tr>
    <!-- END cart_item_tpl -->
    <tr>
	<td colspan="2">&nbsp;</td>
	<td align="right">{intl-shipping}:</td>
	<td rowspan="3">&nbsp;</td>
	<td align="right">{shipping_sum}</td>
	<td rowspan="3">&nbsp;</td>
    </tr>
    <tr>
	<td colspan="2">&nbsp;</td>
	<td align="right">{intl-vat}:</td>
	<td align="right">{cart_vat_sum}</td>
    </tr>
    <tr>
	<td colspan="2">&nbsp;</td>
	<td align="right">{intl-total}:</td>
	<td align="right">{cart_sum}</td>
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
