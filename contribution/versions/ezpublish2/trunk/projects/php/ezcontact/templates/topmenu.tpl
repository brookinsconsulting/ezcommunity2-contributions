<table width="100%">
<tr>
	<td valign="top">
| <a href="{document_root}logout.php4"><img src="{document_root}images/loggut.gif" border="0" alt="Logg ut"></a>
| <a href="index.php4?page={document_root}contactlist.php4"><img src="{document_root}images/liste.gif" border="0" alt="Liste"></a>
| <a href="index.php4?page={document_root}noteslist.php4"><img src="{document_root}images/huskelapp.gif" border="0" alt="Notater"></a>
| <a href="index.php4?page={document_root}personedit.php4"><img src="{document_root}images/person.gif" border="0" alt="Ny kontaktperson"></a>
| <a href="index.php4?page={document_root}companyedit.php4"><img src="{document_root}images/firma.gif" border="0" alt="Nytt kontaktfirma"></a>
| bruker: <b>{current_user}</b> |

  </td>
  <td align="right"  valign="top">
<form method="post" action="index.php4" >
<select name="page" size="1">
<option value="{document_root}phonetypelist.php4">Telefontyper</option>
<option value="{document_root}addresstypelist.php4">Adressetyper</option>
<option value="{document_root}userlist.php4">Brukere</option>
<option value="{document_root}usergrouplist.php4">Brukergrupper</option>
<option value="{document_root}persontypelist.php4">Persontyper</option>
<option value="{document_root}companytypelist.php4">Firmatyper</option>
</select>
<input type="submit" value="go">
</form>

  </td>
</tr>
</table>
<hr noshade side="3" color="#000000">


