<form action="/user/photographerlist" method="post">

<h1>{intl-photographer_edit}</h1>

<hr size="4" noshade="noshade" />
<br />
<!-- BEGIN photographer_list_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-photographer_name}:
	</th>
	<th>
	{intl-photographer_email}:
	</th>
</tr>
<!-- BEGIN photographer_item_tpl -->
<tr>
	<td>
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" size="30" name="Name[]" value="{photographer_name}" />
	</td>
	<td>
	<input type="text" size="30" name="EMail[]" value="{photographer_email}" />
	</td>
	<td>
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END photographer_item_tpl -->
</tr>
</table>
<br />
<!-- END photographer_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewPhotographer" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeletePhotographer" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
