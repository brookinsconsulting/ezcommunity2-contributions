<form method="post" action="/exchange/product/{quote_type}/{product_id}/" enctype="multipart/form-data">

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

</ul>
<!-- END errors_tpl -->

<p class="boxtext">{intl-product}: {product_name}</p>

<!-- BEGIN last_quote_tpl -->
<h3>{intl-current_values}</h3>
<table>
<tr>
	<td>
	{intl-quantity}:
	</td>
	<td>
	{intl-price}:
	</td>
	<td>
	{intl-days}:
	</td>
</tr>
<tr>
	<td>
	{last_quantity}
	</td>
	<td>
	{last_price}
	</td>
	<td>
	{last_days}
	</td>
</tr>
</table>
<!-- END last_quote_tpl -->

<br>

{intl-type}:<br>

<select name="QuoteType">
<option value="0" {all_selected}>{intl-all}</option>
<option value="1" {any_selected}>{intl-any}</option>
</select>

<table>
<tr>
<!-- BEGIN quantity_title_tpl -->
	<td>
	{intl-quantity}:
	</td>
<!-- END quantity_title_tpl -->
	<td>
	{intl-price}:
	</td>
	<td>
	{intl-days}:
	</td>
</tr>
<tr>
<!-- BEGIN quantity_edit_tpl -->
	<td>
	<input type="text" size="7" name="Quantity" value="{quantity}"/>
	</td>
<!-- END quantity_edit_tpl -->
<!-- BEGIN no_quantity_edit_tpl -->
	<td>
	<input type="hidden" name="Quantity" value="{quantity}"/>
        {quantity}
	</td>
<!-- END no_quantity_edit_tpl -->
	<td>
	<input type="text" size="7" name="Price" value="{price}"/><br>
	</td>
	<td>
	<input type="text" size="7" name="Days" value="{days}"/><br>
	</td>
</tr>
</table>

<input type="hidden" name="ProductID" value="{product_id}">

<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}">
<input type="submit" name="Cancel" value="{intl-cancel}">
</form>