<tr bgcolor="#aaaaaa"> 
	<td>
        <p class="smallhead">
	Bruker informasjon
        </p>
        </td>
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