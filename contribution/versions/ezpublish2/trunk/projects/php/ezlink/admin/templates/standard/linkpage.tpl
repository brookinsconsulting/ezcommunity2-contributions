<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

{printpath}

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="3">{intl-category}</th>
</tr>

<!-- BEGIN group_list_tpl -->
<tr>
	<td bgcolor="{bg_color}">
	<a href="/link/group/{linkgroup_id}/">{linkgroup_title}</a> &nbsp;({total_links}, {new_links} nye)
	</td>

	<td width="1%" bgcolor="{bg_color}">
	<a href="/link/groupedit/edit/{linkgroup_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ela{linkgroup_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ela{linkgroup_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td width="1%" bgcolor="{bg_color}">
	<a href="#" onClick="verify( '{intl-delete}', '/link/groupedit/delete/{linkgroup_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ela{linkgroup_id}-slett','','/admin/images/{site_style}/slettminimrk.gif',1)"><img name="ela{linkgroup_id}-slett" border="0" src="/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top" border="0" alt="Delete" /></a>
	</td>

</tr>
<!-- END group_list_tpl -->

</table>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="3">{intl-link}</th>
</tr>

<!-- BEGIN link_list_tpl -->
<tr>
	<td class="{td_class}">
	<a class="path" href="/link/linkedit/edit/{link_id}/">{link_title}</a><br />
	{link_description}
	</td>
	<td class="{td_class}" width="80" align="right">
	(Hits:&nbsp;{link_hits})
	</td>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="/link/linkedit/edit/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="el{link_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="#" onClick="verify( '{intl-deletelink}', '/link/linkedit/delete/{link_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-slett','','/admin/images/{site_style}/slettminimrk.gif',1)"><img name="el{link_id}-slett" border="0" src="/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top" border="0" alt="Delete" /></a>
	</td>
</tr>
<!-- END link_list_tpl -->

</table>
