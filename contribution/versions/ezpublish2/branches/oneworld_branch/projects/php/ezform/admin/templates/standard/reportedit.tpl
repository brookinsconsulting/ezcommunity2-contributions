<form action="{www_dir}{index}/form/report/store/{report_id}" method="post">

<h1>{intl-form_report_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-report_name}:</p>
<input type="text" class="box" size="40" name="reportName" value="{report_name}" />
<p class="boxtext">{intl-form}:</p>
<select name="form">
<!-- BEGIN form_element_tpl -->
<option value="{form_id}" {selected}>{form_name}</option>
<!-- END form_element_tpl -->
</select>

<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="Setup" value="{intl-setup}" /><br />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Update" value="{intl-update}" />
