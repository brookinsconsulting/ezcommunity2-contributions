<form method="post" action="/user/passwordchange/{action_value}/">

<h1>{intl-headline}</h1>

<hr noshade size="4">

<p class="error">{error_msg}</p>

<h2>{first_name} {last_name}</h2>

<p class="boxtext">{intl-oldpassword}</p>
<input type="password" size="20" name="OldPassword"/>

<p class="boxtext">{intl-newpassword}</p>
<input type="password" size="20" name="NewPassword"/>

<p class="boxtext">{intl-verifypassword}</p>
<input type="password" size="20" name="VerifyPassword"/>
<br /><br />

<hr noshade size="4">

<input class="okbutton" type="submit" value="{intl-ok}" />

</form>

