<form method="post" action="index.php4?page={document_root}passwordedit.php4">
<h1>Forandre passord</h1>

Passord:<br>
<input type="password" name="Pwd"><br>

Passord (verifisering):<br>
<input type="password" name="PwdVer"><br>

<input type="hidden" name="UID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>