<form method="post" action="/user/useredit/{action_value}/{user_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-firstname}</p>
<input type="text" size="20" name="FirstName" value="{first_name_value}"/>

<p class="boxtext">{intl-lastname}</p>
<input type="text" size="20" name="LastName" value="{last_name_value}"/>

<p class="boxtext">{intl-email}</p>
<input type="text" size="20" name="Email" value="{email_value}"/>

<p class="boxtext">{intl-login}</p>
<input type="text" size="20" name="Login" value="{login_value}"/>

<p class="boxtext">{intl-groups}</p>
<select name="GroupArray[]" multiple size="5">
{group_item}
</select>

<p class="boxtext">{intl-password}</p>
<input type="password" size="20" name="Password" value="{password_value}"/>

<p class="boxtext">{intl-verifypassword}</p>
<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>

<br></br>

<hr noshade size="4"/>

<input type="hidden" name="UserID" value="{user_id}" />
<input class="okbutton" type="submit" value="OK" />

</form>

