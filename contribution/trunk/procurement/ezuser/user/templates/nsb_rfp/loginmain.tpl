<form method="post" action="{www_dir}{index}/user/login/login/">
	{intl-username}:
	<p><input type="text" size="6" name="Username" style="width:120px" /></p>
	<p>{intl-password}:</p>
	<p><input type="password" size="6" name="Password" style="width:120px" /></p>
	<p><input class="stdbutton" type="submit" value="{intl-ok}"></p>
	<p><a class="menu" href="{www_dir}{index}/user/forgot/">{intl-forgot}</a></p>
	<p><a class="menu" href="{www_dir}{index}{user_edit_url}">{intl-register}</a></p>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

