<form action="{www_dir}{index}/trade/checkout/" method="post">

<h1>{intl-confirm_order}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>&nbsp;</th>
	<th>{intl-product_name}:</th>
	<th>{intl-options}:</th>
	<!-- BEGIN product_available_header_tpl -->
	<th>{intl-product_availability}:</th>
	<!-- END product_available_header_tpl -->
	<th>{intl-qty}:</th>
	<th class="right">&nbsp;&nbsp;{intl-price}:</th>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN cart_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	{product_name}
	</td>
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	<span class="small">{option_name}: {option_value}<!-- BEGIN cart_item_option_availability_tpl -->({option_availability})
<!-- END cart_item_option_availability_tpl --></span><br />
        <!-- END cart_item_option_tpl -->
	&nbsp;
	</td>
	<!-- BEGIN product_available_item_tpl -->
	<td class="{td_class}">
	{product_availability}
	<!-- BEGIN product_available_item_tpl -->
	</td>
	<!-- END product_available_item_tpl -->
	<td class="{td_class}">
	{cart_item_count}
	</td>
	<td class="{td_class}" align="right">
	<nobr>{product_price}</nobr>
	</td>
</tr>
<!-- BEGIN voucher_information_tpl -->
<tr>
        <td colspan="4" width="1%" class="{td_class}" >
	<p>{intl-send_email}</p>
	<input type="radio" name="MailType-{product_id}" value="1" checked />
        </td>
        <td colspan="2" width="99%" class="{td_class}" >
	<p>{intl-send_smail}</p>
	<input type="radio" name="MailType-{product_id}" value="2" />
        </td>
	<input type="hidden" name="VoucherIDArray[]" value="{product_id}" />
</tr>
<!-- END voucher_information_tpl -->
<!-- END cart_item_tpl -->
<tr>
	<td colspan="3" rowspan="3" valign="top">
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
	<nobr>{shipping_cost}</nobr>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="1" align="right"><span class="boxtext">{intl-vat}:</span></td>
	<td align="right">
	<nobr>{cart_vat_sum}</nobr>
	</td>
</tr>
<!-- BEGIN vouchers_tpl --> 
<tr>
        <!-- BEGIN voucher_item_tpl -->
	<td>&nbsp;</td>
	<td colspan="1" align="right"><span class="boxtext">{intl-voucher} {number}:</span></td>
	<td align="right">
	<nobr>- {voucher_price}</nobr>
	</td>
	<td>
	<input type="checkbox" name="RemoveVoucherArray[]" value="{number}" />
	</td>

        <!-- END voucher_item_tpl -->
</tr>
<!-- END vouchers_tpl --> 
<tr>
	<td colspan="{cart_colspan}" align="right"><span class="boxtext">{intl-total_cost_is}:</span></td>
	<td align="right"><nobr>{cart_sum}</nobr></td>
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
<p class="boxtext">{intl-comment}:</p>
<textarea class="box" name="Comment" cols="40" rows="5"></textarea>


<!-- BEGIN show_payment_tpl -->
<br /><br />

<span class="p">{intl-payment_methods_description}:</span>

<select name="PaymentMethod">
<!-- BEGIN payment_method_tpl -->
<option value="{payment_method_id}">{payment_method_text}</option>
<!-- END payment_method_tpl -->
</select>
<!-- END show_payment_tpl -->

<br /><br />


<!-- BEGIN remove_voucher_tpl -->
<input class="stdbutton" type="submit" name="RemoveVoucher" value="{intl-remove_voucher}" />
<!-- END remove_voucher_tpl -->

<hr noshade="noshade" size="4" />


<input type="hidden" name="ShippingCost" value="{shipping_cost_value}" />
<input type="hidden" name="ShippingVAT" value="{shipping_vat_value}" />
<input type="hidden" name="TotalCost" value="{total_cost_value}" />
<input type="hidden" name="TotalVAT" value="{total_vat_value}" />

<!-- BEGIN sendorder_item_tpl -->
<input class="okbutton" type="submit" name="SendOrder" value="{intl-send}" />
<!-- END sendorder_item_tpl -->

</form>



