<h1>{intl-currency}</h1>

<hr noshade="noshade" size="4" />

<form action="/trade/currency/" method="post">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-currency_name}:
	</th>
	<th>
	{intl-currency_sign}:
	</th>
	<th>
	{intl-prefixed_currency_sign}:
	</th>
	<th>
	{intl-currency_ratio}:
	</th>
<tr>

<!-- BEGIN currency_item_tpl -->
<tr>
	<th class="{td_class}">
	 <input type="hidden" name="CurrencyID[]" value="{currency_id}" />
	  <input type="text" size="6" name="CurrencyName[]" value="{currency_name}" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="6" name="CurrencySign[]" value="{currency_sign}" />
	</td>
	<td class="{td_class}">
	  {intl-prefix}: <input type="radio" name="CurrencyPrefix_{currency_id}[]" {currency_prefixed} value="1" />&nbsp;
	  {intl-postfix}: <input type="radio" name="CurrencyPrefix_{currency_id}[]" {currency_not_prefixed} value="0" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="6" name="CurrencyValue[]" value="{currency_value}" />
	</td>
	<td class="{td_class}">
	  <input type="checkbox" name="DeleteID[]" {checked} value="{currency_id}" />
	</td>

</tr>
<!-- END currency_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="AddCurrency" value="{intl-add_currency}" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
