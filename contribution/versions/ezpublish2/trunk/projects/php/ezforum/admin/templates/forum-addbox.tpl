<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="category_id" value="{category_id}">

<h1>Legg til nytt forum</h1>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   <td colspan="3" bgcolor="#3c3c3c"><p class="white"><b>Identifikasjon</b></td>
</tr>
<tr>
   	<td bgcolor="#f0f0f0">
	<br>
	<p>Forumnavn:<br>
	<input type="text" name="name"></p>
	<p>Beskrivelse:<br>
	<input type="description" name="description"></p>
	<p><input type="checkbox" name="moderated">&nbsp;Moderert
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="private">&nbsp;Privat</p>
</tr>
<tr><td bgcolor="#f0f0f0"><br></td></tr>
</table>
<br>
<input type="submit" name="add" value="Legg til">
</form>
