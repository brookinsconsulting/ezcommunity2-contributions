<form method="post" action="/user/login/login/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th>{intl-head_line}</th>
</tr>
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
<tr>
	<td>
	<input type="submit" value="{intl-ok}">
	</td>
</tr>
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
<tr>
	<td class="menutext">
	<a class="menu" href="/user/forgot/">{intl-forgot}</a>
	</td>
</tr>
<tr>
	<td class="menutext">
	<a class="menu" href="/user/userwithaddress/new/">{intl-register}
	</td>
</tr>
<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>

<input type="hidden" name="RedirectURL" value="{redirect_url}">

</form>

