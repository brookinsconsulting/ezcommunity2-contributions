<form method="post" action="index.php4?page={document_root}passwordedit.php4">
<h1>Forandre passord</h1>

<p>Passord:<br>
<input type="password" name="Pwd"></p>

<p>Passord (verifisering):<br>
<input type="password" name="PwdVer"></p>

<input type="hidden" name="UID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">


</form>