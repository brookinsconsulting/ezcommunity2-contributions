<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
	<td colspan="2" align="center" class="menuhead">
	{intl-quotes}
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" align="left">
	{intl-quotes}
	</td>

	<td colspan="1" valign="top" align="right">
	{intl-offers}
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" align="left">
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr>
		<th>
		{intl-expire_date}
		</th>
		<th>
		{intl-type}
		</th>
		<th>
		{intl-quantity}
		</th>
		<th>
		{intl-price}
		</th>
	</tr>
	<!-- BEGIN quote_item_tpl -->
	<tr>
		<td {quote_current}>
		{quote_expire_date}
		</td>
		<td {quote_current}>
		<!-- BEGIN quote_all_type_tpl -->
		{intl-all_type}
		<!-- END quote_all_type_tpl -->
		<!-- BEGIN quote_any_type_tpl -->
		{intl-any_type}
		<!-- END quote_any_type_tpl -->
		</td>
		<td {quote_current}>
		{quote_quantity}
		</td>
		<td {quote_current}>
	<!-- BEGIN real_quote_item_tpl -->
		{quote_price}
	<!-- END real_quote_item_tpl -->
	<!-- BEGIN rfq_quote_item_tpl -->
		{intl-rfq}
	<!-- END rfq_quote_item_tpl -->
	<!-- BEGIN rfq_linked_quote_item_tpl -->
		<a href="/{module}/product/request/{product_id}/{category_id}/{rfq_id}">{intl-rfq}</a>
	<!-- END rfq_linked_quote_item_tpl -->
		</td>
	</tr>
	<!-- END quote_item_tpl -->
	</table>
	</td>

	<td colspan="1" valign="top" align="right">
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr>
		<th>
		{intl-price}
		</th>
		<th>
		{intl-quantity}
		</th>
		<th>
		{intl-type}
		</th>
		<th>
		{intl-expire_date}
		</th>
	</tr>
	<!-- BEGIN offer_item_tpl -->
	<tr>
	<!-- BEGIN full_offer_item_tpl -->
		<td {offer_current}>
		{offer_price}
		</td>
		<td {offer_current}>
		{offer_quantity}
		</td>
		<td {offer_current}>
		<!-- BEGIN offer_all_type_tpl -->
		{intl-all_type}
		<!-- END offer_all_type_tpl -->
		<!-- BEGIN offer_any_type_tpl -->
		{intl-any_type}
		<!-- END offer_any_type_tpl -->
		</td>
		<td {offer_current}>
		{offer_expire_date}
		</td>
	</tr>
	<!-- END full_offer_item_tpl -->
	<!-- BEGIN empty_offer_item_tpl -->
		<td colspan="4">
		&nbsp;
		</td>
	<!-- END empty_offer_item_tpl -->
	<!-- END offer_item_tpl -->
	</table>
	</td>
</tr>


<tr>
	<td colspan="1" align="left" width="50%">
<!-- BEGIN do_quote_item_tpl -->
	<!-- BEGIN do_edit_quote_item_tpl -->
	<a href="/{module}/product/quote/{product_id}/{category_id}">{intl-quote}</a>
	<!-- END do_edit_quote_item_tpl -->
	<!-- BEGIN do_new_quote_item_tpl -->
	<a href="/{module}/product/quote/{product_id}/{category_id}">{intl-quote_new}</a>
	<!-- END do_new_quote_item_tpl -->
<!-- END do_quote_item_tpl -->
<!-- BEGIN no_do_quote_item_tpl -->
	&nbsp;
<!-- END no_do_quote_item_tpl -->
	</td>

	<td colspan="1" align="right" width="50%">
<!-- BEGIN do_offer_item_tpl -->
	<!-- BEGIN do_edit_offer_item_tpl -->
	<a href="/{module}/product/offer/{product_id}/{category_id}">{intl-offer}</a>
	<!-- END do_edit_offer_item_tpl -->
	<!-- BEGIN do_new_offer_item_tpl -->
	<a href="/{module}/product/offer/{product_id}/{category_id}">{intl-offer_new}</a>
	<!-- END do_new_offer_item_tpl -->
<!-- END do_offer_item_tpl -->
<!-- BEGIN no_do_offer_item_tpl -->
	&nbsp;
<!-- END no_do_offer_item_tpl -->
	</td>
</tr>

</table>
