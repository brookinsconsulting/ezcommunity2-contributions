<form action="{www_dir}{index}/form/form/numericaledit/{form_id}/{page_id}/{element_id}/{table_id}" method="post">

<h1>{intl-numerical_values}</h1>

<hr noshade="noshade" size="4" />
<span class="error">{min_higher_than_max_error}</span>
<table cellpadding="0" cellspace="0">
<tr>
<td>
<p class="boxtext">{intl-min_value}:</p>
<input type="text" size="8" name="MinValue" value="{min_value}" />
</td>
<td>
<p class="boxtext">{intl-max_value}:</p>
<input type="text" size="8" name="MaxValue" value="{max_value}" />
<input type="hidden" name="From" value="{from_page}" />
</td>
</tr>
</table>
<br />
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

</form>
