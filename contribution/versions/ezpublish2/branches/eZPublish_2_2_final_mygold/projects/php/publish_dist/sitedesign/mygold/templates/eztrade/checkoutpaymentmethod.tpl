<!-- BEGIN address_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/address/">{intl-address-path}</a>
<!-- END address_path_tpl -->

<!-- BEGIN address_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-address-path}
<!-- END address_dummy_path_tpl -->

<!-- BEGIN shipping_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/shipping/">{intl-shipping-path}</a>
<!-- END shipping_path_tpl -->

<!-- BEGIN shipping_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-shipping-path}
<!-- END shipping_dummy_path_tpl -->

<!-- BEGIN packing_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/packing/">{intl-packing-path}</a>
<!-- END packing_path_tpl -->

<!-- BEGIN packing_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-packing-path}
<!-- END packing_dummy_path_tpl -->

<!-- BEGIN payment_method_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/paymentmethod/">{intl-payment_method-path}</a>
<!-- END payment_method_path_tpl -->

<!-- BEGIN payment_method_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-payment_method-path}
<!-- END payment_method_dummy_path_tpl -->

<!-- BEGIN overview_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/overview/">{intl-overview-path}</a>
<!-- END overview_path_tpl -->

<!-- BEGIN overview_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-overview-path}
<!-- END overview_dummy_path_tpl -->

<!-- BEGIN payment_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/payment/">{intl-payment-path}</a>
<!-- END payment_path_tpl -->

<!-- BEGIN payment_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-payment-path}
<!-- END payment_dummy_path_tpl -->

<!-- BEGIN ordersent_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/checkout/ordersent/">{intl-ordersent-path}</a>
<!-- END ordersent_path_tpl -->

<!-- BEGIN ordersent_dummy_path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="0" />
{intl-ordersent-path}
<!-- END ordersent_dummy_path_tpl -->

<br /><br />
<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/checkout/paymentmethod/" method="post">
<!-- hemmlig -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- <tr> -->
<!--        <th>&nbsp;</th> -->
	<!-- BEGIN header_ex_tax_item_tpl -->
<!--	<th>{intl-header_ex_tax}:</th> -->
	<!-- END header_ex_tax_item_tpl -->

	<!-- BEGIN header_inc_tax_item_tpl -->
<!--	<th>{intl-header_inc_tax}:</th> -->
	<!-- END header_inc_tax_item_tpl -->
	<!-- BEGIN header_inc_tax_item_tpl -->
<!-- 	<th>{intl-delete_method}:</th> -->
	<!-- END header_inc_tax_item_tpl -->
<!-- </tr> -->
<tr>
    <td width="25%">Total pay:</td>
    <!-- BEGIN total_ex_tax_item_tpl -->
    <td><nobr>{total_ex_tax}</nobr></td>
    <!-- END total_ex_tax_item_tpl -->
    <!-- BEGIN total_inc_tax_item_tpl -->
    <td width="75%"><nobr>{total_inc_tax}</nobr></td>
    <!-- END total_inc_tax_item_tpl -->
</tr>
<!-- BEGIN voucher_item_tpl -->
<tr>
    <td width="25%">{intl-voucher_item} {number}:</td>
    <!-- BEGIN voucher_ex_tax_item_tpl -->
    <td width="75%"><nobr>{voucher_ex_tax}</nobr></td>
    <!-- END voucher_ex_tax_item_tpl -->
    <!-- BEGIN voucher_inc_tax_item_tpl -->
    <td width="75%"><nobr>{voucher_inc_tax}</nobr></td>
    <!-- END voucher_inc_tax_item_tpl -->
    <!-- BEGIN delete_voucher_tpl -->
    <td>
    <input type="checkbox" name="RemoveVoucherArray[]" value="{voucher_id}" />
    </td>
    <!-- END delete_voucher_tpl -->
</tr>
<!-- END voucher_item_tpl -->
<!-- BEGIN payment_item_tpl -->
<tr>
    <td width="25%">Payed with {payment_method_name}:</td>
    <!-- BEGIN payment_ex_tax_item_tpl -->
    <td><nobr>{payment_ex_tax}</nobr></td>
    <!-- END payment_ex_tax_item_tpl -->
    <!-- BEGIN payment_inc_tax_item_tpl -->
    <td width="75%"><nobr>{payment_inc_tax}</nobr></td>
    <!-- END payment_inc_tax_item_tpl -->
    <!-- BEGIN delete_payment_tpl -->
    <td>
    <input type="checkbox" name="RemovePayment" />
    </td>
    <!-- END delete_payment_tpl -->
</tr>
<!-- END payment_item_tpl -->
<!-- BEGIN rest_list_tpl -->
<tr>
    <td width="25%">{intl-rest}:</td>

    <!-- BEGIN rest_inc_tax_item_tpl -->
    <td width="75%"><nobr>{rest_price_inc_tax}</nobr></td>
    <!-- END rest_inc_tax_item_tpl -->

    <!-- BEGIN rest_ex_tax_item_tpl -->
    <td width="75%"><nobr>{rest_price_ex_tax}</nobr></td>
    <!-- END rest_ex_tax_item_tpl -->

</tr>
<!-- END rest_list_tpl -->
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <td>
    <!-- BEGIN show_payment_tpl -->
    <select name="PaymentMethod">
    <!-- BEGIN payment_method_tpl -->
    <option value="{payment_method_id}">{payment_method_text}</option>
    <!-- END payment_method_tpl -->
    </select>
    <!-- END show_payment_tpl -->
    &nbsp;<input type="submit" name="Choose" value="{intl-choose}" />
    </td>
    <td>
    <input type="text" name="KeyNumber" value="" />&nbsp;
    <input type="submit" name="UseVoucher" value="{intl-use_voucher}" />
    </td>
</tr>
</table>
<br />

{payment_method_file_tpl}

<br /><br />
<hr noshade="noshade" size="4" />

<!-- BEGIN next_tpl -->
<input type="submit" name="Next" value="{intl-next}" />
<input type="hidden" name="VerifySuccess" value="{veryfy_success}" />
<!-- END next_tpl -->

<!-- BEGIN delete_payments_tpl -->
&nbsp;<input class="stdbutton" type="submit" name="DeletePayments" value="{intl-remove_voucher}" />
<!-- END delete_payments_tpl -->
</form>
