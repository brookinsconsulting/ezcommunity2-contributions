<form action="index.php" method="get">
<input type="hidden" name="page" value="{docroot}/admin/forum.php">
<input type="hidden" name="forum_id" value="{forum_id}">
<input type="hidden" name="category_id" value="{category_id}">

<h1>Endre forum</h1>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   <td colspan="3" bgcolor="#3c3c3c"><p class="white"><b>Identifikasjon</b></td>
</tr>
<tr>
   	<td bgcolor="#f0f0f0">
	<br>
	<p>Forumnavn:<br>
	<input type="text" name="name" value="{name}"></p>
	<p>Beskrivelse:<br>
	<input type="description" name="description" value="{description}"></p>
	<p><input type="checkbox" name="moderated" {moderated}>&nbsp;Moderert
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="private" {private}>&nbsp;Privat</p>
</tr>
<tr><td bgcolor="#f0f0f0"><br></td></tr>
</table>

<br>
<table width="100%">
<tr>
	<td><input type="submit" name="modify" value="Endre"></td>
	<td align="right">
	</form>
	<form action="index.php" method="post">
	<input type="hidden" name="page" value="{docroot}/admin/forum.php">
	<input type="hidden" name="category_id" value="{category_id}">
	<input type="submit" name="addbox" value="Legg til nytt forum">
	</td>
</tr>
</table>
</form>
<br>