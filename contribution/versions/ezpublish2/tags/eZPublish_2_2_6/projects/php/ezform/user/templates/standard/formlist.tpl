<form action="{www_dir}{index}/form/results/" method="post">

<h1>{intl-result_form_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_forms_item_tpl -->
<div>{intl-no_forms_exist}</div>
<!-- END no_forms_item_tpl -->

<!-- BEGIN form_list_tpl -->
<div>{intl-choose_form}</div>

<select name="selectedFormID">
<!-- BEGIN form_item_tpl -->
<option value="{form_id}">{form_name}</option>
<!-- END form_item_tpl -->
</select>
<!-- END form_list_tpl -->

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
</tr>
</table>

</form>
