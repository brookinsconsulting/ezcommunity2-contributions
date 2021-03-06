<!-- BEGIN list_page -->

<table width="100%" border="0">
<tr>
	<td valign="bottom">
		<h1>{intl-list_headline}</h1>
	</td>
	<!-- BEGIN search_item_tpl -->
	<td rowspan="2" align="right">
	    <form action="/contact/{type}/search/" method="post">
	    	<input type="text" name="SearchText" size="12" value="{search_form_text}" />
		<input class="stdbutton" type="submit" value="{intl-search}" />
	    </form>
	</td>
	<!-- END search_item_tpl -->
</tr>
</table>

<hr noshade="noshade" size="4" />
<!-- BEGIN list_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	{extra_type_header}
	<th colspan="5">&nbsp;</th>
</tr>
<!-- BEGIN line_item_tpl -->
<tr class="{bg_color}">
<!-- BEGIN item_plain_tpl -->
	<td>
        {item_name}
	</td>
<!-- END item_plain_tpl -->
<!-- BEGIN item_linked_tpl -->
	<td>
        <a href="{item_sort_command}/{item_id}">{item_name}</a>
	</td>
<!-- END item_linked_tpl -->
	{extra_type_item}

<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="{item_down_command}/{item_id}"><img src="/admin/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="{item_up_command}/{item_id}"><img src="/admin/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->

	<td width="1%">
	<a href="{item_edit_command}/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{item_id}-red','','/admin/images/redigerminimrk.gif',1)"><img name="ezc{item_id}-red" border="0" src="/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<a href="{item_delete_command}/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{item_id}-slett','','/admin/images/slettminimrk.gif',1)"><img name="ezc{item_id}-slett" border="0" src="/admin/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>	

</tr>
<!-- END line_item_tpl -->
</table>
<!-- END list_item_tpl -->

<!-- BEGIN type_list_tpl -->
<table>
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a href="/contact/{type}/{action}/{item_previous_index}/{search_text}">{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	{intl-previous}
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	<a href="/contact/{type}/{action}/{item_index}/{search_text}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	<a href="/contact/{type}/{action}/{item_next_index}/{search_text}">{intl-next}</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->


<!-- BEGIN no_line_item_tpl -->
<p class="boxtext">{intl-no_item}</p>
<!-- END no_line_item_tpl -->

<form method="post" action="{item_new_command}/">

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Back" value="{intl-new}">
</form>

<!-- END list_page -->
