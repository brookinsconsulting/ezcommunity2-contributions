<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{form_name}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/form/results/{form_id}/" method="post">
        <select name="ElementID">
<!-- BEGIN element_tpl -->
        <option value="{element_id}" {selected}>{element_name}</option>
<!-- END element_tpl -->
        </select>
	<input class="searchbox" type="text" name="SearchText" size="10" value="{search_text}" />
	<input class="stdbutton" type="submit" name="Search" value="{intl-search}" />
	</form>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN result_tpl -->
<tr>
        <td class="{td_class}">
	<a href="{www_dir}{index}/form/results/{form_id}/{result_id}/">svar</a>
        </td>
</tr>
<!-- END result_tpl -->
</table>
