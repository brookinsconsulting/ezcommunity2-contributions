<form action=index.php?page=user-delete.php method=post>

<h1>Sletting av bruker</h1>

Er du sikker på at du vil slette denne brukeren?<br>

<table cellspacing=0 cellpadding=8 border=0>
<tr><td><b>Navn:</b></td><td>{name}</td></tr>
<tr><td><b>Brukernavn:</b></td><td>{username}</td></tr>
</table>
<input type=hidden name=id value="{id}">
<input type=submit value="Slett">
</form>
