<form method="post" action="/user/login/login/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="1">

<br />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p>{intl-username}:</p>
	<input type="text" size="6" name="Username"/>
	</td>
</tr>
<tr>
	<td>
	<p>{intl-password}:</p>
	<input type="password" size="6" name="Password" />
	</td>
</tr>
</table>
<br />
<hr noshade="noshade" size="1">

<table width="60%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a href="/user/forgot/?RedirectURL={redirect_url}">{intl-forgot}</a>
	</td>
	<td>&nbsp;</td>
	<td>
	<a href="/user/user/new/?RedirectURL={redirect_url}">{intl-register}</a>
	</td>
</tr>
</table>

<hr noshade="noshade" size="1">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="&nbsp;{intl-ok}&nbsp;">
	</td>
</tr>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</table>
</form>
