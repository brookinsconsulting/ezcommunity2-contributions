<tr bgcolor="#808080"> 
	<td>
        <p class="smallhead">
		{intl-userinfo}
        </p>
    </td>
</tr>

<tr>
	<td>
	<form method="post" action="/user/login/login/">
	<p class="boxtext"><span class="small">{intl-username}</span></p>
	<input type="text" size="8" name="Username"/>
	<p class="boxtext"><span class="small">{intl-password}</span></p>
	<input type="password" size="8" name="Password"/>
	<br />
	<input type="submit" value="{intl-ok}" />
	</form>
	<a href="/user/user/new/">{intl-register}</a><br /><br />
	<a href="/user/forgot">{intl-forgot}</a>
	
	</td>
</tr>
