<form action="{www_dir}{index}/form/form/tableedit/{form_id}/{table_id}/" method="post">

<h1>{intl-table_edit}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN row_list_tpl -->
{intl-col}: {col}

{element_list}
<!-- END row_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />
<br/>

<hr noshade="noshade" size="4" />

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
