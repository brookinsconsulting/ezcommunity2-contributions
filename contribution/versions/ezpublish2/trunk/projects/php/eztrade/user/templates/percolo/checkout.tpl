 <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%"><img src="/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top"><img src="/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><img src="/sitedesign/percolo/images/helikopter.gif" alt="helikopter" width="140" height="100" /><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">{intl-confirm_order}</h1></td>
        </tr>
        <tr>
            <td><img src="/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="/sitedesign/percolo/images/onepix.gif" alt="luft" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		<tr>
		    <td><img src="/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td colspan="2">

<form action="/trade/checkout/" method="post">

<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-picture}:</th>
	<th>{intl-product_name}:</th>
	<!-- BEGIN product_available_header_tpl -->

	<!-- END product_available_header_tpl -->
	<th>{intl-qty}:</th>
	<td align="right">&nbsp;&nbsp;<b>{intl-price}:</b></td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN cart_image_tpl -->
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	{product_name}
	</td>
        <!-- BEGIN cart_item_option_tpl -->

	<!-- BEGIN cart_item_option_availability_tpl -->

<!-- END cart_item_option_availability_tpl -->

        <!-- END cart_item_option_tpl -->

	<!-- BEGIN product_available_item_tpl -->

	<!-- BEGIN product_available_item_tpl -->

	<!-- END product_available_item_tpl -->
	<td class="{td_class}">
	{cart_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="1" rowspan="3" valign="top">
	<div class="boxtext">{intl-shipping_method}:</div>
	<select name="ShippingTypeID">
	<!-- BEGIN shipping_type_tpl -->
	<option value="{shipping_type_id}" {type_selected}>{shipping_type_name}</option>
	<!-- END shipping_type_tpl -->
	</select>
	<input class="stdbutton" type="submit" name="Recalculate" value="{intl-recalculate}" />
	</td>
	<td align="right" colspan="2">
	<span class="boxtext">{intl-shipping_charges}:</span>
	</td>

	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="1" align="right"><span class="boxtext">{intl-vat}:</span></td>
	<td align="right">
	{cart_vat_sum}
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="1" align="right"><span class="boxtext">{intl-total_cost_is}:</span></td>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<!-- BEGIN billing_address_tpl -->
<p class="boxtext">{intl-billing_to}:</p>
<select name="BillingAddressID">
<!-- BEGIN billing_option_tpl -->
<option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {street2}, {zip} {place} {country}</option>
<!-- END billing_option_tpl -->
</select>
<!-- END billing_address_tpl -->

<p class="boxtext">{intl-shipping_to}:</p>
<select name="ShippingAddressID">
<!-- BEGIN shipping_address_tpl -->
<option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {street2}, {zip} {place} {country}</option>
<!-- END shipping_address_tpl -->
<!-- BEGIN wish_user_tpl -->
<option value="{wish_user_address_id}">{wish_first_name} {wish_last_name}</option>
<!-- END wish_user_tpl -->
</select>

<br /><br />
<br />

{intl-payment_methods_description}:

<select name="PaymentMethod">
<!-- BEGIN payment_method_tpl -->
<option value="{payment_method_id}">{payment_method_text}</option>
<!-- END payment_method_tpl -->
</select>

<br /><br />

<input type="hidden" name="ShippingCost" value="{shipping_cost_value}" />
<input type="hidden" name="ShippingVAT" value="{shipping_vat_value}" />
<input type="hidden" name="TotalCost" value="{total_cost_value}" />

<!-- BEGIN sendorder_item_tpl -->
<input class="okbutton" type="submit" name="SendOrder" value="{intl-send}" />
<!-- END sendorder_item_tpl -->

</form>


</td>
</tr>
</table>
