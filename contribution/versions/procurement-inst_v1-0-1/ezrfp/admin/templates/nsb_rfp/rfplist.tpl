<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
	<td rowspan="2" align="right">
	</td>
	<td rowspan="2" align="right">
	<form action="{www_dir}{index}/rfp/search/" method="post">
	<input type="text" name="SearchText" class="searchbox" size="10" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>
	<p class="boxtext">{intl-goto}:</p>
	<form action="{www_dir}{index}/rfp/archive/" method="post">
	<select name="GoToCategoryID">

	<!-- BEGIN category_tree_id_tpl -->
	<option value="{category_id}" {selected}>{category_level}{category_name}</option>
	<!-- END category_tree_id_tpl -->
	</select>
	<input type="submit" name="GoTo" class="stdbutton" value="{intl-go}" />
	</form>
	</td>
	<td>
	<p class="boxtext">{intl-show}:</p>
	<form action="{www_dir}{index}/rfp/archive/{current_category_id}/" method="post">

	<select name="rfpSelection" >
	<option value="Published" {published_selected} > {intl-published_rfps} </option>
	<option value="Unpublished" {un_published_selected}> {intl-un_published_rfps} </option>
	<option value="All" {all_selected}> {intl-all_rfps} </option>
	</select>

	<input class="stdbutton" type="submit" name="StoreSelection" value="{intl-ok}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

	<img src="{www_dir}/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />

	<a class="path" href="{www_dir}{index}/rfp/archive/0/">{intl-topcategory}</a>
	<!-- BEGIN path_item_tpl -->

	<img src="{www_dir}/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

	<a class="path" href="{www_dir}{index}/rfp/archive/{category_id}/">{category_name}</a>
	<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<form method="post" action="{www_dir}{index}/rfp/archive/{category_id}/" enctype="multipart/form-data">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="2">{intl-category}:</th>
	<th>{intl-description}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<!-- BEGIN category_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<img src="{www_dir}/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/rfp/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/archive/{current_category_id}/?MoveCategoryDown={category_id}"><img src="{www_dir}/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/archive/{current_category_id}/?MoveCategoryUp={category_id}"><img src="{www_dir}/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>

<!-- BEGIN category_edit_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
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
<input class="stdbutton" type="submit" Name="CopyCategories" value="{intl-copycategories}">

</form>

<!-- END category_list_tpl -->


<!-- BEGIN rfp_list_tpl -->
<form method="post" action="{www_dir}{index}/rfp/archive/{category_id}/" enctype="multipart/form-data">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="2">{intl-rfp}:</th>
	<th>{intl-published}:</th>
	<th>{intl-published_date}:</th>

	<!-- BEGIN absolute_placement_header_tpl -->
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<!-- END absolute_placement_header_tpl -->

	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN rfp_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<img src="{www_dir}/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td width="74%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/rfppreview/{rfp_id}/">{rfp_name}</a>
	</td>
	<td width="1%" class="{td_class}">
	<!-- BEGIN rfp_is_published_tpl -->
	{intl-is_published}
	<!-- END rfp_is_published_tpl -->
	<!-- BEGIN rfp_not_published_tpl -->
	{intl-not_published}
	<!-- END rfp_not_published_tpl -->
	&nbsp;
	</td>
	<td width="20%" class="{td_class}">
	<span class="small">{rfp_published_date}</span>
	</td>
	<!-- BEGIN absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/archive/{current_category_id}/?MoveDown={rfp_id}"><img src="{www_dir}/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/archive/{current_category_id}/?MoveUp={rfp_id}"><img src="{www_dir}/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>
	<!-- END absolute_placement_item_tpl -->
        <!-- BEGIN rfp_edit_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/rfpedit/edit/{rfp_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{rfp_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{rfp_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="RfpArrayID[]" value="{rfp_id}">
	</td>
        <!-- END rfp_edit_tpl -->
</tr>
<!-- END rfp_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<input type="hidden" Name="CurrentCategoryID" value="{current_category_id}" />

<input type="submit" class="stdbutton" Name="DeleteRfps" value="{intl-deleterfps}" />
</form>

<!-- END rfp_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/rfp/archive/{archive_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/rfp/archive/{archive_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/rfp/archive/{archive_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
