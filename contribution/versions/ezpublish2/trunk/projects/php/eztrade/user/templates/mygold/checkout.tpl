<form action="/trade/checkout/" method="post">

<h1>{intl-confirm_order}</h1>

<hr noshade="noshade" size="1" />

<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
    <tr align="left">
	<th>{intl-picture}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-options}:</th>
	<!-- BEGIN product_available_header_tpl -->
	<th>&nbsp;</th>
	<!-- END product_available_header_tpl -->
	<th align="right">{intl-price}</th>
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
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	<!-- BEGIN cart_item_option_availability_tpl -->&nbsp;
	<!-- END cart_item_option_availability_tpl -->
        <!-- END cart_item_option_tpl -->&nbsp;
	</td>
	<!-- BEGIN product_available_item_tpl -->
	<td class="{td_class}">
	&nbsp;
	<!-- BEGIN product_available_item_tpl -->
	</td>
	<!-- END product_available_item_tpl -->
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="2" rowspan="3" valign="bottom">

	<!-- BEGIN shipping_type_tpl -->
	<input type="hidden" name="ShippingTypeID" value="{shipping_type_id}" />
	<!-- END shipping_type_tpl -->
	</td>
	<td align="right" colspan="2">
	{intl-shipping_charges}:
	</td>

	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="2" align="right">{intl-vat}:</td>
	<td align="right">
	{cart_vat_sum}
	</td>
</tr>
<tr>
	<td colspan="2" align="right">{intl-total_cost_is}:</td>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<!-- BEGIN billing_address_tpl -->
<p><b>{intl-billing_to}:</b></p>
<select name="BillingAddressID">
<!-- BEGIN billing_option_tpl -->
<option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {zip} {place}</option>
<!-- END billing_option_tpl -->
</select>
<!-- END billing_address_tpl -->

<p><b>{intl-shipping_to}:</b></p>
<select name="ShippingAddressID">
<!-- BEGIN shipping_address_tpl -->
<option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {zip} {place}</option>
<!-- END shipping_address_tpl -->
<!-- BEGIN wish_user_tpl -->
<option value="{wish_user_address_id}">{wish_first_name} {wish_last_name}</option>
<!-- END wish_user_tpl -->
</select>

<br /><br />
<hr noshade="noshade" size="1" />
<br />

{intl-payment_methods_description}:&nbsp;

<select name="PaymentMethod">
<!-- BEGIN payment_method_tpl -->
<option value="{payment_method_id}">{payment_method_text}</option>
<!-- END payment_method_tpl -->
</select>

<a href="http://www.campaign.paybox.de/banner.php3?merchantPayboxNo=4900011161914"><img src="/images/paybox_logo.gif" />paybox - bezahlen Sie mit Ihrem Handy</a>

<br /><br />

<hr noshade="noshade" size="1" />


<input type="hidden" name="ShippingCost" value="{shipping_cost_value}" />
<input type="hidden" name="ShippingVAT" value="{shipping_vat_value}" />
<input type="hidden" name="TotalCost" value="{total_cost_value}" />

<!-- BEGIN sendorder_item_tpl -->
<input class="okbutton" type="submit" name="SendOrder" value="{intl-send}" />
<!-- END sendorder_item_tpl -->

</form>



