<h1>{intl-edit_page}</h1>

<form action="{www_dir}{index}/form/form/{action_value}/{form_id}/{page_id}/" method="post">
<p class="boxtext">{intl-page_name}:</p>
<input type="text" class="halfbox" size="20" name="PageName" value="{page_name}" />

<br />
<br />

<p class="boxtext">{intl-jump_element}:</p>
<select name="ElementChoiceID[]">
<option value="0">{intl-select_element}</option>
<!-- BEGIN element_choice_tpl -->
    <option value="{element_choice_id}" {selected}>{element_choice_name}</option>
<!-- END element_choice_tpl -->
</select>
<br />
<br />
<!-- BEGIN fixed_value_list_tpl -->
<p class="boxtext">{intl-fixed_values}:</p>
<table cellspacing="2" cellpadding="0" border="0">
<!-- BEGIN fixed_value_item_tpl -->
<tr>
<td width="100">{fixed_value_name}</td>
<!-- BEGIN fixed_value_select_tpl -->
<td>
<!-- BEGIN fixed_value_text_field_tpl -->
    <input type="text" size="4" name="TextFieldFrom[]" value="{from_value}" /> - 
    <input type="text" size="4" name="TextFieldTo[]" value="{to_value}" />
    <input type="hidden" name="ElementRange[]" value="{element_range}">
<!-- END fixed_value_text_field_tpl -->
<select name="FixedPage_{fixed_value_id}[]">
   <!-- BEGIN fixed_value_tpl -->
   <option value="{page_id}" {selected}>{page_name}</option>
   <!-- END fixed_value_tpl -->
</select>
<!-- BEGIN delete_range_tpl -->
&nbsp;<input type="checkbox" name="DeleteRangeArrayID[]" value="{element_range}">
<!-- END delete_range_tpl -->
</td>
<!-- END fixed_value_select_tpl -->
</tr>
<!-- BEGIN add_more_ranges_tpl -->
<tr>
<td colspace="2"><input class="stdbutton" type="submit" name="NewTextFieldRange" value="{intl-add_range}" />
<!-- BEGIN delete_range_button_tpl -->
<input class="stdbutton" type="submit" name="DeleteTextFieldRange" value="{intl-delete_range}" />
<!-- END delete_range_button_tpl -->
</td>
</tr>
<!-- END add_more_ranges_tpl -->
<!-- END fixed_value_item_tpl -->
</table>
<!-- END fixed_value_list_tpl -->


<hr noshade="noshade" size="4" />

{element_list}

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewElement" value="{intl-add_element}" />
<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />
<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected_elements}" />

<hr noshade="noshade" size="4" />
<br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>
</form>