<form action="{www_dir}{index}/license/program/list/" method="post">

<h1>{intl-license_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_licenses_tpl -->
<h2>{intl-no_licenses}</h2>

<hr noshade="noshade" size="4" />
<!-- END no_licenses_tpl -->

<!-- BEGIN license_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-license_id}:</th>
	<th>{intl-license_number}:</th>
	<th>{intl-license_owner}:</th>
	<th>{intl-license_start}:</th>
	<th>{intl-license_expiry}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN license_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{license_id}
	</td>
	<td width="27%" class="{td_class}">
	{license_number}
	</td>
	<td width="50%" class="{td_class}">
	{license_owner}
	</td>
	<td width="10%" class="{td_class}">
	{license_start}
	</td>
	<td width="10%" class="{td_class}">
	{license_expiry}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/license/license/edit/{license_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="LicenseArrayID[]" value="{license_id}">
	</td>
</tr>
<!-- END license_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<!-- END license_list_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewLicense" value="{intl-new_license}" />
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
	</td>
</tr>
</table>



<hr noshade="noshade" size="4" />

</form>

<form action="{www_dir}{index}/license/license/list/" method="post">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
</tr>
</table>

</form>
