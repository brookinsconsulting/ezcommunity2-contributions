
<h1>Brukeradministrasjon</h1>

<form name="form" action="index.php4?page=useredit.php4" method="post" onsubmit="return validate_form(this)">

<p>Fornavn:<br>
<input type="text" name="FirstName" value="{first_name}"><br></p>

<p>Etternavn:<br>
<input type="text" name="LastName" value="{last_name}"><br></p>
<p>
<p>
Brukernavn:<br>
<input type=text name="UserName" value="{nick_name}"><br></p>
<p>
<p>
Email:<br>
<input type=text name="EMail" value="{email}"><br></p>
<p>
<p>
Brukergruppe:<br>
<select name="UserGroup">
   {choices}
</select>
</p>

<p>Passord:<br>
<input type="password" name="Password1" value="{password}"><br>
</p>
<p>
Gjenta passord:<br>
<input type="password" name="Password2" value="{password}"><br>
</p>
<input type="hidden" name="UserID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="OK">
</form>