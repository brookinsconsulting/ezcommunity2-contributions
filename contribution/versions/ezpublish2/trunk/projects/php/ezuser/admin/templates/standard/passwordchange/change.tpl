<form method="post" action="/user/passwordchange/{action_value}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<h3>Bytte passord for</h3>
{first_name} {last_name}
<br><br><br>
<tr>
	<td>
	{intl-oldpassword}
	</td>
</tr>
<tr>
	<td>
	<input type="password" size="20" name="OldPassword"/>
	</td>
</tr>

<tr>
	<td>
	{intl-newpassword}
	</td>
</tr>
<tr>
	<td>
	<input type="password" size="20" name="NewPassword"/>
	</td>
</tr>

<tr>
	<td>
	{intl-verifypassword}
	</td>
</tr>
<tr>
	<td>
	<input type="password" size="20" name="VerifyPassword"/>
	</td>
</tr>

<tr>
	<td>
	<input type="submit" value="OK" />
	</td>
</tr>
<td>
</table>
</form>

