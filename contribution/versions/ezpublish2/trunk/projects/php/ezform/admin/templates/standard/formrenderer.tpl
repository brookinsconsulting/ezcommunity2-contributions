<!-- BEGIN user_email_item_tpl -->
{header_line}
<input type="text" {element_size} name="{field_name}" value="{field_value}" />
<!-- END user_email_item_tpl -->

<!-- BEGIN header_tpl -->
<p class="boxtext">{element_name}:</p>
<!-- END header_tpl -->

<!-- BEGIN text_field_item_tpl -->
{header_line}
<input type="text" {element_size} name="{field_name}" value="{field_value}" />
<!-- END text_field_item_tpl -->

<!-- BEGIN text_area_item_tpl -->
{header_line}
<textarea class="box" name="{field_name}" cols="40" rows="5" wrap="soft">{field_value}</textarea>
<!-- END text_area_item_tpl -->

<!-- BEGIN result_item_tpl -->
{header_line}
<div>{field_value}</div>
<!-- END result_item_tpl -->

<!-- BEGIN text_label_item_tpl -->
<span class="boxtext">{element_name}</span>
<!-- END text_label_item_tpl -->

<!-- BEGIN text_header_1_item_tpl -->
<h1>{element_name}</h1>
<!-- END text_header_1_item_tpl -->

<!-- BEGIN text_header_2_item_tpl -->
<h2>{element_name}</h2>
<!-- END text_header_2_item_tpl -->

<!-- BEGIN hr_line_item_tpl -->
<hr noshade="noshade" size="4" />
<!-- END hr_line_item_tpl -->

<!-- BEGIN empty_item_tpl -->

<!-- END empty_item_tpl -->

<!-- BEGIN numerical_float_item_tpl -->
{header_line}
<!-- BEGIN numerical_float_range_tpl -->

<!-- END numerical_float_range_tpl -->
</p>
<input type="text" {element_size} name="{field_name}" value="{field_value}" />
<!-- END numerical_float_item_tpl -->

<!-- BEGIN numerical_integer_item_tpl -->
{header_line}
<!-- BEGIN numerical_integer_range_tpl -->

<!-- END numerical_integer_range_tpl -->
<input type="text" {element_size} name="{field_name}" value="{field_value}" />
<!-- END numerical_integer_item_tpl -->

<!-- BEGIN text_block_item_tpl -->
<div>{text_block}</div>
<!-- END text_block_item_tpl -->

<!-- BEGIN multiple_select_item_tpl -->
<p class="boxtext">{element_name}:</p>
<select name="{field_name}[]" multiple="multiple" >
<!-- BEGIN multiple_select_item_sub_item_tpl -->
<option value="{sub_value}" {selected}>{sub_value}</option>
<!-- END multiple_select_item_sub_item_tpl -->
</select>
<!-- END multiple_select_item_tpl -->

<!-- BEGIN dropdown_item_tpl -->
{header_line}
<select name="{field_name}">
<!-- BEGIN dropdown_item_sub_item_tpl -->
<option value="{sub_value}" {selected}>{sub_value}</option>
<!-- END dropdown_item_sub_item_tpl -->
</select>
<!-- END dropdown_item_tpl -->

<!-- BEGIN radiobox_item_tpl -->
{header_line}
<!-- BEGIN radiobox_item_sub_item_tpl -->
{sub_value}: <input type="radio" value="{sub_value}" name="{field_name}" {selected} />
<!-- END radiobox_item_sub_item_tpl -->
<!-- END radiobox_item_tpl -->

<!-- BEGIN checkbox_item_tpl -->
{header_line}
<!-- BEGIN checkbox_item_sub_item_tpl -->
{sub_value}: <input type="checkbox" value="{sub_value}" name="{field_name}[]" {selected} />
<!-- END checkbox_item_sub_item_tpl -->
<!-- END checkbox_item_tpl -->

<!-- BEGIN table_item_tpl -->
<table cellspacing="3" cellpadding="3" border="1">
<!-- BEGIN table_item_sub_item_tpl -->
<tr>
<!-- BEGIN table_item_cell_tpl -->
<td {colspan} valign="top" >
{element}
</td>
<!-- END table_item_cell_tpl -->
</tr>
<!-- END table_item_sub_item_tpl -->
</table>
<!-- END table_item_tpl -->

<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_value} {error_message}.</div>
<!-- END error_item_tpl -->
<br />
<!-- END error_list_tpl -->

<!-- BEGIN form_list_tpl -->
<!-- BEGIN form_start_tag_tpl -->
<form action="{www_dir}{index}/form/form/process/{form_id}/{section_id}/" method="post">
<h2>{form_name}</h2>
<!-- END form_start_tag_tpl -->
<!-- BEGIN form_edit_start_tag_tpl -->
<form action="{www_dir}{index}/form/results/store/{form_id}/{result_id}/" method="post">
<h2>{form_name}</h2>
<!-- END form_edit_start_tag_tpl -->
<!-- BEGIN form_instructions_tpl -->
<a href="{www_dir}{index}{form_instruction_page}">{form_instruction_page_name}</a>
<!-- END form_instructions_tpl -->
<input type="hidden" name="formID" value="{form_id}" />
<input type="hidden" name="mailSubject" value="{form_name}" />
<input type="hidden" name="redirectTo" value="{form_completed_page}" />
<input type="hidden" name="pageList" value="{page_list}" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN form_item_tpl -->
	<td {colspan} >
	{element}<br /><br />
	</td>
<!-- BEGIN break_tpl -->
</tr>
<tr>
<!-- END break_tpl -->

<!-- END form_item_tpl -->
</tr>
</table>

<!-- BEGIN form_buttons_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
<!-- BEGIN previous_button_tpl -->
	<input class="okbutton" type="submit" name="Previous" value="{intl-previous}" />
<!-- END previous_button_tpl -->
<!-- BEGIN ok_button_tpl -->
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<!-- END ok_button_tpl -->
<!-- BEGIN next_button_tpl -->
	<input class="okbutton" type="submit" name="Next" value="{intl-next}" />
<!-- END next_button_tpl -->

	</td>
</tr>
</table>
<!-- END form_buttons_tpl -->

<!-- BEGIN form_end_tag_tpl -->
</form>
<!-- END form_end_tag_tpl -->

<!-- END form_list_tpl -->

ADMIN REMOVE
