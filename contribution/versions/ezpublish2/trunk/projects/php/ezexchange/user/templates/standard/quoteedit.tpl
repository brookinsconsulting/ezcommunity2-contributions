<form method="post" action="/exchange/product/{quote_type}/{product_id}/{category_id}/{quote_id}" enctype="multipart/form-data">

<h2>{intl-quote_edit}</h2>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_quantity_item_tpl -->
<li>{intl-error_quantity}
<!-- END error_quantity_item_tpl -->

<!-- BEGIN error_price_item_tpl -->
<li>{intl-error_price}
<!-- END error_price_item_tpl -->

<!-- BEGIN error_expire_item_tpl -->
<li>{intl-error_expire}
<!-- END error_expire_item_tpl -->

<!-- BEGIN error_low_price_item_tpl -->
<li>{intl-error_low_price}
<!-- END error_low_price_item_tpl -->

<!-- BEGIN error_low_expire_item_tpl -->
<li>{intl-error_low_expire}
<!-- END error_low_expire_item_tpl -->

<!-- BEGIN error_low_quantity_item_tpl -->
<li>{intl-error_low_quantity}
<!-- END error_low_quantity_item_tpl -->

<!-- BEGIN error_high_price_item_tpl -->
<li>{intl-error_high_price_pre}{high_price}{intl-error_high_price_post}
<!-- END error_high_price_item_tpl -->

</ul>
<!-- END errors_tpl -->

<p class="boxtext">{intl-product}: {product_name}</p>

<table border="1">

<tr>
	<td>
	</td>
	<td>
	{intl-today}:
	</td>
	<td>
	{intl-days_left}:
	</td>
	<td>
	{intl-expire_date}:
	</td>
	<td>
	{intl-type}:
	</td>
	<td>
	{intl-quantity}:
	</td>
	<!-- BEGIN quote_header_price_tpl -->
	<td>
	{intl-price}:
	</td>
	<!-- END quote_header_price_tpl -->
</tr>

<!-- BEGIN edit_quote_tpl -->
<tr>
	<td>
	{intl-current_values}
	</td>
	<td>
	{today}
	</td>
	<td>
	{last_days}
	</td>
	<td>
	{last_expire_date}
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
	{last_quantity}
	</td>
	<td>
	{last_price}
	</td>
</tr>
<tr>
	<td>
	{intl-new_values}
	</td>
	<td>
	{today}
	</td>
	<td>
	<input type="text" size="7" name="DaysLeft" value="{cur_days}"/>
	</td>
	<td>
	&nbsp;
	</td>
	<td>
	<!-- BEGIN quote_2_all_type_tpl -->
	{intl-all_type}
	<!-- END quote_2_all_type_tpl -->
	<!-- BEGIN quote_2_any_type_tpl -->
	{intl-any_type}
	<!-- END quote_2_any_type_tpl -->
	</td>
	<td>
	<!-- BEGIN quote_edit_quantity_tpl -->
	<input type="text" size="7" name="Quantity" value="{cur_quantity}"/>
	<!-- END quote_edit_quantity_tpl -->
	<!-- BEGIN quote_show_quantity_tpl -->
	<input type="hidden" name="Quantity" value="{cur_quantity}"/>
	{cur_quantity}
	<!-- END quote_show_quantity_tpl -->
	</td>
	<!-- BEGIN quote_edit_price_tpl -->
	<td>
	<input type="text" size="7" name="Price" value="{cur_price}"/>
	</td>
	<!-- END quote_edit_price_tpl -->
</tr>
<!-- END edit_quote_tpl -->

<!-- BEGIN new_quote_tpl -->
<tr>
	<td>
	{intl-new_values}
	</td>
	<td>
	{today}
	</td>
	<td>
	<input type="text" size="7" name="DaysLeft" value="{cur_days}"/>
	</td>
	<td>
	&nbsp;
	</td>
	<td>
	<select name="QuoteType">
	<option	value="0" {all_selected}>{intl-all_type}</option>
	<option	value="1" {any_selected}>{intl-any_type}</option>
	</select>
	</td>
	<td>
	<input type="text" size="7" name="Quantity" value="{cur_quantity}"/>
	</td>
	<!-- BEGIN quote_new_price_tpl -->
	<td>
	<input type="text" size="7" name="Price" value="{cur_price}"/>
	</td>
	<!-- END quote_new_price_tpl -->
</tr>
<!-- END new_quote_tpl -->

<!-- BEGIN quote_rfq_price_tpl -->
<input type="hidden" name="Price" value="rfq"/>
<!-- END quote_rfq_price_tpl -->

</table>

<input type="hidden" name="ProductID" value="{product_id}">
<input type="hidden" name="CategoryID" value="{category_id}">

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}">
<input type="submit" name="Cancel" value="{intl-cancel}">
</form>
