<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-login_page_title}</h1>

<p>{intl-explanation}</p>

<h2>{intl-user_login}</h2>

<p>{intl-reg_text}</p>

<form method="post" action="/user/login/login/">
<p class="boxtext">{intl-username}:</p>
<input type="text" size="20" name="Username"/>

<p class="boxtext">{intl-password}:</p>
<input type="password" size="20" name="Password"/><br />
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-login}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>
<br />

<h2>{intl-new_user}</h2>

<p>{intl-new_text}</p>

<form method="post" action="/user/user/new/?RedirectURL={redirect_url}">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-newuser}" />

</form>
