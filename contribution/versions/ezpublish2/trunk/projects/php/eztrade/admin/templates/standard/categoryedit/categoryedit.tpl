<form method="post" action="/trade/categoryedit/{action_value}/">

<h1>Kategoriredigering</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">Navn:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">Kategori:</p>
<select name="ParentID">
<option value="0">topp</option>
<!-- BEGIN value_tpl -->
<option value="{option_value}">{option_name}</option>
<!-- END value_tpl -->
</select>

<p class="boxtext">Beskrivelse:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input type="hidden" name="CategoryID" value="{category_id}" />
	<input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	Avbrytknapp!
	</td>
</tr>
</table>


