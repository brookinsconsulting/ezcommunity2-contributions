<form method="post" action="{document_root}login.php4">
<h1>eZ contact</h1>
<h3>{login_msg}</h3>
<p>Brukernavn:<br>
<input type="text" name="Login" value="{login}"><br></p>
<p>Passord:<br>
<input type="hidden" name="TryLogin" value="true">
<input type="password" name="Pwd" value=""><br></p>
<input type="submit" name="searchButton" value="logg inn">
</form>