<form action="{www_dir}{index}/license/version/edit/{version_id}/" method="post">

<h1>{intl-version_edit}: {program_name} {intl-version} {version_major} {version_minor}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_costs_tpl -->
<h2>{intl-no_types}</h2>

<hr noshade="noshade" size="4" />
<!-- END no_costs_tpl -->

<!-- BEGIN duplicate_key_error_tpl -->
<h2>{intl-duplicate_key}</h2>
{intl-duplicate_key_cost}
<hr noshade="noshade" size="4" />
<!-- END duplicate_key_error_tpl -->

<!-- BEGIN cost_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-id}:</th>
	<th>{intl-license_type}:</th>
	<th>{intl-price_unit}:</th>
	<th>{intl-price_unit_non_professional}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN cost_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{cost_id}
	<input type="hidden" name="CostIDArray[]" value="{cost_id}"   />
	</td>
	<td width="10%" class="{td_class}">
    
    <select name="LicenseTypeArray[]">
    <option value="0">{intl-select_license_type}</option>
    <!-- BEGIN license_type_item_tpl -->
    <option {selected} value="{license_type_id}">{license_type_name}</option>
    <!-- END license_type_item_tpl -->
    </select>
	</td>
	<td width="20%" class="{td_class}">
	<input type="text"   name="CostValueArray[]" value="{cost_value}" size="10" />
	</td>
	<td width="20%" class="{td_class}">
	<input type="text"   name="CostNonProfValueArray[]" value="{cost_value_non_prof}" size="10" />
	</td>
	<td width="58%" class="{td_class}" align="right">
	<!--a href="{www_dir}{index}/license/version/edit/{cost_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a-->
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CostDeleteIDArray[]" value="{cost_id}" />
	</td>
</tr>
<!-- END cost_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<!-- END cost_list_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewCost" value="{intl-new_license_type}" />
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
</tr>
</table>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="Store" value="{intl-store}" />
	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td valign="top">
    <input type="hidden" name="RedirectURL" value="{RedirectURL}" />
	<input class="okbutton" type="submit" name="Back" value="{intl-back}" />
	</td>
</tr>
</table>

</form>
