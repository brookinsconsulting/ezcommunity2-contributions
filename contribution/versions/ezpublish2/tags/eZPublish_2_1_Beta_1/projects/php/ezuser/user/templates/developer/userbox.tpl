<form method="post" action="/user/login/logout/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menuhead" bgcolor="#c0c0c0">
	{intl-userinfo}	
	</td>
</tr>
<tr>
	<td>
	<p>{intl-userlogin}:<br />
	<span class="small">{first_name} {last_name}</span></p>
	</td>
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
	<td class="menutext">
	<a class="menu" href="{user_edit_url}/{user_id}/">{intl-change_user_info}</a>  
	</td>
</tr>
</table>

</form>
