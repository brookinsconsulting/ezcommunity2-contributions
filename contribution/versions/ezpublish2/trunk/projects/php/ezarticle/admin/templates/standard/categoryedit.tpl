<form method="post" action="/article/categoryedit/{action_value}/{category_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>
	</td>	
	<td>
	<input type="checkbox" name="ExcludeFromSearch" {exclude_checked} />
	<span class="boxtext">{intl-exclude_from_search}</span>
	</td>
</tr>
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

	<p class="boxtext">{intl-place}:</p>
	<select name="ParentID">
	<option value="0">{intl-categoryroot}</option>

	<!-- BEGIN value_tpl -->
	<option {selected} value="{option_value}">{option_level}{option_name}</option>
	<!-- END value_tpl -->

	</select>

	</td>
  	<td>
	<p class="boxtext">{intl-sort_mode}:</p>
	<select name="SortMode">

	<option {1_selected} value="1">{intl-publishing_date}</option>
	<option {2_selected} value="2">{intl-alphabetic_asc}</option>
	<option {3_selected} value="3">{intl-alphabetic_desc}</option>
	<option {4_selected} value="4">{intl-absolute_placement}</option>

	</select>

	</td>
</tr>
</table>


<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<!-- {intl-owner_group -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<th class "boxtext" width="100%">{intl-write_groups}:</th>
<!-- <th class "boxtext" width="50%">{intl-recursive}:</th> -->
<tr>
  <td>
    <select name="WriteGroupArray[]" multiple size="7">
    <option value="0" {all_write_selected}>{intl-all}</option>
    <!-- BEGIN category_owner_tpl -->
    <option value="{module_owner_id}" {is_selected}>{module_owner_name}</option>
    <!-- END category_owner_tpl -->
    </select>
  </td>
  <td>
<!--    <input type="checkbox" name="Recursive" /> -->
  </td>
</tr>
</table>
<br />

<p class="boxtext">{intl-groups}:</p>
<select name="GroupArray[]" multiple size="7">
<option value="0" {all_selected}>{intl-all}</option>
<!-- BEGIN group_item_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END group_item_tpl -->
</select>
<br /><br />



<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td>
	<td>
       <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
	
