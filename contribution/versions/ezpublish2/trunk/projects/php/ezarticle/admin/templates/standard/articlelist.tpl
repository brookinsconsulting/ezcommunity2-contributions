<h1>Artikkel arkiv</h1>

<hr noshade size="4"/>
/ <a href="/article/archive/parent/0/">Toppnivå</a> / 
<!-- BEGIN path_item_tpl -->
<a href="/article/archive/parent/{category_id}/">{category_name}</a> / 
<!-- END path_item_tpl -->

<hr noshade size="4"/>


<!-- BEGIN category_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Kategori:
	</td>

	<td>
	Beskrivelse:
	</td>

	<td>
	Rediger:
	</td>

	<td>
	Slett:
	</td>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/article/archive/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td class="{td_class}">
	<a href="/article/categoryedit/edit/{category_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/article/categoryedit/delete/{category_id}/">[ slett ]</a>
	</td>	
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade size="4"/>
<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Artikkel:
	</td>
	<td>
	Rediger:
	</td>
	<td>
	Slett:
	</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/article/articlepreview/{article_id}/">
	{article_name}
	</a>
	</td>
	<td class="{td_class}">
	<a href="/article/articleedit/edit/{article_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/article/articleedit/delete/{article_id}/">[ Slett ]</a>
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->




