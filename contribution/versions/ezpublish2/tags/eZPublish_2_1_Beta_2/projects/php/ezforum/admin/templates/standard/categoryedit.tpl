<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form action="/forum/categoryedit/{action_value}/{category_id}/" method="post">
<input type="hidden" name="page" value="{docroot}/admin/category.php">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input type="text" size="20" value="{category_name}" name="Name">

<p class="boxtext">{intl-description}:</p>
<input type="text" size="40" value="{category_description}" name="Description">

<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="add" value="{intl-ok}">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/forum/categorylist/">
	<input class="okbutton" type="submit" value="{intl-cancel}">
	</form>
	</td>
</tr>
</table>


