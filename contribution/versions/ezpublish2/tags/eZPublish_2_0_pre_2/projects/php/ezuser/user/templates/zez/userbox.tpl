<form method="post" action="/user/login/logout/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
        <td class="menuhead" bgcolor="#c82828">{intl-userinfo}</td>
</tr>
<tr>
        <td>
        <p class="menutext">{intl-userlogin}:<br />
        <span class="small">{first_name} {last_name}</span></p>
        </td>
</tr>
<tr>
        <td class="menutext">
        <input type="submit" value="{intl-logout}" />
        </td>
</tr>
<tr>
        <td class="menuspacer">&nbsp;</td>
</tr>
<tr>
        <td class="menutext">
    <img src="/images/dot.gif" width="12" height="10"><a class="menu" href="/user/user/edit/{user_id}/">{intl-change_user_info}</a>  
        </td>
</tr>
</table>

</form>