<form method="post" action="index.php4?prePage={document_root}useredit.php4">
<h1>Legg til en ny bruker</h1>

Brukergruppe:<br>
<select name="UserGroup">
{user_group}
</select>
<br>

Brukernavn:<br>
<input type="text" name="Login" value="{user_login}"><br>

Passord:<br>
<input type="password" name="Pwd"><br>

Passord (verifisering):<br>
<input type="password" name="PwdVer"><br>

<input type="hidden" name="UID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>