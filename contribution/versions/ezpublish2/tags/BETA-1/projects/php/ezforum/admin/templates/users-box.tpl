<form action="index.php" method="post">
<input type="hidden" name="page" value="{docroot}/admin/users.php">
<input type="hidden" name="UserId" value="{userid}">
<table cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td><p>Kallenavn:</p></td>
    <td><p>Fornavn:</p></td>
    <td><p>Etternavn:</p></td>
    <td><p>E-post:</p></td>
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