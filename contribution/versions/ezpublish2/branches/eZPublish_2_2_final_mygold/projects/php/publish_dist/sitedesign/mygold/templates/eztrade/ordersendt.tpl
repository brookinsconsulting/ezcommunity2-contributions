<h1>{intl-confirming-order}</h1>

<hr noshade="noshade" size="1" />

<h2>{intl-thanks_for_shopping}</h2>

<p>{intl-email_notice}</p>
<br />

<table width="1%" border="0" cellspacing="0" cellpadding="3" align="right">
    <tr>
        <th colspan="2">Wir garantieren f&uuml;r Ihre Sicherheit</th>
    </tr>
    <tr bgcolor="#DDDDDD">
        <td>
            <form name="formSiegel" method="post" action="https://www.trustedshops.de/tshops/siegel.php3" target="_blank">
                <input type="image" border="0" src="{www_dir}/sitedesign/mygold/images/trusted_shop.gif" height="69" width="69" alt="Trusted Shops G&uuml;tesiegel - Bitte hier klicken." />
                <input name="shop_id" type="hidden" value="XD7D38F69FDE28952D48AC3056C5D449C" />
            </form>
        </td>
        <td class="small">
            <form method="post" action="https://www.trustedshops.de/tshops/protect.php3" target="_blank">
                <input name=shop_id type=hidden value="XD7D38F69FDE28952D48AC3056C5D449C" />
                <input name=email type=hidden value="{order_email}" />
                <input name=phone type=hidden value="n/a" />
                <input name=first_name type=hidden value="{customer_first_name}" />
                <input name=last_name type=hidden value="{customer_last_name}" />
                <input name=street type=hidden value="{billing_street1}" />
                <input name=zip type=hidden value="{billing_zip}" />
                <input name=city type=hidden value="{billing_place}" />
                <input name=country type=hidden value="{billing_country}" />
                <input name=amount type=hidden value="{order_sum_wo_cncy}" />
                <input name=curr type=hidden value="DEM" />
                Als Trusted Shops Mitglied bieten wir Ihnen als zus&auml;tzlichen
                Service die Geld-Zur&uuml;ck-Garantie von Gerling an. Wir &uuml;bernehmen
                alle Kosten dieser Garantie, Sie m&uuml;ssen sich lediglich anmelden.
                <input type="submit" class="okbutton small" name="btnProtect" value="Anmeldung Geld-Zur&uuml;ck-Garantie..." />
            </form>
        </td>
    </tr>
</table>
<br clear="all" />
<hr noshade="noshade" size="1" />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<!-- BEGIN billing_address_tpl -->
	<b>{intl-billing_address}:</b><br /> 
	{customer_first_name} {customer_last_name} <br />
	{billing_street1}<br />
	{billing_street2}<br />
	{billing_zip} {billing_place}<br />
	{billing_country}<br />
	<!-- END billing_address_tpl -->
	<br />
	</td>
	<td>
	<!-- BEGIN shipping_address_tpl -->
	<b>{intl-shipping_address}:</b><br />
	{shipping_first_name} {shipping_last_name} <br />
	{shipping_street1}<br />
	{shipping_street2}<br />
	{shipping_zip} {shipping_place}<br />
	{shipping_country}<br />
	<!-- END shipping_address_tpl -->
	<br />
	</td>
</tr>
<tr>
	<td>
	<b>{intl-payment_method}:</b>
	<div class="p">{payment_method}</div>
	</td>
	<td>
	<b>{intl-shipping_type}:</b>
	<div class="p">{shipping_type}</div>
	</td>
</tr>
</table>
<br />
<b>{intl-comment}:</b><br />
{comment}

<br />
<br />

<h2>{intl-goods_list}:</h2>

<!-- BEGIN full_cart_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0"><!-- BEGIN cart_item_list_tpl -->
  <tr>
    <th>&nbsp;</th>
    <th align="right">{intl-product_qty}:</th>    
    <th>{intl-product_number}:</th>
    <th>{intl-product_name}:</th>
    <th align="right"><nobr>{intl-product_price}</nobr><br /><nobr>{intl-inc_tax}</nobr></th>
    <!-- BEGIN header_savings_item_tpl -->
    <th align="right">{intl-product_savings}:</th>
    <!-- END header_savings_item_tpl -->
    <!-- BEGIN header_ex_tax_item_tpl -->
    <th align="right"><nobr>{intl-product_total}</nobr><br /><nobr>{intl-ex_tax}</nobr></th>
    <!-- END header_ex_tax_item_tpl -->
    <!-- BEGIN header_inc_tax_item_tpl -->
    <th align="right"><nobr>{intl-product_total}</nobr><br /><nobr>{intl-inc_tax}</nobr></th>
    <!-- END header_inc_tax_item_tpl -->
    <th align="right">&nbsp;</th>
  </tr>
  <!-- BEGIN cart_item_tpl -->
  <tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}" align="right">{product_count}
	    <!--
        <input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
	    <input size="3" type="text" name="CartCountArray[]" value="{product_count}" />
        -->
    </td>

    <td class="{td_class}">{product_number}</td>
    <td class="{td_class}"><a href="{www_dir}{index}/trade/productview/{product_id}">{product_name}</a></td>
    <td class="{td_class}" align="right"><nobr>{product_price}</nobr></td>
    
	<!-- BEGIN cart_savings_item_tpl -->
    <td class="{td_class}" align="right">&nbsp;</td>
	<!-- END cart_savings_item_tpl -->
    
    
	<!-- BEGIN cart_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_ex_tax}</nobr></td>
	<!-- END cart_ex_tax_item_tpl -->

	<!-- BEGIN cart_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{product_total_inc_tax}</nobr></td>
	<!-- END cart_inc_tax_item_tpl -->
    
    <td class="{td_class}"><!-- <input type="checkbox" name="CartSelectArray[]" value="{cart_item_id}" /> --></td>
</tr>

<!-- BEGIN cart_item_basis_tpl -->
<tr>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}"><span class="small">{intl-basis_price} <nobr>{basis_price}<nobr/></span></td>
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
    <td class="{td_class}"><span class="small">{option_id} {option_name} {option_value} <nobr>{option_price}<nobr/></span></td>
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
    <td>&nbsp;</td>
    
    <td colspan="{subtotals_span_size}" align="right">{intl-subtotal}:</td>

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
    <td colspan="{subtotals_span_size}" align="right">{intl-shipping}:</td>

	<!-- BEGIN shipping_ex_tax_item_tpl -->
    <td align="right"><nobr>{shipping_ex_tax}</nobr></td>
	<!-- END shipping_ex_tax_item_tpl -->

	<!-- BEGIN shipping_inc_tax_item_tpl -->
    <td align="right"><nobr>{shipping_inc_tax}</nobr></td>
	<!-- END shipping_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td colspan="{subtotals_span_size}" align="right">{intl-total}:</td>

	<!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right"><nobr>{total_ex_tax}</nobr></td>
	<!-- END total_ex_tax_item_tpl -->

	<!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right"><nobr>{total_inc_tax}</nobr></td>
	<!-- END total_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>

</table>

<!-- BEGIN voucher_item_list_tpl -->

<h2>{intl-voucher_list}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-voucher_number}:</th>

	<!-- BEGIN voucher_used_header_ex_tax_item_tpl -->
	<th align="right"><nobr>{intl-voucher_used}</nobr><br /><nobr>{intl-ex_tax}</nobr></th>
	<!-- END voucher_used_header_ex_tax_item_tpl -->

	<!-- BEGIN voucher_used_header_inc_tax_item_tpl -->
	<th align="right"><nobr>{intl-voucher_used}</nobr><br /><nobr>{intl-inc_tax}</nobr></th>
	<!-- END voucher_used_header_inc_tax_item_tpl -->

	<!-- BEGIN voucher_left_header_ex_tax_item_tpl -->
	<th align="right"><nobr>{intl-voucher_left}</nobr><br /><nobr>{intl-ex_tax}</nobr></th>
	<!-- END voucher_left_header_ex_tax_item_tpl -->

	<!-- BEGIN voucher_left_header_inc_tax_item_tpl -->
	<th align="right"><nobr>{intl-voucher_left}</nobr><br /><nobr>{intl-inc_tax}</nobr></th>
	<!-- END voucher_left_header_inc_tax_item_tpl -->

</tr>
<!-- BEGIN voucher_item_tpl -->
<tr>

    <td class="{td_class}">{voucher_number}</td>

	<!-- BEGIN voucher_used_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_used_ex_tax}</nobr></td>
	<!-- END voucher_used_ex_tax_item_tpl -->

	<!-- BEGIN voucher_used_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_used_inc_tax}</nobr></td>
	<!-- END voucher_used_inc_tax_item_tpl -->

	<!-- BEGIN voucher_left_ex_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_left_ex_tax}</nobr></td>
	<!-- END voucher_left_ex_tax_item_tpl -->

	<!-- BEGIN voucher_left_inc_tax_item_tpl -->
    <td class="{td_class}" align="right"><nobr>{voucher_left_inc_tax}</nobr></td>
	<!-- END voucher_left_inc_tax_item_tpl -->

</tr>
<!-- END voucher_item_tpl -->

</table>
<!-- END voucher_item_list_tpl -->

<!-- BEGIN tax_specification_tpl -->
<br />
<br />

<h2>{intl-tax_list}:</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr align="right">
<th>{intl-tax_basis}:</th>
<th>{intl-tax_percentage}:</th>
<th>{intl-tax}:</th>
<th>&nbsp;</th>
</tr>

<!-- BEGIN tax_item_tpl -->

<tr>
    <td class="{td_class}" align="right">{sub_tax_basis}</td>
    <td class="{td_class}" align="right">{sub_tax_percentage} %</td>
    <td class="{td_class}" align="right">{sub_tax}</td>
    <td class="{td_class}" align="right">&nbsp;</td>
</tr>
<!-- END tax_item_tpl -->

<tr>
    <td colspan="2" align="right">{intl-total}:</td>
    <td align="right">{tax}</td>
</tr>

</table>
<!-- END tax_specification_tpl -->
<!-- END full_cart_tpl -->



