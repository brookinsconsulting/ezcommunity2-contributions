<form method="post" action="{www_dir}{index}/calendar/{action_type}/{calendar_id}">

<h1>{intl-edit_calendar}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{calendar_name}"/>

<br />

<p class="boxtext">{intl-administrators}:</p>
<select name="Groups[]" multiple>
<!-- BEGIN group_element_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END group_element_tpl -->
</select>

<hr noshade="noshade" size="4" />

<input type="hidden" name="CalendarID" value="{type_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>