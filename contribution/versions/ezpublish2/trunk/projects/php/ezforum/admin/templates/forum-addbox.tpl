<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/forum.php4">
<input type="hidden" name="category_id" value="{category_id}">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
   <td colspan="5"><h1>Legg til nytt forum</h1></td>
</tr>
<tr>
   <td><p>Navn:</p></td>
   <td><p>&nbsp;&nbsp;Moderert:</p></td>
   <td><p>&nbsp;&nbsp;Privat:</p></td>
</tr>
<tr>
   <td><input type="text" name="name"><br><br></td>
   <td>&nbsp;&nbsp;<input type="checkbox" name="moderated"><br><br></td>
   <td>&nbsp;&nbsp;<input type="checkbox" name="private"><br><br></td>
</tr>
<tr>
   <td><p>Beskrivelse:</p></td>
</tr>
<tr>
   <td><input type="description" name="description"></td>
   <td>&nbsp;&nbsp;<input type="submit" name="add" value="Legg til"></td>
</tr>
</table>
</form>
