
<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-search} - ( {query_text} )</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="/bug/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />


<!-- BEGIN bug_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-bug}:</th>
	<th>{intl-status}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-is_closed}:</th>
</tr>

<!-- BEGIN bug_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/bugview/{bug_id}/">
	{bug_name}&nbsp;
	</a>
	</td>

	<td class="{td_class}">
	{bug_status}&nbsp;
	</td>

	<td class="{td_class}">
	{bug_priority}&nbsp;
	</td>

	<td class="{td_class}">
	<!-- BEGIN bug_is_closed_tpl -->
	{intl-is_closed}&nbsp;
	<!-- END bug_is_closed_tpl -->

	<!-- BEGIN bug_is_open_tpl -->
	{intl-is_open}&nbsp;
	<!-- END bug_is_open_tpl -->

	</td>
</tr>
<!-- END bug_item_tpl -->

</table>
<!-- END bug_list_tpl -->
