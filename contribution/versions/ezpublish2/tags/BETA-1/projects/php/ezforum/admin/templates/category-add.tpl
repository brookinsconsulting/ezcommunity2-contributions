<form action="index.php" method="post">
    <input type="hidden" name="page" value="{docroot}/admin/category.php">
    <h1>Legg til ny kategori</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>Identifikasjon</b></p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>Navn:<br>
	<input type="text" name="Name"></p>
	<p>Beskrivelse:<br>
	<input type="text" name="Description"></p>
	<input type="checkbox" name="Private">&nbsp;Privat
	</td>
</tr>
<tr><td bgcolor="#f0f0f0"><br></td></tr>
</table>

<br>
<input type="submit" name="add" value="Legg til">
</form>