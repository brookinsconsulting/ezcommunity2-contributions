<form action="{www_dir}{index}/form/report/" method="post">

<h1>{intl-report_form_list}</h1>

<hr noshade="noshade" size="4" />

<div>{intl-choose_report}</div>

<select name="selectedReportID">
<!-- BEGIN report_item_tpl -->
<option value="{report_id}">{report_name}</option>
<!-- END report_item_tpl -->
</select>

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
