	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="menuhead" bgcolor="#323296">Kunde</td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<form method="post" action="/user/login/logout/">
		<p class="smalltitle">Innlogget kunde:</p>
		<span class="user">{first_name} {last_name}</span>
		</td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<input type="submit" value="{intl-logout}" />
		</td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<img src="/images/path-arrow.gif" width="15" height="10" /><a class="menu" href="{user_edit_url}/{user_id}/">Endre kundeinfo</a>  
		</form>
		</td>
	</tr>
	</table>
	