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
<img src="/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>

<hr noshade="noshade" size="4" />

<form method="post" action="/forum/forumedit/edit/" enctype="multipart/form-data">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN forum_item_tpl -->
<tr>
    <td class={td_class}>
	<a href="/forum/messagelist/{forum_id}/">{forum_name}</a>
	</td>
	<td class={td_class}>
	{forum_description}
	</td>
    <td width="1%" class={td_class}>
	<a href="/forum/forumedit/edit/{forum_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eff{forum_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eff{forum_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
    <td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ForumArrayID[]" value="{forum_id}">
    </td>
</tr>
<!-- END forum_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteForums" value="{intl-deleteforums}">
</form>
