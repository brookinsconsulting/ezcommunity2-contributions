<h1>{intl-site_map}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<!-- BEGIN category_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
	<a href="/article/{option_value}">{option_name}</a>
	<a href="/article/categoryedit/edit/{category_id}"><img name="edit" border="0" src="/admin/images/ezpublish/redigermini.gif" width="16" height="16" align="top"></a><br />
	</td>
</tr>
<!-- END category_value_tpl -->

<!-- BEGIN article_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" />&nbsp;
	<a href="/article/{option_value}">{option_name}</a>
	<a href="/article/articleedit/edit/{article_id}"><img name="edit" border="0" src="/admin/images/ezpublish/redigermini.gif" width="16" height="16" align="top"></a><br />

	</td>
</tr>
<!-- END article_value_tpl -->

<!-- BEGIN value_tpl -->

<!-- END value_tpl -->
</table>
<br />