<form method="post" action="/todo/todoedit/{action_value}/{todo_id}">
<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<br>

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-name}</p>
	<input type="text" size="30" name="Name" value="{name}">
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-owner}</p>
	{first_name} {last_name}
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">{intl-user}</p>
	<select name="UserID">
	<!-- BEGIN user_item_tpl -->
	<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
	<!-- END user_item_tpl -->
	</select>
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class=boxtext>{intl-due}</p>
	<span class="small">Klokkeslett:</span> <input size="2" type="text" name="Hour" value="{hour}">:<input size="2" type="text" name="Minute" value="{hour}">&nbsp;
	<span class="small">Dato:</span> <input size="2" type="text" name="Mnd" value="{mnd}">-<input size="2" type="text" name="Day" value="{day}">&nbsp;
	<span class="small">År:</span> <input size="4" type="text" name="Year" value="2000">&nbsp;
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">{intl-date}</p>{current_date}
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-cat}</p>
	<select name="CategoryID">
	<!-- BEGIN category_select_tpl -->
	<option {is_selected} value="{category_id}">{category_name}</option>
	<!-- END category_select_tpl -->
	</select>
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">{intl-pri}</p>
	<select name="PriorityID">
	<!-- BEGIN priority_select_tpl -->
	<option {is_selected} value="{priority_id}">{priority_name}</option>
	<!-- END priority_select_tpl -->
	</select>
	<br><br>
	</td>
</tr>
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-desc}</p>
	<textarea swap="soft" cols="30" rows="10" name="Text">{text}</textarea>
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">Done:</p>
	<div class="check"><input type="checkbox" name="Done" {done}>&nbsp;</div>
	</td>
	<td class="br">
	<p class="boxtext">Visning:</p>
	<div class="check"><input type="checkbox" name="Permission" {permission}>&nbsp;</div>
	</td>
</tr>
</table>

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}">

</form>
