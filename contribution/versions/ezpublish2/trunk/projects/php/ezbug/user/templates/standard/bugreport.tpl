<form method="post" action="/bug/report/">

<h1>{intl-report_a_bug}</h1>

<hr noshade="noshade" size="4">

<br />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-bug_module}:</p>
	<select name="ModuleID">
	<!-- BEGIN module_item_tpl -->
	<option value="{module_id}">{module_name}</option>
	<!-- END module_item_tpl -->
	</select>
	</td>

	<td>
	<p class="boxtext">{intl-bug_category}:</p>
	<select name="CategoryID">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}">{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
</table>

<p class="boxtext">{intl-bug_title}:</p>
<input type="text" size="40" name="Name" />

<p class="boxtext">{intl-bug_description}:</p>
<textarea name="Description" cols="40" rows="8" wrap="soft"></textarea>
<br /><br />


<br />
<hr noshade="noshade" size="4">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>

<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-send_bug_report}">
	</td>
</tr>

<input type="hidden" name="Action" value="{action_value}">

</table>
</form>
