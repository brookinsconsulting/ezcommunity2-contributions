<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />


<h2>{intl-customer_login}</h2>


<form method="post" action="/user/login/login/">
<p class="boxtext">{intl-username}</p>
<input type="text" size="20" name="Username"/>

<p class="boxtext">{intl-password}</p>
<input type="password" size="20" name="Password"/><br />

<input class="okbutton" type="submit" value="OK" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

<h2>{intl-new_customer}</h2>

<form method="post" action="/user/userwithaddress/new/?RedirectURL=/trade/customerlogin/">
<input class="okbutton" class="stdbutton" type="submit" value="Registrer ny bruker" />

<a href="/user/userwithaddress/new/?RedirectURL=/trade/customerlogin/">registrer ny bruker </a>
</form>

