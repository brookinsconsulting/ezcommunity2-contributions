
<h1>Arkiv</h1>

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-article}:
	</th>
	<th>
	Kategori:
	</th>
	<th>
	<div align="right">{intl-publishing_date}:</div>
	</th>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/article/articleview/{article_id}/">
	{article_name}
	</a>
	</td>
	<td class="{td_class}">{article_category_name}</td>
	<td align="right" class="{td_class}">
	<span class="small">{article_published}</span>
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->




