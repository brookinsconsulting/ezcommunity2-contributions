<form method="post" action="index.php?page={document_root}useredit.php">
<h1>{intl-headline}</h1>

<p>{intl-usergroup}<br>
<select name="UserGroup">
{user_group}
</select></p>

<p>
{intl-username}<br>
<input type="text" name="Login" value="{user_login}">
</p>

<p>
{intl-password}<br>
<input type="password" name="Pwd">
</p>

<p>
{intl-passwordvert}<br>
<input type="password" name="PwdVer">
</p>

<input type="hidden" name="UID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>