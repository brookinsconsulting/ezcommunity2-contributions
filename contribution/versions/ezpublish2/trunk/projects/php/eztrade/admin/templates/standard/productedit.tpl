<form method="post" action="/trade/productedit/{action_value}/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-product_number}:</p>
<input class="box" type="text" size="40" name="ProductNumber" value="{product_nr_value}"/>
<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">	
	<p class="boxtext">{intl-category}:</p>
	<select name="CategoryID">
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
	<td valign="top">
	<p class="boxtext">{intl-additional_categories}:</p>
	<select multiple size="5" name="CategoryArray[]">
	<!-- BEGIN multiple_value_tpl -->
	<option value="{option_value}" {multiple_selected}>{option_level}{option_name}</option>
	<!-- END multiple_value_tpl -->
	</select>
	</td>
</tr>
</table>
<br />

<p class="boxtext">{intl-keywords}:</p>
<input class="box" name="Keywords" size="40" value="{keywords_value}" />
<br /><br />

<p class="boxtext">{intl-intro}:</p>
<textarea class="box" rows="5" cols="40" name="Brief" wrap="soft">{brief_value}</textarea>
<br /><br />

<p class="boxtext">{intl-description}:</p>
<textarea class="box" rows="15" cols="40" name="Description" wrap="soft">{description_value}</textarea>
<br /><br />

<p class="boxtext">{intl-external_link}:</p>
http://<input type="text" size="36" name="ExternalLink" value="{external_link}"/><br />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-price}:</p>
	<input type="text" size="10" name="Price" value="{price_value}" />
	<br /><br />
	</td>

	<!-- BEGIN quantity_item_tpl -->
	<td valign="top">
	<p class="boxtext">{intl-availability}:</p>
	<input type="text" size="10" name="Quantity" value="{quantity_value}" />
	</td>
	<!-- END quantity_item_tpl -->

	<td valign="top">
	<p class="boxtext">{intl-vat_type}:</p>
	<select name="VATTypeID">

	<!-- BEGIN vat_select_tpl -->
	<option value="{vat_id}" {vat_selected}>{vat_name}</option>
	<!-- END vat_select_tpl -->

	</select>
	</td>

	<td valign="top">
	<p class="boxtext">{intl-shipping_group}:</p>
	<select name="ShippingGroupID">

	<!-- BEGIN shipping_select_tpl -->
	<option value="{shipping_group_id}" {selected}>{shipping_group_name}</option>
	<!-- END shipping_select_tpl -->

	</select>
	</td>

</tr>
<tr>
	<td valign="top">
	<div class="check"><input type="checkbox" name="ShowPrice" {showprice_checked} />&nbsp;<span class="boxtext">{intl-has_price}</span></div>
	</td>
	<td valign="top">
	<div class="check"><input type="checkbox" name="Active" {showproduct_checked} />&nbsp;<span class="boxtext">{intl-active}</span></div>
	</td>
	<td valign="top">
	<div class="check"><input type="checkbox" name="IsHotDeal" {is_hot_deal_checked} />&nbsp;<span class="boxtext">{intl-is_hot_deal}</span></div>
	</td>
	<td valign="top">
	<div class="check"><input type="checkbox" name="Discontinued" {discontinued_checked} /><span class="boxtext">{intl-discontinued}</span></div>
	</td>

</tr>
</table>

<!-- BEGIN price_group_list_tpl -->
<h2>{intl-price_groups}</h2>
<!-- BEGIN price_groups_item_tpl -->
<table cellspacing="0" cellpadding="4" border="0">
<tr>
	<!-- BEGIN price_group_header_item_tpl -->
	<th>
	{price_group_name}:
	</th>
	<!-- END price_group_header_item_tpl -->
</tr>
<tr>
	<!-- BEGIN price_group_item_tpl -->
	<td>
	<input type="text" name="PriceGroup[]" size="8" value="{price_group_value}" />
	<input type="hidden" name="PriceGroupID[]" value="{price_group_id}" />
	</td>
	<!-- END price_group_item_tpl -->
</tr>
</table>
<!-- END price_groups_item_tpl -->
<!-- BEGIN price_groups_no_item_tpl -->
<p>{intl-no_price_groups}</p>
<!-- END price_groups_no_item_tpl -->
<!-- END price_group_list_tpl -->

<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="4" border="0">
<tr>
    <td>
        <select name="ItemToAdd">
	<option value="Image">{intl-pictures}</option>
	<option value="Option">{intl-options}</option>
	<option value="Attribute">{intl-attributes}</option>
<!-- BEGIN module_linker_button_tpl -->
	<option value="ModuleLinker">{intl-links}</option>
<!-- END module_linker_button_tpl -->
        </select>
	    <input class="stdbutton" type="submit" name="AddItem" value="{intl-add_item}" />
    </td>
    <td>
        &nbsp;&nbsp;&nbsp;
    </td>
    <td>
	    <input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />
    </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<div class="divider">
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />
</div>
	</form>

<div class="divider">
	<form method="post" action="/trade/productedit/cancel/">
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</form>
</div>
