<h1>{intl-category_edit}</h1>

<hr noshade="noshade" size="4">

<form action="{www_dir}{index}/bulkmail/categoryedit/{category_id}" method="post">

	<p class="boxtext">{intl-category}:</p>
	<select name="ParentID">
	<option value="0">{intl-top_level}</option>
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	
	<p class="boxtext">{intl-sort_mode}:</p>
	<select name="SortMode">

	<option {1_selected} value="1">{intl-publishing_date}</option>
	<option {2_selected} value="2">{intl-alphabetic_asc}</option>
	<option {3_selected} value="3">{intl-alphabetic_desc}</option>
	<option {4_selected} value="4">{intl-absolute_placement}</option>

	</select>


<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{category_name}">
<br>
<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" cols="40" rows="3" wrap="soft">{description}</textarea>

<p class="boxtext">{intl-subscribed_usergroups}:</p>
<select multiple size="5" name="SubscriptionGroupsArrayID[]">
<!-- BEGIN subscribe_group_item_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END subscribe_group_item_tpl -->
</select>

<br /><br />
<input type="checkbox" name="PublicList" value="true" {checked} />&nbsp;<span class="boxtext">{intl-public_list}</span><br /><br />

<hr noshade="noshade" size="4">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" Name="Ok" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>