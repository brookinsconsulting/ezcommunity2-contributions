<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<img src="/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/forum/categorylist/">{intl-forum-main}</a> 

<hr noshade="noshade" size="4" />

<form method="post" action="/forum/categoryedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <th colspan="2">{intl-name}:</th>
        <th>{intl-desc}:</th>
        <th colspan="2">&nbsp;</td>
    </tr>
<!-- BEGIN category_item_tpl -->
<tr bgcolor="{color}">
	<td width="1%" class="{td_class}">
	<img src="/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
    <td class={td_class}>
    <a href="/forum/forumlist/{category_id}/">{category_name}</a>
    </td>
    <td class={td_class}>
	{category_description}
    </td>
    
    <td width="1%" class={td_class}>
        <a href="/forum/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ef{category_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ef{category_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
    </td>
     <td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
     </td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-delete_categories}">

</form>

