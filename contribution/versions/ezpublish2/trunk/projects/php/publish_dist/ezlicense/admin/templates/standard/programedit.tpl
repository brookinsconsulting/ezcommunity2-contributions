<form action="{www_dir}{index}/license/program/edit/{program_id}/" method="post">

<h1>{intl-program_edit}: {program_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_versions_tpl -->
<h2>{intl-no_versions}</h2>

<hr noshade="noshade" size="4" />
<!-- END no_versions_tpl -->

<!-- BEGIN duplicate_key_error_tpl -->
<h2>{intl-duplicate_key}</h2>
{intl-duplicate_key_info}
<hr noshade="noshade" size="4" />
<!-- END duplicate_key_error_tpl -->

<!-- BEGIN version_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-id}:</th>
	<th>{intl-major}:</th>
	<th>{intl-minor}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN version_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{version_id}
	<input type="hidden" name="VersionIDArray[]"   value="{version_id}"   />
	</td>
	<td width="10%" class="{td_class}">
	<input type="text"   name="VersionMajorArray[]" value="{version_major}" size="4" />
	</td>
	<td width="10%" class="{td_class}">
	<input type="text"   name="VersionMinorArray[]" value="{version_minor}" size="4" />
	</td>
	<td width="78%" class="{td_class}" align="right">
	<a href="{www_dir}{index}/license/version/edit/{version_id}/?RedirectURL=/license/program/edit/{program_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="VersionDeleteIDArray[]" value="{version_id}" />
	</td>
</tr>
<!-- END version_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<!-- END version_list_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewVersion" value="{intl-new_version}" />
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
