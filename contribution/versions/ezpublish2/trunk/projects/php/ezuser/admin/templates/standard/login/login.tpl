<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-error_login}</h3>
<!-- END error_message_tpl -->

<form method="post" action="/user/login/login/">
	{intl-username}<br>
	<input type="text" size="10" name="Username"/><br>
	{intl-password}<br>
	<input type="password" size="10" name="Password"/><br>
	<input type="submit" value="OK" />
</form>

