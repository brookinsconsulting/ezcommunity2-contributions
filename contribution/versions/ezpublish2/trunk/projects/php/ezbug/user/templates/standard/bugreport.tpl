<form method="post" action="/bug/report/update/{bug_id}">

<h1>{intl-report_a_bug}</h1>

<!-- BEGIN all_fields_error_tpl -->
<span class="error">{intl-all_fields_error}</span>
<!-- END all_fields_error_tpl -->

<!-- BEGIN email_error_tpl -->
<span class="error">{intl-email_error}</span>
<!-- END email_error_tpl -->

<hr noshade="noshade" size="4">

<p class="boxtext">{intl-bug_title}:</p>
<input type="text" size="40" name="Name" value="{title_value}"/>
<br /><br />

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-bug_module}:</p>
	<select name="ModuleID">
	<!-- BEGIN module_item_tpl -->
	<option value="{module_id}" {selected}>{module_name}</option>
	<!-- END module_item_tpl -->
	</select>
	</td>

	<td>
	<p class="boxtext">{intl-bug_category}:</p>
	<select name="CategoryID">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}" {selected}>{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
</table>

<!-- BEGIN email_address_tpl -->
<p class="boxtext">{intl-email_address} ({intl-if_you_are_a registered_user_please_log_in}):</p>
<input type="text" size="40" name="Email" />
<!-- END email_address_tpl -->

<p class="boxtext">{intl-bug_description}:</p>
<textarea name="Description" cols="40" rows="8" wrap="soft">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input name="InsertImage" class="stdbutton" type="submit" value="{intl-add_screenshot}" /></td>
  <td>&nbsp; </td>
  <td><input name="InsertFile" class="stdbutton" type="submit" value="{intl-add_patch}" </td>
</tr>
</table>

<hr noshade="noshade" size="4">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="Ok" value="{intl-send_bug_report}">
	</td>
</tr>
</table>
<!-- <input type="text" name="Action" value="{action_value}"> -->

</form>
