<form method="post" action="/mediacatalogue/typeedit/">

<h1>{intl-type_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{name_value}"/>

<br /><br />


<!-- BEGIN attribute_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-attribute_name}:
	</th>
	<th colspan="4">&nbsp;</th>
</tr>
<!-- BEGIN attribute_tpl -->
<tr class="{td_class}">
	<td width="1%">
	<input type="hidden" name="AttributeID[]" value="{attribute_id}" />
	<input type="text" name="AttributeName[]" value="{attribute_name}" />
	</td>
<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="/media/typeedit/down/{type_id}/{attribute_id}"><img src="/admin/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="/media/typeedit/up/{type_id}/{attribute_id}"><img src="/admin/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->
	<td align="right">
	<input type="checkbox" name="DeleteAttributes[]" value="{attribute_id}" />
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<br />
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewAttribute" value="{intl-new_attribute}" />&nbsp;
<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />&nbsp;
<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />

<hr noshade="noshade" size="4" />

<input type="hidden" name="TypeID" value="{type_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<input class="okbutton" name="Ok" type="submit" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
