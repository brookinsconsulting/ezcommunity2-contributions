	
<h1>{intl-unhandled_bugs}</h1>

<hr noshade="noshade" size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-bug_id}
	</th>
	<th>
	{intl-bug_name}
	</th>
	<th>
	{intl-edit_bug}
	</th>
</tr>
<!-- BEGIN bug_tpl -->
<tr>
	<td class="{td_class}">
	{bug_id}
	</td>

	<td class="{td_class}">
	{bug_name}
	</td>
	<td class="{td_class}">
	<a href="/bug/edit/edit/{bug_id}/">edit</a><br />
	</td>
</tr>
<!-- END bug_tpl -->
</table>