<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="category_id" value="{category_id}">
<table width="50%">
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
   <td><input type="text" name="name"><td>
   <td><input type="description" name="description"></td>
   <td><input type="checkbox" name="moderated"></td>
   <td><input type="checkbox" name="private"></td>
   <td><input type="submit" name="add" value="Legg til"></td>
</tr>
</table>
</form>
