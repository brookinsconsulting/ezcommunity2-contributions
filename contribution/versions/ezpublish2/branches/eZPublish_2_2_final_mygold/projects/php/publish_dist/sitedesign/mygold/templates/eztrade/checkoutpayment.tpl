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

<form action="{www_dir}{index}/trade/checkout/payment/" method="post">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th>&nbsp;</th>
	<!-- BEGIN header_ex_item_tpl -->
	<th>{intl-voucher_header_ex_tax}:</th>
	<!-- END header_ex_item_tpl -->

	<!-- BEGIN header_inc_item_tpl -->
	<th>{intl-voucher_header_inc_tax}:</th>
	<!-- END header_inc_item_tpl -->
	<!-- BEGIN header_inc_item_tpl -->
	<th>{intl-delete_voucher}:</th>
	<!-- END header_inc_item_tpl -->
</tr>
<!-- BEGIN voucher_item_tpl -->
<tr>
     <td>
     <b>{intl-voucher_item} {number}:</b>
     </td>
	<!-- BEGIN voucher_ex_item_tpl -->
    <td><nobr>{voucher_ex_tax}</nobr></td>
	<!-- END voucher_ex_item_tpl -->

	<!-- BEGIN voucher_inc_item_tpl -->
    <td><nobr>{voucher_inc_tax}</nobr></td>
	<!-- END voucher_inc_item_tpl -->

	<!-- BEGIN delete_voucher_tpl -->
	<td>
	<input type="checkbox" name="RemoveVoucherArray[]" value="{number}" />
	</td>
	<!-- END delete_voucher_tpl -->
</tr>
<!-- END voucher_item_tpl -->
<!-- BEGIN rest_list_tpl -->
<tr>
     <td>
     <b>{intl-rest}:</b>
     </td>
    <!-- BEGIN rest_inc_tax_tpl -->
    <td><nobr>{rest_price_inc_tax}</nobr></td>
    <!-- END rest_inc_tax_tpl -->
    <!-- BEGIN rest_ex_tax_tpl -->
    <td><nobr>{rest_price_ex_tax}</nobr></td>
    <!-- END rest_ex_tax_tpl -->
</tr>
<!-- END rest_list_tpl -->
</table>

<br /><br />
<hr noshade="noshade" size="4" />
<input type="submit" name="Next" value="{intl-next}" />

</form>