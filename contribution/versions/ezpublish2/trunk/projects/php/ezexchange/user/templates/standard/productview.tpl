<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
	<td colspan="8" align="center">
	<h2>{intl-quotes}</h2>
	</td>
</tr>
<tr>
	<td colspan="2" valign="top" align="left">
	{intl-quotes}
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr>
		<td>
		{intl-expire_date}
		</td>
		<td>
		{intl-type}
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
		{quote_expire_date}
		</td>
		<td>
		<!-- BEGIN quote_all_type_tpl -->
		{intl-all_type}
		<!-- END quote_all_type_tpl -->
		<!-- BEGIN quote_any_type_tpl -->
		{intl-any_type}
		<!-- END quote_any_type_tpl -->
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
	{intl-offers}
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr>
		<td>
		{intl-price}
		</td>
		<td>
		{intl-quantity}
		</td>
		<td>
		{intl-type}
		</td>
		<td>
		{intl-expire_date}
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
		<!-- BEGIN offer_all_type_tpl -->
		{intl-all_type}
		<!-- END offer_all_type_tpl -->
		<!-- BEGIN offer_any_type_tpl -->
		{intl-any_type}
		<!-- END offer_any_type_tpl -->
		</td>
		<td>
		{offer_expire_date}
		</td>
	</tr>
	<!-- END offer_item_tpl -->
	</table>
	</td>
</tr>


<tr>
<!-- BEGIN your_quote_item_tpl -->
	<td colspan="2" valign="top" align="left">
	{intl-your_quote}
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr>
		<td>
		{intl-expire_date}
		</td>
		<td>
		{intl-type}
		</td>
		<td>
		{intl-quantity}
		</td>
		<td>
		{intl-price}
		</td>
	</tr>
	<!-- BEGIN your_quote_item_content_tpl -->
	<tr>
		<td>
		{quote_expire_date}
		</td>
		<td>
		<!-- BEGIN your_quote_all_type_tpl -->
		{intl-all_type}
		<!-- END your_quote_all_type_tpl -->
		<!-- BEGIN your_quote_any_type_tpl -->
		{intl-any_type}
		<!-- END your_quote_any_type_tpl -->
		</td>
		<td>
		{quote_quantity}
		</td>
		<td>
		{quote_price}
		</td>
	</tr>
	<!-- END your_quote_item_content_tpl -->
	</table>
	</td>
<!-- END your_quote_item_tpl -->
<!-- BEGIN no_quote_item_tpl -->
	<td colspan="2">
	&nbsp;
	</td>
<!-- END no_quote_item_tpl -->


<!-- BEGIN your_offer_item_tpl -->
	<td colspan="2" valign="top" align="left">
	{intl-your_offer}
	<table class="list" width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr>
		<td>
		{intl-price}
		</td>
		<td>
		{intl-quantity}
		</td>
		<td>
		{intl-type}
		</td>
		<td>
		{intl-expire_date}
		</td>
	</tr>
	<!-- BEGIN your_offer_item_content_tpl -->
	<tr>
		<td>
		{offer_price}
		</td>
		<td>
		{offer_quantity}
		</td>
		<td>
		<!-- BEGIN your_offer_all_type_tpl -->
		{intl-all_type}
		<!-- END your_offer_all_type_tpl -->
		<!-- BEGIN your_offer_any_type_tpl -->
		{intl-any_type}
		<!-- END your_offer_any_type_tpl -->
		</td>
		<td>
		{offer_expire_date}
		</td>
	</tr>
	<!-- END your_offer_item_content_tpl -->
	</table>
	</td>
<!-- END your_offer_item_tpl -->
<!-- BEGIN no_offer_item_tpl -->
	<td colspan="2">
	&nbsp;
	</td>
<!-- END no_offer_item_tpl -->
</tr>


<tr>
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
</tr>

</table>
