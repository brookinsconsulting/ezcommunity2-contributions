<form method="post" action="/user/useredit/{action_value}/{user_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<h3 class="error">{error}</h3>
<tr>
	<td width="1%">
	<p class="boxtext">{intl-firstname}:</p>
	<input type="text" size="20" name="FirstName" value="{first_name_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-lastname}:</p>
	<input type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-email}:</p>
	<input type="text" size="40" name="Email" value="{email_value}"/>
	</td>
</tr>

<tr>
	<td rowspan="2" valign="top">
	<p class="boxtext">{intl-signature}:</p>
	<textarea name="Signature" cols="40" rows="9" wrap="soft">{signature}</textarea>
	</td>

	<td>
	<p class="boxtext">{intl-login}:</p>
	<input type="text" {read_only} size="20" name="Login" value="{login_value}"/>
	</td>
</tr>

<tr>
	<td>
	<p class="boxtext">{intl-groups}:</p>
	<select name="GroupArray[]" multiple size="5">
	<!-- BEGIN group_item_tpl -->
	<option value="{group_id}" {selected}>{group_name}</option>
	<!-- END group_item_tpl -->
	</select>
	</td>
</tr>

<br />

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
<tr>
	<td>
	<br />
	<p class="boxtext">{intl-infosubscription}:</p>
	<input {info_subscription} type="checkbox" name="InfoSubscription" />
	</td>
</tr>

</table>
	
<br />

<hr noshade size="4"/>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top>
	<input type="hidden" name="UserID" value="{user_id}" />
	<input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>
	</td>
</table>
</form>

