<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="forum_id" value="{forum_id}>"
<input type="hidden" name="category_id" value="{category_id}">
<table>
<tr>
<td colspan="5">Legg til forum</td>
</tr>
<tr>
   <td>Navn:<td>
   <td>Beskrivelse:</td>
   <td>Moderert:</td>
   <td>Privat:</td>
   <td>&nbsp;</td>
</tr>
<tr>
   <td><input type="text" name="name" value="{name}"><td>
   <td><input type="description" name="desription" value="{description}"></td>
   <td><input type="checkbox" name="moderated" {moderated}></td>
   <td><input type="checkbox" name="private" {private}></td>
   <td><input type="submit" name="add" value="Endre"></td>
</tr>
</table>
</form>