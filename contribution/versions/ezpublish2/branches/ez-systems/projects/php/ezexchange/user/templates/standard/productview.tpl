<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
	<td colspan="4" align="center">
	<h2>{intl-quotes}</h2>
	</td>
</tr>
<tr>
	<td colspan="2" valign="top" align="left">
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
	<td>
	{intl-quote_customers}
	</td>
	<td>
	{intl-quantity}
	</td>
	<td>
	{intl-price}
	</td>
</tr>
<!-- BEGIN quote_item_tpl -->
<tr>
	<td>
	{quote_customers}
	</td>
	<td>
	{quote_quantity}
	</td>
	<td>
	{quote_price}
	</td>
</tr>
<!-- END quote_item_tpl -->
	</table>
	</td>

	<td colspan="2" valign="top" align="right">
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
	<td>
	{intl-price}
	</td>
	<td>
	{intl-quantity}
	</td>
	<td>
	{intl-offer_suppliers}
	</td>
</tr>
<!-- BEGIN offer_item_tpl -->
<tr>
	<td>
	{offer_price}
	</td>
	<td>
	{offer_quantity}
	</td>
	<td>
	{offer_suppliers}
	</td>
</tr>
<!-- END offer_item_tpl -->
	</table>
	</td>
</tr>

<tr>
<!-- BEGIN your_quote_item_tpl -->
	<td colspan="2">
	{intl-your_quote}
	</td>
<!-- END your_quote_item_tpl -->
<!-- BEGIN no_quote_item_tpl -->
	<td colspan="2">
	&nbsp;
	</td>
<!-- END no_quote_item_tpl -->

<!-- BEGIN your_offer_item_tpl -->
	<td colspan="2">
	{intl-your_offer}
	</td>
<!-- END your_offer_item_tpl -->
<!-- BEGIN no_offer_item_tpl -->
	<td colspan="2">
	&nbsp;
	</td>
<!-- END no_offer_item_tpl -->

</tr>

<tr>
<!-- BEGIN your_quote_info_item_tpl -->
	<td>
	{intl-quantity}: {your_quote_quantity}
	</td>

	<td>
	{intl-price}: {your_quote_price}
	</td>
<!-- END your_quote_info_item_tpl -->
<!-- BEGIN no_quote_info_item_tpl -->
	<td colspan="2">
	&nbsp;
	</td>
<!-- END no_quote_info_item_tpl -->

<!-- BEGIN your_offer_info_item_tpl -->
	<td>
	{intl-quantity}: {your_offer_quantity}
	</td>

	<td>
	{intl-price}: {your_offer_price}
	</td>
<!-- END your_offer_info_item_tpl -->
<!-- BEGIN no_offer_info_item_tpl -->
	<td>
	&nbsp;
	</td>
<!-- END no_offer_info_item_tpl -->

</tr>
	<td colspan="2" align="left">
<!-- BEGIN do_quote_item_tpl -->
	<a href="/{module}/product/quote/{product_id}">{intl-quote}</a>
<!-- END do_quote_item_tpl -->
<!-- BEGIN no_do_quote_item_tpl -->
	&nbsp;
<!-- END no_do_quote_item_tpl -->
	</td>

	<td colspan="2" align="right">
<!-- BEGIN do_offer_item_tpl -->
	<a href="/{module}/product/offer/{product_id}">{intl-offer}</a>
<!-- END do_offer_item_tpl -->
<!-- BEGIN no_do_offer_item_tpl -->
	&nbsp;
<!-- END no_do_offer_item_tpl -->
	</td>
<tr>

</tr>

</table>
