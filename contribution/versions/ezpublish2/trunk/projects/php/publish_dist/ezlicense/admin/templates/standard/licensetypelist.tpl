<form action="{www_dir}{index}/license/licensetype/list/" method="post">

<h1>{intl-license_type_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_licenses_tpl -->
<h2>{intl-no_license_types}</h2>

<hr noshade="noshade" size="4" />
<!-- END no_licenses_tpl -->

<!-- BEGIN license_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-id}:</th>
	<th>{intl-name}:</th>
	<th>{intl-translated_name}:</th>
	<!-- th>&nbsp;</th>
	<th>&nbsp;</th -->
</tr>

<!-- BEGIN license_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{license_type_id}
	</td>
	<td width="47%" class="{td_class}">
	<!-- input type="hidden" name="LicenseTypeIDArray[]"   value="{license_type_id}"   />
	<input type="text"   name="LicenseTypeNameArray[]" value="{license_type_name}" / -->
    {license_type_name}
	</td>
	<td width="50%" class="{td_class}">
	{license_type_translated_name}
	</td>
	<!-- td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/license/licensetype/edit/{license_type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="LicenseTypeDeleteIDArray[]" value="{license_type_id}" />
	</td -->
</tr>
<!-- END license_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<!-- END license_list_tpl -->

<!-- table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewLicenseType" value="{intl-new_license_type}" />
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
	</td>
</tr>
</table -->



<!-- hr noshade="noshade" size="4" / -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<!-- input class="okbutton" type="submit" name="Store" value="{intl-store}" / -->
	</td>
</tr>
</table>

</form>
