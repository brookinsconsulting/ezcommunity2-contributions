<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="/link/group/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="/link/group/{group_id}/">{group_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4" />
<!-- BEGIN group_list_tpl -->
<form method="post" action="/link/groupedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<h2>{categories}</h2>
	</td>
</tr>
<!-- BEGIN group_item_tpl -->
<tr>
	<td bgcolor="{bg_color}">
	<a href="/link/group/{linkgroup_id}/">{linkgroup_title}</a>
	</td>
	<td bgcolor="{bg_color}">
	{category_description}&nbsp;
	</td>
        <td bgcolor="{bg_color}">
	<!-- BEGIN image_item_tpl -->
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td width="1%" bgcolor="{bg_color}">
	<a href="/link/groupedit/edit/{linkgroup_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ela{linkgroup_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ela{linkgroup_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td bgcolor="{bg_color}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{linkgroup_id}">
	</td>
</tr>
<!-- END group_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}">
</form>
<!-- END group_list_tpl -->


<!-- BEGIN link_list_tpl -->
<form method="post" action="/link/linkedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<h2>{links} - ({link_start}-{link_end}/{link_total})</h2>
</tr>
<!-- BEGIN link_item_tpl -->
<tr>
	<td width="98%" class="{td_class}">
	<b><a href="/link/gotolink/addhit/{link_id}/?Url={link_url}" target="_blank">{link_title}</a></b><br />
	{link_description}
	</td>
        <td class="{td_class}">
	<!-- BEGIN image_item_tpl -->
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td class="{td_class}" width="80" align="right">
	(Hits:&nbsp;{link_hits})
	</td>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="/link/linkedit/edit/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="el{link_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="LinkArrayID[]" value="{link_id}">
	</td>
</tr>
<!-- END link_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteLinks" value="{intl-delete_links}">
</form>
<!-- END link_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/link/group/{group_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="/link/group/{group_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="/link/group/{group_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
