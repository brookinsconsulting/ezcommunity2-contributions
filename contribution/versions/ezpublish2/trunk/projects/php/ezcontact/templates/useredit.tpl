<form method="post" action="index.php4?prePage={document_root}useredit.php4">
<h2>{head_line}</h2>

<p>Brukergruppe:<br>
<select name="UserGroup">
{user_group}
</select></p>

<p>
Brukernavn:<br>
<input type="text" name="Login" value="{user_login}">
</p>

<p>
Passord:<br>
<input type="password" name="Pwd">
</p>

<p>
Passord (verifisering):<br>
<input type="password" name="PwdVer">
</p>

<input type="hidden" name="UID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>