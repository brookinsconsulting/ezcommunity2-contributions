<form method="post" action="/user/login/login/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-username}:</p>
<input type="text" size="6" name="Username" />
<p class="boxtext">{intl-password}:</p>
<input type="password" size="6" name="Password" />

<hr noshade="noshade" size="4">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" Name="Forgot" value="{intl-forgot}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" Name="Register" value="{intl-register}">
	</td>
</tr>
</table>

<br />
<hr noshade="noshade" size="4">
<input class="okbutton" type="submit" value="{intl-ok}">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>

<input type="hidden" name="RedirectURL" value="{redirect_url}">
</table>
</form>
