<h1>{intl-site_search}: {search_text}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN search_type_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-search_in_module}: {module_name}
	<br />
	{intl-search_count}: {search_count}
	</th>
</tr>
<!-- BEGIN search_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{search_link}"><img src="{icon_src}" width="16" height="16" alt="" border="0" />{search_name}</a><br />
	</td>
</tr>
<!-- END search_item_tpl -->
<tr>
	<td>
	{intl-full_search}: <a href="{search_more_link}">{intl-click_here}</a><br />
	</tr>
</table>
<!-- END search_type_tpl -->
