<h1>{intl-new_customer}</h1>

<a href="/user/userwithaddress/new/?RedirectURL=/trade/customerlogin/">registrer ny bruker </a>

<h1>{intl-customer_login}</h1>


<form method="post" action="/user/login/login/">
	{intl-username}<br>
	<input type="text" size="10" name="Username"/><br>
	{intl-password}<br>
	<input type="password" size="10" name="Password"/><br>
	<input type="submit" value="OK" />
	<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

