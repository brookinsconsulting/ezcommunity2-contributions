<form method="post" action="/trade/typeedit/">

<h1>{intl-type_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>


<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />


<!-- BEGIN attribute_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-attribute_name}:
	</th>
	<th>
	{intl-variable}:
	</th>
	<th>
	{intl-header}:
	</th>
	<th colspan="4">&nbsp;</th>
</tr>
<!-- BEGIN attribute_tpl -->
<tr class="{td_class}">
	<td width="46%">
	<input type="hidden" name="AttributeID[]" value="{attribute_id}" />
	<input type="text" name="AttributeName[]" value="{attribute_name}" />
	</td>
	<td width="25%">
	<input {is_1_selected} type="radio" value="1" name="AttributeType[{counter}]" />
	</td>
	<td width="25%">
	<input {is_2_selected} type="radio" value="2" name="AttributeType[{counter}]" />
	</td>
<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="/trade/typeedit/down/{type_id}/{attribute_id}"><img src="/admin/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="/trade/typeedit/up/{type_id}/{attribute_id}"><img src="/admin/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<br />
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewAttribute" value="{intl-new_attribute}" />

<hr noshade="noshade" size="4" />

<input type="hidden" name="TypeID" value="{type_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" name="Ok" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
