<form action="{www_dir}{index}/user/titlelist" method="post">

<h1>{intl-title_edit}</h1>

<hr size="4" noshade="noshade" />

<!-- BEGIN title_list_tpl -->

<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>
	{intl-title_name}:
	</th>
</tr>
<!-- BEGIN title_item_tpl -->
<tr>
	<td class="{td_class}">
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" class="halfbox" size="20" name="Name[]" value="{title_name}" />
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END title_item_tpl -->
</tr>
</table>
<!-- END title_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewTitle" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteTitle" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
