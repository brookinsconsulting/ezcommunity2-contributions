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