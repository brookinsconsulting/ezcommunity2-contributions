<h1>Registrer ny bruker</h1>

<!-- BEGIN required_fields_error_tpl -->
<h3 class="error" >{intl-required_fields_error}</h3>
<!-- END required_fields_error_tpl -->

<!-- BEGIN user_exists_error_tpl -->
<h3 class="error" >{intl-user_exists_error}</h3>
<!-- END user_exists_error_tpl -->

<!-- BEGIN password_error_tpl -->
<h3 class="error" >{intl-password_error}</h3>
<!-- END password_error_tpl -->


<form method="post" action="/user/userwithaddress/{action_value}/{user_id}/">

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
	{intl-street1}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Street1" value="{street1_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-street2}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Street2" value="{street2_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-zip}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Zip" value="{zip_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-place}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Place" value="{place_value}"/>
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

<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>



