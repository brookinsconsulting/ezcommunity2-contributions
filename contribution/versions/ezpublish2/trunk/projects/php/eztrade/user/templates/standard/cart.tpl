<h1>{intl-cart}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<form action="{www_dir}{index}/trade/cart/" method="post">

<!-- BEGIN full_cart_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">

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
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">{product_number}</td>
    <td class="{td_class}"><a href="/trade/productview/{product_id}">{product_name}</a></td>
    <td class="{td_class}" align="right">{product_price}</td>
    
	<!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END cart_savings_item_tpl -->
    
    <td class="{td_class}" align="right">
	    <input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
	    <input size="3" type="text" name="CartCountArray[]" value="{product_count}" />
    </td>
    
	<!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right">{product_total_ex_tax}</td>
	<!-- END cart_ex_tax_item_tpl -->

	<!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right">{product_total_inc_tax}</td>
	<!-- END cart_inc_tax_item_tpl -->
    
    <td class="{td_class}">&nbsp;<!-- <input type="checkbox" name="CartSelect[]" value="{cart_item_id}" /> --></td>
</tr>

<!-- BEGIN cart_item_basis_tpl -->
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">{intl-basis_price} {basis_price}</td>
    <td class="{td_class}" align="right">&nbsp;</td>
    
	<!-- BEGIN basis_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_savings_item_tpl -->
    
    <td class="{td_class}" align="right">&nbsp;</td>

	<!-- BEGIN basis_inc_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_inc_tax_item_tpl -->
    
	<!-- BEGIN basis_ex_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_ex_tax_item_tpl -->

    <td class="{td_class}">&nbsp;</td>
</tr>
<!-- END cart_item_basis_tpl -->

<!-- BEGIN cart_item_option_tpl -->
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">{option_id} {option_name} {option_value} {option_price}</td>
    <td class="{td_class}" align="right">&nbsp;</td>
    
	<!-- BEGIN option_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END option_savings_item_tpl -->
    
    <td class="{td_class}" align="right">&nbsp;</td>

	<!-- BEGIN option_inc_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END option_inc_tax_item_tpl -->
    
	<!-- BEGIN option_ex_tax_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END option_ex_tax_item_tpl -->

    <td class="{td_class}">&nbsp;</td>
</tr>
<!-- END cart_item_option_tpl -->

<!-- END cart_item_tpl -->

<!-- END cart_item_list_tpl -->

<tr>
<td colspan="9"><hr noshade="noshade" size="4" /></td>
</tr>

<tr>
    <td>&nbsp;</td>
    
    <td colspan="{subtotals_span_size}">{intl-subtotal}</td>

	<!-- BEGIN subtotal_ex_tax_item_tpl -->
    <td align="right">{subtotal_ex_tax}</td>
	<!-- END subtotal_ex_tax_item_tpl -->

	<!-- BEGIN subtotal_inc_tax_item_tpl -->
    <td align="right">{subtotal_inc_tax}</td>
	<!-- END subtotal_inc_tax_item_tpl -->
    
    <td>&nbsp;</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}">{intl-shipping}</td>

	<!-- BEGIN shipping_ex_tax_item_tpl -->
    <td align="right">{shipping_ex_tax}</td>
	<!-- END shipping_ex_tax_item_tpl -->

	<!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right">{shipping_inc_tax}</td>
	<!-- END shipping_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}">{intl-total}</td>

	<!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right">{total_ex_tax}</td>
	<!-- END total_ex_tax_item_tpl -->

	<!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right">{total_inc_tax}</td>
	<!-- END total_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>

</table>
<!-- END full_cart_tpl -->

<!-- BEGIN tax_specification_tpl -->
<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<th class="right">{intl-tax_basis}</th>
<th class="right">{intl-tax_percentage}</th>
<th class="right">{intl-tax}</th>
</tr>

<!-- BEGIN tax_item_tpl -->
<tr>
<td align="right">{sub_tax_basis}</td>
<td align="right">{sub_tax_percentage}</td>
<td align="right">{sub_tax}</td>
</tr>
<!-- END tax_item_tpl -->

<tr>
<td colspan="3"><hr noshade="noshade" size="4" /></td>
</tr>

<tr>
<td colspan="2" align="right">{intl-total}</td>
<td align="right">{tax}</td>
</tr>

</table>
<!-- END tax_specification_tpl -->

<!-- BEGIN cart_checkout_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="ShopMore" value="{intl-shopmore}" />
	</td>
	<td>&nbsp;</td>
	<td align="right">
	<input class="stdbutton" type="submit" value="{intl-update}" />
	</td>
</td>
</table>
<hr noshade="noshade" size="4" />
	<!-- BEGIN cart_checkout_button_tpl -->
	<input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	<!-- END cart_checkout_button_tpl -->

<!-- END cart_checkout_tpl -->


<input type="hidden" name="Action" value="Refresh" />

</form>
