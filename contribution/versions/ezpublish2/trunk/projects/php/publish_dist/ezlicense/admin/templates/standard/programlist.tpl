<form action="{www_dir}{index}/license/program/list/" method="post">

<h1>{intl-program_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_licenses_tpl -->
<h2>{intl-no_programs}</h2>

<hr noshade="noshade" size="4" />
<!-- END no_licenses_tpl -->

<!-- BEGIN license_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-id}:</th>
	<th>{intl-name}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN license_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{program_id}
	</td>
	<td width="97%" class="{td_class}">
	<input type="hidden" name="ProgramIDArray[]"   value="{program_id}"   />
	<input type="text"   name="ProgramNameArray[]" value="{program_name}" size="40" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/license/program/edit/{program_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ProgramDeleteIDArray[]" value="{program_id}" />
	</td>
</tr>
<!-- END license_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<!-- END license_list_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewProgram" value="{intl-new_program}" />
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
