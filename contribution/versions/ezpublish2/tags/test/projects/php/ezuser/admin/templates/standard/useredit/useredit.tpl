<h1>{head_line}</h1>


<form method="post" action="/user/useredit/{action_value}/{user_id}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{intl-login}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Login" value="{login_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-email}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Email" value="{email_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-firstname}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="FirstName" value="{first_name_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-lastname}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>

<tr>
	<td>
	<select name="GroupArray[]" multiple size="5">
	{group_item}
	</select>
	</td>
</tr>
<tr>
	<td>
	{intl-password}
	</td>
</tr>
<tr>
	<td>
	<input type="password" size="20" name="Password" value="{password_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-verifypassword}
	</td>
</tr>
<tr>
	<td>
	<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>

<tr>
	<td>
	<input type="hidden" name="UserID" value="{user_id}" />
	<input type="submit" value="OK" />
	</td>
</tr>
<td>
</table>
</form>

