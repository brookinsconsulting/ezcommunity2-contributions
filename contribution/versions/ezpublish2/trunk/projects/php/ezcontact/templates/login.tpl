<form method="post" action="{document_root}login.php4">
{login_msg}
Brukernavn:<br>
<input type="text" name="Login" value="{login}"><br>
Passord:<br>
<input type="hidden" name="TryLogin" value="true">
<input type="password" name="Pwd" value=""><br>
<input type="submit" name="searchButton" value="logg inn">
</form>