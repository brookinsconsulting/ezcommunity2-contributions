<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{form_name}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/form/results/{form_id}/" method="get">
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
</table>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/form/results/delete/{form_id}/" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN result_tpl -->
<tr>
        <td class="{td_class}">
	<a href="{www_dir}{index}/form/results/{form_id}/{result_id}/">{title}</a>
        </td>
<!-- BEGIN edit_fields_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/form/results/edit/{form_id}/{result_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{result_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{result_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="DeleteArrayID[]" value="{result_id}" />
	</td>
<!-- END edit_fields_tpl -->
</tr>
<!-- END result_tpl -->
</table>
<!-- BEGIN delete_button_tpl -->
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" />
<!-- END delete_button_tpl -->
</form>
