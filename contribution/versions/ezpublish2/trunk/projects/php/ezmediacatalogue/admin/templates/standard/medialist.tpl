<form method="post" action="/mediacatalogue/media/new/" enctype="multipart/form-data">

<input type="hidden" name="CategoryID" value="{main_category_id}">

<h1>{intl-media}</h1>

<!-- BEGIN current_category_tpl -->

<!-- END current_category_tpl -->

<hr noshade="noshade" size="4" />

<img src="/media/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="/mediacatalogue/media/list/0/">{intl-media_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="/media/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="/mediacatalogue/media/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >

<!-- BEGIN category_tpl -->
<tr>
        <!-- BEGIN category_read_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/mediacatalogue/media/list/{category_id}/"><img src="/media/folder.gif" alt="" width="16" height="16" border="0" /></a>
	</td>
	<td class="{td_class}" width="38%">
	<a href="/mediacatalogue/media/list/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}" width="59%">
	<span class="small">{category_description}</span>
	</td>
        <!-- END category_read_tpl -->
        <!-- BEGIN category_write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/mediacatalogue/category/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapMedia('ezim{category_id}-red','','/media/redigerminimrk.gif',1)"><img name="ezim{category_id}-red" border="0" src="/media/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}" />
	</td>
        <!-- END category_write_tpl -->
</tr>
<!-- END category_tpl -->

</table>
<!-- END category_list_tpl -->

<!-- BEGIN media_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN media_tpl -->
<tr>
	<!-- BEGIN read_tpl -->
	<td class="{td_class}" width="1%">
	<img src="/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%">
	<a href="/mediacatalogue/mediaview/{media_id}/?RefererURL=/mediacatalogue/media/list/{main_category_id}/">{media_name}</a>
	</td>
	<td class="{td_class}" width="56%">
	<span class="small">{media_description}</span>
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/mediacatalogue/media/edit/{media_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="MediaArrayID[]" value="{file_id}">
	</td>

	<!-- END write_tpl -->
<tr>
<!-- END media_tpl -->
</table>

<!-- END media_list_tpl -->

<!-- BEGIN write_menu_tpl -->
<table width="100%">
<tr>
<!-- BEGIN previous_tpl -->
<td align="left">
<a class="path" href="/mediacatalogue/media/list/{main_category_id}/{prev-offset}/">&lt;&lt;&nbsp;{intl-previous}</a>
</td>
<!-- END previous_tpl -->
<!-- BEGIN next_tpl -->
<td align="right">
<a class="path" href="/mediacatalogue/media/list/{main_category_id}/{next-offset}/">{intl-next}&nbsp;&gt;&gt;</a>
</td>
<!-- END next_tpl -->
</tr>
</table>

<!-- BEGIN default_delete_tpl -->

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>

    <!-- BEGIN delete_categories_button_tpl -->
    <td>
	<input class="stdbutton" type="submit" name="DeleteCategories" value="{intl-delete_categories}">
	</td>
	<td>&nbsp;</td>
	<!-- END delete_categories_button_tpl -->

    <!-- BEGIN delete_media_button_tpl -->
    <td>
	<input class="stdbutton" type="submit" name="DeleteMedia" value="{intl-delete_media}">
	</td>
    <!-- END delete_media_button_tpl -->

</tr>
</table>
<!-- END default_delete_tpl -->

<!-- BEGIN default_new_tpl -->

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<input class="stdbutton" type="submit" name="NewMedia" value="{intl-new_media}">
	</td>
	<td>&nbsp;</td>
    <td>
	<input class="stdbutton" type="submit" name="NewCategory" value="{intl-new_category}">
	</td>
</tr>
</table>
<!-- END default_new_tpl -->
<!-- END write_menu_tpl -->
</form>
