<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="forum_id" value="{forum_id}>"
<input type="hidden" name="category_id" value="{category_id}">
<table cellspacing="0" cellpadding="4" border="0">
<tr>
<td colspan="5"><h1>Endre forum</h1></td>
</tr>
<tr>
   <td><p>Navn:</p></td>
   <td><p>Beskrivelse:</p></td>
   <td><p>Moderert:</p></td>
   <td><p>Privat:</p></td>
   <td>&nbsp;</td>
</tr>
<tr>
   <td><input type="text" name="name" value="{name}"></td>
   <td><input type="description" name="desription" value="{description}"></td>
   <td><input type="checkbox" name="moderated" {moderated}></td>
   <td><input type="checkbox" name="private" {private}></td>
   <td><input type="submit" name="add" value="Endre"></td>
</tr>
</table>
</form>
<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="category_id" value="{category_id}">
<input type="submit" name="addbox" value="Legg til nytt forum">
</form>