<form method="post" action="/article/categoryedit/{action_value}/{category_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}</p>
<input type="text" size="20" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-place}</p>
<select name="ParentID">
<option value="0">topp</option>

<!-- BEGIN value_tpl -->
<option value="{option_value}">{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-description}</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td'
	<td>
	Avbrytknapp
	</td>
</tr>
</table>

</form>
