<form action="/user/authorlist" method="post">

<h1>{intl-author_edit}</h1>

<hr size="4" noshade="noshade" />
<br />
<!-- BEGIN author_list_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-author_name}:
	</th>
	<th>
	{intl-author_email}:
	</th>
</tr>
<!-- BEGIN author_item_tpl -->
<tr>
	<td>
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" size="30" name="Name[]" value="{author_name}" />
	</td>
	<td>
	<input type="text" size="30" name="EMail[]" value="{author_email}" />
	</td>
	<td>
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END author_item_tpl -->
</tr>
</table>
<br />
<!-- END author_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewAuthor" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteAuthor" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
