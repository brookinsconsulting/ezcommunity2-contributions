<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{form_name}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/form/report/{report_id}/" method="get">
        <select name="ElementID">
<!-- BEGIN element_tpl -->
        <option value="{element_id}" {selected}>{element_name}</option>
<!-- END element_tpl -->
        </select>
	<select name="Operator">
	<option value="substring" {substring_selected}>{intl-substring}</option>
	<option value="starts" {starts_selected}>{intl-starts}</option>
	<option value="equal" {equal_selected}>{intl-equal}</option>
	<option value="not" {not_selected}>{intl-not}</option>
	<option value="greater" {greater_selected}>{intl-greater}</option>
	<option value="less" {less_selected}>{intl-less}</option>
	<option value="between" {between_selected}>{intl-between}</option>
	</select>
	<input class="searchbox" type="text" name="SearchText" size="10" value="{search_text}" />
	<input class="stdbutton" type="submit" name="Search" value="{intl-search}" />
	</form>
	</td>
</tr>
<tr>
<td colspan="2">
{intl-results}: {result_count}
</td>
</table>

<hr noshade="noshade" size="4" />

<!-- {gruk_rapporterte_kommuner} av {gruk_antall_kommuner} kommuner har svart<br /> -->

{form}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
       <td align="right">
       <a href="{printable_url}">{intl-printable}</a>
       </td>
</tr>
</table>
