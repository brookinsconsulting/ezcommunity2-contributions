<form action="{www_dir}{index}/form/report/edit/" method="post">

<h1>{intl-form_report_list}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>{intl-report_name}:</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN form_report_tpl -->
<tr>
    <td class="{td_class}">
        <a href="{www_dir}{index}/form/report/edit/{report_id}/">{report_name}</a>
    </td>
    <td width="1%" class="{td_class}" align="center">
        <input type="checkbox" name="reportDelete[]" value="{report_id}">
    </td>
</tr>
<!-- END form_report_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="NewReport" value="{intl-new_report}" />
<input class="okbutton" type="submit" name="DeleteSelected" value="{intl-delete}" />
</form>

