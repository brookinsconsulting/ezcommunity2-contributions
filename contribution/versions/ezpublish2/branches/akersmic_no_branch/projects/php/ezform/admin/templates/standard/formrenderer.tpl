<!-- BEGIN text_field_item_tpl -->
<input type="text" size="{element_size}" name="{field_name}" value="{field_value}" class="formfield">
<!-- END text_field_item_tpl -->

<!-- BEGIN text_area_item_tpl -->
<textarea class="box" name="{field_name}" cols="40" rows="5" wrap="soft" class="formfield">{field_value}</textarea>
<!-- END text_area_item_tpl -->

<!-- BEGIN multiple_select_item_tpl -->
<select name="{field_name}[]" multiple="multiple" class="formfield">
<!-- BEGIN multiple_select_item_sub_item_tpl -->
<option value="{sub_value}">{sub_value}</option>
<!-- END multiple_select_item_sub_item_tpl -->
</select>
<!-- END multiple_select_item_tpl -->

<!-- BEGIN dropdown_item_tpl -->
<select name="{field_name}" class="formfield">
<!-- BEGIN dropdown_item_sub_item_tpl -->
<option value="{sub_value}" class="formfield">{sub_value}</option>
<!-- END dropdown_item_sub_item_tpl -->
</select>
<!-- END dropdown_item_tpl -->

<!-- BEGIN radiobox_item_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
	<!-- BEGIN radiobox_item_sub_item_tpl -->
	<tr>
		<td><input type="radio" value="{sub_value}" name="{field_name}"></td>
		<td class="normal">{sub_value}</td>
	</tr>
	<!-- END radiobox_item_sub_item_tpl -->
</table>
<!-- END radiobox_item_tpl -->

<!-- BEGIN checkbox_item_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
	<!-- BEGIN checkbox_item_sub_item_tpl -->
	<tr>
		<td><input type="checkbox" value="{sub_value}" name="{field_name}[]"></td>
		<td class="normal">{sub_value}</td>
	</tr>
<!-- END checkbox_item_sub_item_tpl -->
</table>
<!-- END checkbox_item_tpl -->


<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_value} {error_message}.</div>
<!-- END error_item_tpl -->
<br>
<!-- END error_list_tpl -->

<!-- BEGIN form_list_tpl -->
<!-- BEGIN form_start_tag_tpl -->
<br>
<form action="{www_dir}{index}/form/form/process/{form_id}/{section_id}/" method="post">
<div class="article_h1">{form_name}</div>
<!-- END form_start_tag_tpl -->
<!-- BEGIN form_instructions_tpl -->
<a href="{www_dir}{index}{form_instruction_page}">{intl-instructions}</a>
<!-- END form_instructions_tpl -->
<input type="hidden" name="formID" value="{form_id}">
<input type="hidden" name="mailSubject" value="{form_name}">
<input type="hidden" name="redirectTo" value="{form_completed_page}">
<!-- BEGIN form_sender_tpl -->
<div class="normal">{intl-form_sender}</div>
<input type="text" class="box" name="formSender" value="{form_sender_value}">
<br><br>

<!-- END form_sender_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<!-- BEGIN form_item_tpl -->
		<td {colspan} class="normal">
			<br>
			<b>{element_name}</b>
			<br>
			{element}
		</td>
	<!-- BEGIN break_tpl -->
	</tr>
	<tr>
	<!-- END break_tpl -->
	
	<!-- END form_item_tpl -->
	</tr>
</table>

<!-- BEGIN form_buttons_tpl -->
<input type="image" name="OK" src="/sitedesign/am/img/ok.gif" class="okbutton" vspace="10">
<!-- END form_buttons_tpl -->

<!-- BEGIN form_end_tag_tpl -->
</form>
<!-- END form_end_tag_tpl -->

<!-- END form_list_tpl -->

ADMIN REMOVE