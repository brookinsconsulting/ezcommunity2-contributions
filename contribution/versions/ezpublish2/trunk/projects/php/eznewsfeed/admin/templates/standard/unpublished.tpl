<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_category_name}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="/newsfeed/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<tr>
	<td>{current_category_description}</td>
</tr>
</table>

<form method="post" action="/newsfeed/unpublished/{current_category_id}">

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" /> 

<a class="path" href="/newsfeed/unpublished/0/">{intl-top_category}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="/newsfeed/unpublished/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</td>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/newsfeed/unpublished/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="/newsfeed/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/newsfeed/categoryedit/delete/{category_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="ezac{category_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>

<!-- END category_list_tpl -->

<!-- BEGIN news_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-news}:</th>
	<th>{intl-news_origin}:</th>
	<th>{intl-news_date}:</th>
	<th>{intl-publish}:</th>
	<th>{intl-delete}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN news_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/newsfeed/news/edit/{news_id}/">
	{news_name}
	</a>
	</td>

	<td class="{td_class}">
	{news_origin}&nbsp;
	</td>
	<td class="{td_class}">
	{news_date}&nbsp;
	</td>

	<td class="{td_class}">
	<input type="checkbox" name="NewsPublishIDArray[]" value="{news_id}" />
	</td>

	<td class="{td_class}">
	<input type="checkbox" name="NewsDeleteIDArray[]" value="{news_id}" />
	</td>

	<td width="1%" class="{td_class}">
	<a href="/newsfeed/news/edit/{news_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{news_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/newsfeed/unpublished/{current_category_id}/delete/{news_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="ezaa{news_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END news_item_tpl -->

</table>
<!-- END news_list_tpl -->


<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td width="50%">
<!-- BEGIN previous_tpl -->
<a href="/newsfeed/unpublished/{current_category_id}/?Offset={prev_offset}">&lt;&lt;&nbsp;{intl-prev}</a>
<!-- END previous_tpl -->

     </td>
     <td width="50%" align="right">

<!-- BEGIN next_tpl -->
<a href="/newsfeed/unpublished/{current_category_id}/?Offset={next_offset}">{intl-next}&nbsp;&gt;&gt;</a>
<!-- END next_tpl -->
     </td>
</tr>
</table>


<hr noshade="noshade" size="4" />

<input type="submit" class="okbutton" name="Publish" value="{intl-publish_marked}" />

<input type="submit" class="okbutton" name="Delete" value="{intl-delete_marked}" />

<input type="hidden" name="CategoryID" value="{current_category_id}" />

</form>


