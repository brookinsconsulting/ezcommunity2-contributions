<h1>{intl-item_edit}</h1>

<form method="post" action="/datamanager/itemedit/">

<hr size="4" noshade="noshade" />

<p class="boxtext">{intl-item_type}:</p>
<select name="NewItemTypeID" >
<!-- BEGIN item_type_option_tpl -->
<option {selected} value="{type_id}">{type_name}</option>
<!-- END item_type_option_tpl -->
</select>

<input class="stdbutton" type="submit" name="SelectType" value="{intl-select}" />

<p class="boxtext">{intl-item_owner_group}:</p>
<select name="ItemOwnerGroupID" >
<option value="0">{intl-everybody}</option>
<!-- BEGIN item_owner_group_tpl -->
<option {selected} value="{group_id}">{group_name}</option>
<!-- END item_owner_group_tpl -->
</select>



<p class="boxtext">{intl-item_name}:</p>
<input class="box" type="text" name="ItemName" value="{item_name}" />


<!-- BEGIN item_value_list_tpl -->

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-item_name}:</p>
	</th>
</tr>
<!-- BEGIN item_value_tpl -->
<tr>
	<td class="{td_class}">	
	<b>{data_type_name}</b><br />
	<input type="hidden" name="ItemValueTypeID[{data_type_id}]" value="{item_value_type_id}" />

	<!-- BEGIN text_item_tpl -->
	<textarea class="box" name="ItemValueArray[{data_type_id}]" cols="40" rows="5" wrap="soft">{data_type_value}</textarea>
	<!-- END text_item_tpl -->

	<!-- BEGIN relation_item_tpl -->
	<select name="ItemValueArray[{data_type_id}]" >
	<!-- BEGIN relation_item_value_tpl -->
	<option {selected} value="{relation_type_id}">{relation_type_name}</option>
	<!-- END relation_item_value_tpl -->
	</select>
	<!-- END relation_item_tpl -->

	</td>
</tr>
<!-- END item_value_tpl -->

</table>

<hr size="4" noshade="noshade" />

<input type="hidden" name="ItemID" value="{item_id}" />
<input type="hidden" name="ItemTypeID" value="{item_type_id}" />

<input class="okbutton" type="submit" name="Store" value="{intl-ok}" />

<!-- END item_value_list_tpl -->



</form>