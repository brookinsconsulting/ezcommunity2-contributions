<h1>{intl-category_edit}</h1>

<hr noshade="noshade" size="4">

<form action="/bulkmail/categoryedit/{category_id}" method="post">

<p class="boxtext">{intl-name}:</p>
<input type="text" name="Name" value="{category_name}">
<br>
<p class="boxtext">{intl-description}:</p>
<textarea name="Description" cols="40" rows="5" wrap="soft">{description}</textarea>

<hr noshade="noshade" size="4">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" Name="Ok" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>