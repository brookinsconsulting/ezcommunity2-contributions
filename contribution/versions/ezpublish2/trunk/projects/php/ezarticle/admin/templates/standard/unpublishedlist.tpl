<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_category_name}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->

<img src="/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="/article/unpublished/0/">{intl-topcategory}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="/article/unpublished/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<form method="post" action="/article/unpublished/{current_category_id}/" enctype="multipart/form-data">
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/article/unpublished/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
<!-- BEGIN category_edit_tpl -->
	<td width="1%" class="{td_class}">
	<a href="/article/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
	</td>
<!-- END category_edit_tpl -->
</tr>
<!-- END category_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}">
</form>

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-article}:</th>
	<th>{intl-published}:</th>

	<!-- BEGIN absolute_placement_header_tpl -->
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<!-- END absolute_placement_header_tpl -->

	<th colspan="2">&nbsp;</th>
</tr>

<form method="post" action="/article/unpublished/{current_category_id}/" enctype="multipart/form-data">
<!-- BEGIN article_item_tpl -->
<tr>
	<td width="48%" class="{td_class}">
	<a href="/article/articlepreview/{article_id}/">
	{article_name}
	</a>
	</td>
	<td width="48%" class="{td_class}">
	<!-- BEGIN article_is_published_tpl -->
	{intl-is_published}
	<!-- END article_is_published_tpl -->
	<!-- BEGIN article_not_published_tpl -->
	{intl-not_published}
	<!-- END article_not_published_tpl -->
	&nbsp;
	</td>
	<!-- BEGIN absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="/article/unpublished/{category_id}/?MoveDown={article_id}"><img src="/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/unpublished/{category_id}/?MoveUp={article_id}"><img src="/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>
	<!-- END absolute_placement_item_tpl -->
	<!-- BEGIN article_edit_tpl -->
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/edit/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{article_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{article_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ArticleArrayID[]" value="{article_id}">
	</td>
	<!-- END article_edit_tpl -->
</tr>
<!-- END article_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<input type="submit" class="stdbutton" Name="DeleteArticles" value="{intl-deletearticles}">
</form>

<!-- END article_list_tpl -->


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

<!-- BEGIN previous_tpl -->
<a class="path" href="/article/unpublished/{category_id}/?Offset={prev_offset}">&lt;&lt; {intl-prev}</a>
<!-- END previous_tpl -->

     </td>
     <td align="right">

<!-- BEGIN next_tpl -->
<a class="path" href="/article/unpublished/{category_id}/?Offset={next_offset}">{intl-next} &gt;&gt;</a>
<!-- END next_tpl -->

     </td>
</tr>
</table>    


