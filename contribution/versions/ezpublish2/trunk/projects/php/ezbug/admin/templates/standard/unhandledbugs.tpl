<h1>{intl-unhandled_bugs}</h1>

<hr noshade="noshade" size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-bug_id}:</th>
	<th>{intl-bug_name}:</th>
	<th>{intl-bug_module_name}:</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN bug_tpl -->
<tr>
	<td class="{td_class}" width="1%">
	{bug_id}
	</td>

	<td class="{td_class}" width="20%">
	{bug_name}
	</td>
	<td class="{td_class}" width="78%">
	{bug_module_name}
	</td>
	<td class="{td_class}" width="1%">
	<a href="/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{bug_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{bug_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
</tr>
<!-- END bug_tpl -->
</table>