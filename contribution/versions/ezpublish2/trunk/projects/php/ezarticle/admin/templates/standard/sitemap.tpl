<h1>{intl-site_map}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" >
<!-- BEGIN category_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" /> &nbsp;
	<a href="/article/{option_value}">{option_name}</a><br />
	</td>
</tr>
<!-- END category_value_tpl -->

<!-- BEGIN article_value_tpl -->
<tr>
	<td>
	{option_level}
	<img src="/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" /> &nbsp;
	<a href="/article/{option_value}">{option_name}</a><br />
	</td>
</tr>
<!-- END article_value_tpl -->

<!-- BEGIN value_tpl -->

<!-- END value_tpl -->
</tr>
</table>