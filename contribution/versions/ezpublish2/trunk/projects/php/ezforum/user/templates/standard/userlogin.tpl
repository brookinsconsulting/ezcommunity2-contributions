<h1>{intl-new_user}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-user_login}</h2>

<p>{intl-reg_text}</p>

<form method="post" action="/user/login/login/">
<p class="boxtext">{intl-username}</p>
<input type="text" size="20" name="Username"/>

<p class="boxtext">{intl-password}</p>
<input type="password" size="20" name="Password"/><br />
<br />

<input class="okbutton" type="submit" value="Logg inn" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>
<br />

<h2>{intl-new_user}</h2>

<p>{intl-new_text}</p>

<form method="post" action="/user/user/new/?RedirectURL={redirect_url}">
<input class="okbutton" class="stdbutton" type="submit" value="Ny bruker" />

</form>