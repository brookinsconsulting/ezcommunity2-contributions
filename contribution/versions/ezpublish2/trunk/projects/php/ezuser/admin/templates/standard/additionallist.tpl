<h1>{intl-additional_list}</h1>

<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/user/additional/">
<!-- BEGIN additional_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN additional_item_tpl -->
<tr  class="{td_class}">
	<td width="97%">
	<input type="hidden" name="AdditionalArrayID[]" value="{additional_id}" />
	<input type="text" name="Name[]" value="{additional_name}" />
	</td>

<!-- BEGIN item_move_down_tpl --> 
	<td width="1%"><a href="{www_dir}{index}/user/additional/down/{additional_id}"><img src="{www_dir}/admin/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="{www_dir}{index}/user/additional/up/{additional_id}"><img src="{www_dir}/admin/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->

	<td width="1%">
	<input type="checkbox" name="DeleteArrayID[]" value="{additional_id}" />
	</td>
</tr>
<!-- END additional_item_tpl -->

</table>
<hr noshade="noshade" size="4" />

<!-- END additional_list_tpl -->

<input class="stdbutton" type="submit" name="New" value="{intl-new_additional}" />&nbsp;
<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />&nbsp;
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />
</form>