<h1>{intl-new_user}</h1>

<a href="/user/user/new/?RedirectURL={redirect_url}">registrer ny bruker </a>

<h1>{intl-user_login}</h1>


<form method="post" action="/user/login/login/">
        {intl-username}<br>
        <input type="text" size="10" name="Username"/><br>
        {intl-password}<br>
        <input type="password" size="10" name="Password"/><br>
        <input type="submit" value="OK" />
        <input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>