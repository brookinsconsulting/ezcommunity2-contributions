<form method="post" action="/user/userwithaddress/{action_value}/{user_id}/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN required_fields_error_tpl -->
<h3 class="error" >{intl-required_fields_error}</h3>
<!-- END required_fields_error_tpl -->

<!-- BEGIN user_exists_error_tpl -->
<h3 class="error" >{intl-user_exists_error}</h3>
<!-- END user_exists_error_tpl -->

<!-- BEGIN password_error_tpl -->
<h3 class="error" >{intl-password_error}</h3>
<!-- END password_error_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-firstname}:</p>
	<input type="text" size="20" name="FirstName" value="{first_name_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-lastname}:</p>
	<input type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>
</table>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-login}:</p>
	<input {readonly} type="text" size="20" name="Login" value="{login_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-email}:</p>
	<input type="text" size="20" name="Email" value="{email_value}"/>
	</td>
</tr>
</table>

<p class="boxtext">Gate:</p>
<input type="text" size="20" name="Street1" value="{street1_value}"/><br />
<input type="text" size="20" name="Street2" value="{street2_value}"/><br />
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">Postnr:</p>
	<input type="text" size="6" name="Zip" value="{zip_value}"/>
	</td>
	<td>&nbsp;</td>
	<td>
	<p class="boxtext">{intl-place}:</p>
	<input type="text" size="20" name="Place" value="{place_value}"/>
	</td>
</tr>
</table>

<!-- BEGIN country_tpl -->
<p class="boxtext">{intl-country}:</p>
<select name="CountryID" size="5">
<!-- BEGIN country_option_tpl -->
<option {is_selected} value="{country_id}">{country_name}</option>
<!-- END country_option_tpl -->
</select>
<!-- END country_tpl -->



<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-password}:</p>
	<input type="password" size="20" name="Password" value="{password_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-verifypassword}:</p>
	<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>
</table>

<br />

<hr noshade="noshade" size="4" />

<input type="hidden" name="AddressID" value="{address_id}">
<input type="hidden" name="UserID" value="{user_id}" />
<input class="okbutton" type="submit" value="OK" />

<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>



