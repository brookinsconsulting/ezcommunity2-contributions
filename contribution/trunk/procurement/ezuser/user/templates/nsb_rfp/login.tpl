<form method="post" action="{www_dir}{index}/user/login/login/">

<h1>{intl-head_line}</h1>

<br />

<div align="center">

<p class="boxtext">{intl-username}:</p>
<input tabindex="1" type="text" size="10" name="Username"/>
<br />

<p class="boxtext">{intl-password}:</p>
<input tabindex="2" type="password" size="10" name="Password" />
<br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
        <td class="menuspacer">&nbsp;</td>
</tr>

<tr>
        <td>
        <input tabindex="3" class="okbutton" type="submit" value="{intl-ok}">
        </td>
</tr>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</table>

<br />
<br />

<!-- BEGIN buttons_tpl -->
<table width="80%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<input tabindex="4" class="stdbutton" type="submit" Name="Forgot" value="{intl-forgot}">
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td align="center">
	<input tabindex="5" class="stdbutton" type="submit" Name="Register" value="{intl-register}">
	</td>
</tr>
</table>

<!-- END buttons_tpl -->
</div>

</form>
