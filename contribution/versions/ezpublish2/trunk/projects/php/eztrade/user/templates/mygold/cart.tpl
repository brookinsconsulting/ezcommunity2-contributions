<h1>{intl-cart}</h1>

<hr noshade="noshade" size="1" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<form action="{www_dir}{index}/trade/cart/" method="post">

<!-- BEGIN full_cart_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="1" border="0">
  <!-- BEGIN cart_item_list_tpl -->
  <tr>
    <th width="1%">&nbsp;</th>
    <th width="1%"><nobr>{intl-product_number}:</nobr></th>
    <th align="left"><nobr>&nbsp;{intl-product_name}:</nobr></th>
    <th class="right" width="1%"><nobr>{intl-product_price}:</nobr></th>
    <!-- BEGIN header_savings_item_tpl -->
    <th class="right">{intl-product_savings}:</th>
    <!-- END header_savings_item_tpl -->
    <!-- BEGIN header_ex_tax_item_tpl -->
    <th class="right" width="1%"><nobr>{intl-product_total_ex_tax}:</nobr></th>
    <!-- END header_ex_tax_item_tpl -->
    <!-- BEGIN header_inc_tax_item_tpl -->
    <th class="right" width="1%"><nobr>{intl-product_total_inc_tax}:</nobr></th>
    <!-- END header_inc_tax_item_tpl -->
    <th class="right" width="1%">&nbsp;</th>
  </tr>
  <!-- BEGIN cart_item_tpl -->
  <tr valign="top">
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}"><nobr>&nbsp;{product_number}</nobr></td>
    <td class="{td_class}">
      &nbsp;<a href="/trade/productview/{product_id}">{product_name}</a><br />
      <!-- BEGIN cart_item_option_tpl -->
      &nbsp;<span class="small">Gr&ouml;&szlig;e {option_value}</span>
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
    <td class="{td_class}" align="right"><nobr>&nbsp;{product_price}</nobr></td>
    <!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;<input type="hidden" name="CartIDArray[]" value="{cart_item_id}" /></td>
    <!-- END cart_savings_item_tpl -->
    <!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>&nbsp;{product_total_ex_tax}</nobr></td>
    <!-- END cart_ex_tax_item_tpl -->
    <!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>&nbsp;{product_total_inc_tax}</nobr></td>
    <!-- END cart_inc_tax_item_tpl -->
    <td class="{td_class}"><input type="checkbox" name="CartSelectArray[]" value="{cart_item_id}" /></td>
  </tr>
  <!-- BEGIN cart_item_basis_tpl -->
    <!-- {intl-basis_price} {basis_price} -->
    <!-- BEGIN basis_savings_item_tpl -->
    &nbsp;
    <!-- END basis_savings_item_tpl -->
    <!-- BEGIN basis_inc_tax_item_tpl -->
    &nbsp;
    <!-- END basis_inc_tax_item_tpl -->
    <!-- BEGIN basis_ex_tax_item_tpl -->
    &nbsp;
    <!-- END basis_ex_tax_item_tpl -->
  <!-- END cart_item_basis_tpl -->


  <!-- END cart_item_tpl -->
  <!-- END cart_item_list_tpl -->
  <tr>
    <td colspan="{subtotals_span_size}" align="right">{intl-subtotal}:</th>
    <!-- BEGIN subtotal_ex_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_ex_tax}</nobr></td>
    <!-- END subtotal_ex_tax_item_tpl -->
    <!-- BEGIN subtotal_inc_tax_item_tpl -->
    <td align="right"><nobr>{subtotal_inc_tax}</nobr></td>
    <!-- END subtotal_inc_tax_item_tpl -->
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="{subtotals_span_size}" align="right">{intl-shipping}:</th>
    <!-- BEGIN shipping_ex_tax_item_tpl -->
    <td align="right"><nobr>{shipping_ex_tax}</nobr></td>
    <!-- END shipping_ex_tax_item_tpl -->
    <!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right"><nobr>{shipping_inc_tax}</nobr></td>
    <!-- END shipping_inc_tax_item_tpl -->
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="{subtotals_span_size}" align="right">{intl-total}:</th>
    <!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right"><nobr>{total_ex_tax}</nobr></td>
    <!-- END total_ex_tax_item_tpl -->
    <!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right"><nobr>{total_inc_tax}</nobr></td>
    <!-- END total_inc_tax_item_tpl -->
    <td>&nbsp;</td>
  </tr>
</table>

<!-- BEGIN tax_specification_tpl -->
<!-- BEGIN tax_item_tpl -->
&nbsp;
<!-- END tax_item_tpl -->
<!-- END tax_specification_tpl -->

<hr noshade="noshade" size="1" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td align="right">
      <input class="okbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
    </td>
  </td>
</table>
<!-- END full_cart_tpl -->

<!-- BEGIN cart_checkout_tpl -->
<hr noshade="noshade" size="1" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td>
      <input class="okbutton" type="submit" name="ShopMore" value="{intl-shopmore}" />
    </td>
    <td align="right">
      <!-- BEGIN cart_checkout_button_tpl -->
      <input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
      <!-- END cart_checkout_button_tpl -->
    </td>
  </tr>
</table>
<!-- END cart_checkout_tpl -->
<input type="hidden" name="Action" value="Refresh" />
</form>
