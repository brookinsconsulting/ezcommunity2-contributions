<form action="{www_dir}{index}/trade/checkout/" method="post">

<h1>{intl-confirm_order}</h1>
<hr noshade="noshade" size="1" />
<h2>{intl-products_about_to_order}:</h2>

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<!-- BEGIN full_cart_tpl -->
<table width="100%" cellspacing="0" cellpadding="1" border="0">
  <!-- BEGIN cart_item_list_tpl -->
  <tr>
    <th>&nbsp;</th>
    <th>{intl-product_number}:</th>
    <th>{intl-product_name}:</th>
    <th class="right">{intl-product_price}:</th>
    <!-- BEGIN header_savings_item_tpl -->
    <th class="right">{intl-product_savings}:</th>
    <!-- END header_savings_item_tpl -->
    <th class="right">{intl-product_qty}:</th>
    <!-- BEGIN header_ex_tax_item_tpl -->
    <th class="right">{intl-product_total_ex_tax}:</th>
    <!-- END header_ex_tax_item_tpl -->
    <!-- BEGIN header_inc_tax_item_tpl -->
    <th class="right">{intl-product_total_inc_tax}:</th>
    <!-- END header_inc_tax_item_tpl -->
    <th class="right">&nbsp;</th>
  </tr>
  
  <!-- BEGIN cart_item_tpl -->
  <tr valign="top">
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">{product_number}</td>
    <td class="{td_class}">
      <a href="/trade/productview/{product_id}">{product_name}</a><br />
      <!-- BEGIN cart_item_option_tpl -->
      <span class="small">Gr&ouml;&szlig;e: {option_value}</span>
      <!-- BEGIN option_savings_item_tpl -->
      &nbsp;
      <!-- END option_savings_item_tpl -->
      <!-- BEGIN option_inc_tax_item_tpl -->
      &nbsp;
      <!-- END option_inc_tax_item_tpl -->
      <!-- BEGIN option_ex_tax_item_tpl -->
      &nbsp;
      <!-- END option_ex_tax_item_tpl -->
      <!-- END cart_item_option_tpl -->
    </td>
    <td class="{td_class}" align="right"><nobr>{product_price}</nobr></td>
    <!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
    <!-- END cart_savings_item_tpl -->
    <td class="{td_class}" align="right">
      {product_count}    
      <!--
      <input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
      <input size="3" type="text" name="CartCountArray[]" value="{product_count}" />
      -->
    </td>
    <!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_ex_tax}</nobr></td>
    <!-- END cart_ex_tax_item_tpl -->
    <!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_inc_tax}</nobr></td>
    <!-- END cart_inc_tax_item_tpl -->
    <td class="{td_class}"> <!-- <input type="checkbox" name="CartSelectArray[]" value="{cart_item_id}" /> --></td>
  </tr>
  
  <!-- BEGIN cart_item_basis_tpl -->
  <!--
  {intl-basis_price} {basis_price}
  -->
  <!-- BEGIN basis_savings_item_tpl -->
  <!-- -->
  <!-- END basis_savings_item_tpl -->
  <!-- BEGIN basis_inc_tax_item_tpl -->
  <!-- -->  
  <!-- END basis_inc_tax_item_tpl -->
  <!-- BEGIN basis_ex_tax_item_tpl -->
  <!-- -->  
  <!-- END basis_ex_tax_item_tpl -->

  <!-- END cart_item_basis_tpl -->
  <!-- END cart_item_tpl -->
  <!-- END cart_item_list_tpl -->
  <tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}" class="right">{intl-subtotal}:</th>
    <!-- BEGIN subtotal_ex_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_ex_tax}</nobr></td>
    <!-- END subtotal_ex_tax_item_tpl -->
    <!-- BEGIN subtotal_inc_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_inc_tax}</nobr></td>
    <!-- END subtotal_inc_tax_item_tpl -->
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}" class="right">{intl-shipping}:</th>
    <!-- BEGIN shipping_ex_tax_item_tpl -->
    <td align="right"><nobr>{shipping_ex_tax}</nobr></td>
    <!-- END shipping_ex_tax_item_tpl -->
    <!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right"><nobr>{shipping_inc_tax}</nobr></td>
    <!-- END shipping_inc_tax_item_tpl -->
    <td>&nbsp;</td>
  </tr>
  
  <!-- BEGIN vouchers_tpl --> 
  <!-- BEGIN voucher_item_tpl -->
  <tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}" align="right"><span class="boxtext">{intl-voucher} {number}:</span></td>
    <td align="right"><nobr>- {voucher_price_ex_vat}</nobr></td>
    <td align="right"><nobr>- {voucher_price_inc_vat}</nobr></td>
    <td><input type="checkbox" name="RemoveVoucherArray[]" value="{number}" /></td>
  </tr>
  <!-- END voucher_item_tpl -->
  <!-- END vouchers_tpl --> 
  <tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}" class="right">{intl-total}:</th>
    <!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right"><nobr>{total_ex_tax}</nobr></td>
    <!-- END total_ex_tax_item_tpl -->
    <!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right"><nobr>{total_inc_tax}</nobr></td>
    <!-- END total_inc_tax_item_tpl -->
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="{totals_span_size}" class="right">{intl-shipping_method}:</th>
    <td>&nbsp;</td>
    <td align="right">
      <select name="ShippingTypeID">
        <!-- BEGIN shipping_type_tpl -->
        <option value="{shipping_type_id}" {type_selected}>{shipping_type_name}</option>
        <!-- END shipping_type_tpl -->
      </select>
      <input class="okutton" type="submit" name="Recalculate" value="{intl-recalculate}" />
    </td>
    <td>&nbsp;</td>
  </tr>
</table>

<!-- BEGIN tax_specification_tpl -->
{intl-tax_basis}{intl-tax_percentage}{intl-tax}

<!-- BEGIN tax_item_tpl -->
{sub_tax_basis}{sub_tax_percentage}{sub_tax}

<!-- END tax_item_tpl -->
{intl-total}{tax}

<!-- END tax_specification_tpl -->
<!-- END full_cart_tpl -->


<!-- BEGIN billing_address_tpl -->
<p>{intl-billing_to}:</p>
  <select name="BillingAddressID">
    <!-- BEGIN billing_option_tpl -->
    <option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {street2}, {zip} {place} {country}</option>
    <!-- END billing_option_tpl -->
  </select>
<!-- END billing_address_tpl -->

<p>{intl-shipping_to}:</p>
  <select name="ShippingAddressID">
    <!-- BEGIN shipping_address_tpl -->
    <option value="{address_id}">{customer_first_name} {customer_last_name}, {street1}, {street2}, {zip} {place} {country}</option>
    <!-- END shipping_address_tpl -->
    <!-- BEGIN wish_user_tpl -->
    <option value="{wish_user_address_id}">{wish_first_name} {wish_last_name}</option>
    <!-- END wish_user_tpl -->
  </select>
  <br /><br />
<p>{intl-comment}:</p>
<textarea name="Comment" cols="40" rows="5"></textarea>

<!-- BEGIN show_payment_tpl -->
<br /><br />

<p>{intl-payment_methods_description}:</p>
  <select name="PaymentMethod">
    <!-- BEGIN payment_method_tpl -->
    <option value="{payment_method_id}">{payment_method_text}</option>
    <!-- END payment_method_tpl -->
  </select>
<!-- END show_payment_tpl -->

<br /><br />

<!-- BEGIN remove_voucher_tpl -->
<input class="okbutton" type="submit" name="RemoveVoucher" value="{intl-remove_voucher}" />
<!-- END remove_voucher_tpl -->

<hr noshade="noshade" size="1" />


<input type="hidden" name="ShippingCost" value="{shipping_cost_value}" />
<input type="hidden" name="ShippingVAT" value="{shipping_vat_value}" />
<input type="hidden" name="TotalCost" value="{total_cost_value}" />
<input type="hidden" name="TotalVAT" value="{total_vat_value}" />

<!-- BEGIN sendorder_item_tpl -->
<input class="okbutton" type="submit" name="SendOrder" value="{intl-send}" />
<!-- END sendorder_item_tpl -->

</form>



