<h1>{intl-head_line}</h1>

<form method="post" action="/trade/productedit/{action_value}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="2">
	{intl-name}:
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	{intl-keywords}:
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="text" size="20" name="Keywords" value="{keywords_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	{intl-product_number}:
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="text" size="20" name="ProductNumber" value="{product_nr_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	{intl-category}:
	</td>
</tr>
<tr>
	<td colspan="2">
	<select name="CategoryID">
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
</tr>
<tr>
	<td colspan="2">
	{intl-intro}:
	</td>
</tr>
<tr>
	<td colspan="2">
        <textarea rows="5" cols="20" name="Brief">{brief_value}</textarea>
	</td>
</tr>
<tr>
	<td colspan="2">
	{intl-description}:
	</td>
</tr>
<tr>
	<td colspan="2">
        <textarea rows="5" cols="20" name="Description">{description_value}</textarea>
	</td>
</tr>
<tr>
	<td>
	{intl-has_price}:
	</td>
	<td>
	{intl-price}:
	</td>
</tr>
<tr>
	<td width="20%">
	<input type="checkbox" name="ShowPrice" {showprice_checked} />
	</td>
	<td>
	<input type="text" size="10" name="Price" value="{price_value}" />
	</td>
</tr>
<tr>
	<td>
	{intl-active}:
	</td>
	<td>
	{intl-inherit_options}:
	</td>
</tr>
<tr>
	<td width="20%">
	<input type="checkbox" name="Active" {showproduct_checked} />
	</td>
	<td>
	<input type="checkbox" name="InheritOptions" {inherit_options_checked} />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="submit" name="Image" value="{intl-pictures}" />
	<input type="submit" name="Option" value="{intl-options}" />
	<input type="submit" name="Preview" value="{intl-preview}" />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input type="submit" value="{intl-ok}" />
	</td>
</tr>
</table>
</form>


