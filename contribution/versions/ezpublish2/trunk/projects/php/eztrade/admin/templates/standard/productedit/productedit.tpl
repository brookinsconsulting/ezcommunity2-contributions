<form method="post" action="/trade/productedit/{action_value}/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-product_number}:</p>
<input type="text" size="40" name="ProductNumber" value="{product_nr_value}"/>
	
<p class="boxtext">{intl-category}:</p>
<select name="CategoryID">
<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->
</select>

<p class="boxtext">{intl-keywords}:</p>
<textarea rows="5" cols="40" name="Keywords">{keywords_value}</textarea>
<br /><br />

<p class="boxtext">{intl-intro}:</p>
<textarea rows="5" cols="40" name="Brief">{brief_value}</textarea>
<br /><br />

<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<div class="check"><input type="checkbox" name="Active" {showproduct_checked} />&nbsp;{intl-active}</div>
	</td>
	<td>
	<div class="check"><input type="checkbox" name="InheritOptions" {inherit_options_checked} />{intl-inherit_options}</div>
	</td>
</tr>
<tr>
	<td width="20%">
	<div class="check"><input type="checkbox" name="ShowPrice" {showprice_checked} />&nbsp;{intl-has_price}</div>
	<br />
	</td>
	<td>
	<p class="boxtext">{intl-price}:</p>
	<input type="text" size="10" name="Price" value="{price_value}" />
	<br /><br />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

	<input class="stdbutton" type="submit" name="Image" value="{intl-pictures}" />
	<input class="stdbutton" type="submit" name="Option" value="{intl-options}" />
	<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	Avbrytknapp!
	</td>
</tr>
</table>
