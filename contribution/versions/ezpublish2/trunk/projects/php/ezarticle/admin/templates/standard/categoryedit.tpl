<h1>{intl-headline}</h1>


<form method="post" action="/article/categoryedit/{action_value}/{category_id}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-name}
	</th>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<th>
	{intl-place}
	</th>
</tr>
<tr>
	<td>
	<select name="ParentID">
	<option value="0">topp</option>
	<!-- BEGIN value_tpl -->
	<option value="{option_value}">{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
</tr>
<tr>
	<th>
	{intl-description}
	</th>
</tr>
<tr>
	<td>
    <textarea rows="5" cols="20" name="Description">{description_value}</textarea>
	</td>
</tr>
<tr>
	<td>
    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input type="submit" value="OK" />
	</td>
</tr>
</table>
</form>


