<form method="post" action="/trade/categoryedit/{action_value}/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-category}:</p>
<select name="ParentID">
<option value="0">topp</option>
<!-- BEGIN value_tpl -->
<option value="{option_value}">{option_name}</option>
<!-- END value_tpl -->
</select>

<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input type="hidden" name="CategoryID" value="{category_id}" />
	<input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
