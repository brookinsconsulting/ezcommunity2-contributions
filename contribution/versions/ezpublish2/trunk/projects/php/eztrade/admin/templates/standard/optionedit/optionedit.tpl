<form method="post" action="/trade/productedit/optionedit/">

<h1>{intl-option_edit}: {product_name}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-title}:</p>
<input type="text" size="40" name="OptionName" value="{name_value}"/>
	
<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />
	
<p class="boxtext">{intl-options}:</p>
<table>
<tr>
	<th>{intl-value}</th>
	<th>{intl-main_price}</th>
	<!-- BEGIN group_item_tpl -->
	<th>{price_group_name}</th>
	<!-- END group_item_tpl -->
	<th>&nbsp;</th>
</tr>
	<!-- BEGIN option_item_tpl -->
<tr>
	<td>
	<input type="text" size="20" name="OptionValue[]" value="{option_value}" />
	</td>
	<td>
	<input type="text" size="8" name="OptionMainPrice[]" value="{main_price_value}" />
	</td>
	<!-- BEGIN option_price_item_tpl -->
	<td>
	<input type="text" size="8" name="OptionPrice[{value_index}][{price_group}]" value="{price_value}" />
	</td>
	<!-- END option_price_item_tpl -->
	<td>
	<div class="check"><input type="checkbox" name="OptionDelete[]" value="{value_index}" />{intl-delete}</div>
	</td>
</tr>
	<!-- END option_item_tpl -->
</table>

<hr noshade="noshade" size="4" />
<table cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="NewValue" value="{intl-new_value}" />	
	</td>
	<td>
	<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_value}" />	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input type="hidden" name="OptionID" value="{option_id}" />
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="Abort" value="{intl-abort}" />	
	</td>
	</form>
</tr>
</table>



