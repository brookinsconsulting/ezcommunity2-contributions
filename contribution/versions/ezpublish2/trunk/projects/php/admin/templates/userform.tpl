
<h1>Brukeradministrasjon</h1>

<form name="form" action="index.php4?page=useredit.php4" method="post" onsubmit="return validate_form(this)">

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white">
	<b>Brukeridentifikasjon</b>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>
	Brukernavn:<br>
	<input type=text name="UserName" value="{nick_name}"><br></p>

	<p>
	Brukergruppe:<br>
	<select name="UserGroup">
	   {choices}
	</select>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	</td>
</tr>
</table>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="../ezpublish/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white">
	<b>Personopplysninger</b>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>Fornavn:<br>
	<input type="text" name="FirstName" value="{first_name}"><br></p>
	
	<p>Etternavn:<br>
	<input type="text" name="LastName" value="{last_name}"><br></p>
	<p>
	<p>
	<p>
	E-postadresse:<br>
	<input type=text name="EMail" value="{email}"><br></p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	</td>
</tr>
</table>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="../ezpublish/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white">
	<b>Passord</b>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>Skriv inn ønsket passord:<br>
	<input type="password" name="Password1" value="{password}"><br>
	</p>
	<p>
	Gjenta passord:<br>
	<input type="password" name="Password2" value="{password}"><br>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	</td>
</tr>
</table>
<br>

<input type="hidden" name="UserID" value="{user_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="OK">
</form>