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

<form action="{www_dir}{index}/trade/checkout/address/" method="post">

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
<hr noshade="noshade" size="4" />
<input type="submit" name="Next" value="{intl-next}" />

</form>