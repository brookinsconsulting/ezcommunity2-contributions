<h1>{intl-product_report}</h1>

<hr noshade size="4" />

{month}

<h2>{intl-most_viewed_products}:</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<th>
	{intl-view_count}:
	</th>
</tr>
<!-- BEGIN most_viewed_product_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td>
	{view_count}
	</td>
</tr>
<!-- END most_viewed_product_tpl -->

</table>

<h2>{intl-most_added_to_cart_products}:</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<th>
	{intl-add_to_cart_count}:
	</th>
</tr>
<!-- BEGIN most_added_to_cart_products_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td>
	{add_count}
	</td>
</tr>
<!-- END most_added_to_cart_products_tpl -->

</table>

<h2>{intl-most_added_to_wishlist_products}:</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<th>
	{intl-add_to_wishlist_count}:
	</th>
</tr>
<!-- BEGIN most_added_to_wishlist_products_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td>
	{add_count}
	</td>
</tr>
<!-- END most_added_to_wishlist_products_tpl -->

</table>

<h2>{intl-most_bought_products}:</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<th>
	{intl-times_bought}:
	</th>
	<th>
	{intl-total_number_bought}:
	</th>
</tr>
<!-- BEGIN most_bought_products_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td>
	{buy_count}
	</td>
	<td>
	{total_buy_count}
	</td>
</tr>
<!-- END most_bought_products_tpl -->

<!-- BEGIN month_tpl -->
<table>
<tr>
	<!-- BEGIN month_previous_tpl -->
	<td>
	<a href="/stats/productreport/{previous_year}/{previous_month}">{intl-previous}</a>
	</td>
	<!-- END month_previous_tpl -->
	
	<!-- BEGIN month_previous_inactive_tpl -->
	<td>
	{intl-previous}
	</td>
	<!-- END month_previous_inactive_tpl -->

	<!-- BEGIN month_next_tpl -->
	<td>
	<a href="/stats/productreport/{next_year}/{next_month}">{intl-next}</a>
	</td>
	<!-- END month_next_tpl -->

	<!-- BEGIN month_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END month_next_inactive_tpl -->

</tr>
</table>
<!-- END month_tpl -->
	</td>
</tr>

</table>
<hr noshade size="4" />

