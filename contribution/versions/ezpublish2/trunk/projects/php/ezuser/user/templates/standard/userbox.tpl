<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menuhead" bgcolor="#c82828">{intl-userinfo}</td>
</tr>

<tr>
	<td>
	<form method="post" action="/user/login/logout/">
	<b>Innlogget bruker:</b>
	{first_name} {last_name}
	<input type="submit" value="Logg ut" /><br>
        <a href="/user/user/edit/{user_id}/">{intl-change_user_info}</a>
        </form>
	</td>
</tr>
</table>