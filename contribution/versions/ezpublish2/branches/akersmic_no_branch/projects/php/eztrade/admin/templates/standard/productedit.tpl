<form method="post" action="{www_dir}{index}/trade/{url_action}/{action_value}/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-product_number}:</p>
<input class="box" type="text" size="40" name="ProductNumber" value="{product_nr_value}"/>
<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<th>{intl-category}:</th>
	<th>Hovedkategori:</th>
	<th>Fjern kategori:</th>
	<!-- BEGIN selected_category_item_tpl -->
	<tr> 
	    <td width="30%">
<b>/</b>
<!-- BEGIN path_item_tpl -->
{category_name} <b>/</b>
<!-- END path_item_tpl -->
	    <input type="hidden" name="SelectedCategories[]" value="{category_id}" />
	    <input type="hidden" name="CategoryArray[]" value="{category_id}" />
	    </td>
	    <td width="30%">
	    <input type="radio" {is_checked} name="MainCategoryID" value="{category_id}" />
	    </td>
	    <td width="40%">
	    <input type="checkbox"  name="RemoveCategory[]" value="{category_id}" />
	    </td>
	</tr>
	<!-- END selected_category_item_tpl -->
	<tr>
	<tr><td>&nbsp;</td></tr>
	<td>
	    <input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />
	</td>
	<td>&nbsp;</td>
	<td>
	    <input class="stdbutton" type="submit" name="RemoveCategories" value="Fjern kategorier" />
	</td>
	</tr>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
</table>

<p class="boxtext">{intl-keywords}:</p>
<input class="box" name="Keywords" size="40" value="{keywords_value}" />
<br /><br />

<p class="boxtext">{intl-intro}:</p>
<textarea class="box" rows="5" cols="40" name="Contents[]" wrap="soft">{brief_value}</textarea>
<br /><br />

<p class="boxtext">{intl-description}:</p>
<textarea class="box" rows="15" cols="40" name="Contents[]" wrap="soft">{description_value}</textarea>
<br /><br />

<p class="boxtext">{intl-external_link}:</p>
http://<input type="text" size="36" name="ExternalLink" value="{external_link}"/><br />
<br />

	


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">
	<!-- BEGIN quantity_item_tpl -->
	<p class="boxtext">{intl-availability}:</p>
	<input type="text" size="10" name="Quantity" value="{quantity_value}" />
	<!-- END quantity_item_tpl -->&nbsp;
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
    <td valign="top" colspan="2">&nbsp;</td>
</tr>
<tr>
    <td valign="top">
	    <p class="boxtext">{intl-expiry_time}:</p>
	    <input type="text" size="3" name="Expiry" value="{expiry_value}" />&nbsp;<span class="boxtext">{intl-days}</span><br />
    </td>
    <td valign="top">
        <div class="check">
        <input type="checkbox" name="IsHotDeal" {is_hot_deal_checked} />&nbsp;<span class="boxtext">{intl-is_hot_deal}</span>
        <input type="checkbox" name="Discontinued" {discontinued_checked} /><span class="boxtext">{intl-discontinued}</span>
        </div>
    </td>
</tr>
<tr>
    <td valign="top" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td valign="top">
	<p class="boxtext">{intl-vat_type}:</p>
	<select name="VATTypeID">

	<!-- BEGIN vat_select_tpl -->
	<option value="{vat_id}" {vat_selected}>{vat_name}</option>
	<!-- END vat_select_tpl -->

	</select>
	</td>
    <td>
        <input type="radio" name="IncludesVAT" {include_vat} value="true" /> <span class="boxtext">{intl-includes_vat}</span>
        <input type="radio" name="IncludesVAT" {exclude_vat} value="false" /> <span class="boxtext">{intl-excludes_vat}</span>
    </td>
</tr>
<tr>
        <!-- BEGIN price_range_tpl -->
	<td valign="top">
	{intl-0_is_unlimited}
	<p class="boxtext">{intl-min_price}:</p>
	<input type="text" size="10" name="MinPrice" value="{price_min}" />
	<p class="boxtext">{intl-max_price}:</p>
	<input type="text" size="10" name="MaxPrice" value="{price_max}" />
	<br /><br />
	</td>
        <!-- END price_range_tpl -->

        <!-- BEGIN normal_price_tpl -->
	<td valign="top">
	<p class="boxtext">{intl-price}:</p>
	<input type="text" size="10" name="Price" value="{price_value}" />
	<br /><br />
	</td>
        <!-- END normal_price_tpl -->
    <td>
        <div class="check">
        <input type="checkbox" name="Active" {showproduct_checked} />&nbsp;<span class="boxtext">{intl-active}</span>
        <input type="checkbox" name="ShowPrice" {showprice_checked} />&nbsp;<span class="boxtext">{intl-has_price}</span>
        <input type="checkbox" name="Forhond" {forhond_checked} />&nbsp;<span class="boxtext">Forhåndsbestilling</span>
        </div>
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

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
        <select name="ItemToAdd">
	<option value="Image">{intl-pictures}</option>
	<option value="Option">{intl-options}</option>
	<option value="Attribute">{intl-attributes}</option>
<!-- BEGIN module_linker_button_tpl -->
	<option value="ModuleLinker">{intl-links}</option>
<!-- END module_linker_button_tpl -->
        <option value="Form">{intl-forms}</option>
        </select>
    </td>
    <td>
	    <input class="stdbutton" type="submit" name="AddItem" value="{intl-add_item}" />
    </td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>
	    <input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />
    </td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<table cellspacing="0" cellpadding="0" border="0">
<tr valign="center">
<td>
    <input class="okbutton" type="submit" value="{intl-ok}" />
    <input type="hidden" name="ProductID" value="{product_id}" />
	</form>
</td>
<td>
&nbsp;
</td>
<td>
    <form method="post" action="{www_dir}{index}/trade/productedit/cancel/">
        <input type="hidden" name="ProductID" value="{product_id}" />
        <input class="okbutton" type="submit" value="{intl-cancel}" />
    </form>
</td></tr>
</table>
