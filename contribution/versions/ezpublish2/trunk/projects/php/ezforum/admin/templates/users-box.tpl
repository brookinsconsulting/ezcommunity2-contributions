<form action="index.php4" method="post">
<input type="hidden" name="page" value="{docroot}/admin/users.php4">
<input type="hidden" name="UserId" value="{userid}">
<table cellspacing="1" cellpadding="5" border="0">
  <tr>
    <td>Kallenavn:</td>
    <td>Fornavn:</td>
    <td>Etternavn:</td>
    <td>Epost:</td>
    <td colspan="1">&nbsp;</td>
  </tr>
  <tr>
     <td><input type="text" name="NickName" value="{nick-name}"></td>
     <td><input type="text" name="FirstName" value="{first-name}"></td>
     <td><input type="text" name="LastName" value="{last-name}"></td>
     <td><input type="text" name="Email" value="{email}"></td>
     <td><input type="submit" name="{action}" value="{caption}"></td>
  </tr>
</table>
</form>