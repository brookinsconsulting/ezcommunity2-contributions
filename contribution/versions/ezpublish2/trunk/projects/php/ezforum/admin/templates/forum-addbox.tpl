<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="category_id" value="{category_id}">
<table width="50%" cellspacing="0" cellpadding="4" border="0">
<tr>
   <td colspan="5"><h1>Legg til forum</h1></td>
</tr>
<tr>
   <td><p>Navn:</p></td>
   <td><p>Beskrivelse:</p></td>
   <td><p>Moderert:</p></td>
   <td><p>Privat:</p></td>
   <td>&nbsp;</td>
</tr>
<tr>
   <td><input type="text" name="name"></td>
   <td><input type="description" name="description"></td>
   <td><input type="checkbox" name="moderated"></td>
   <td><input type="checkbox" name="private"></td>
   <td><input type="submit" name="add" value="Legg til"></td>
</tr>
</table>
</form>
