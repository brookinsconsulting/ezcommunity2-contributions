<h1>{intl-url_edit}</h1>


<hr size="4" noshade="noshade" />
<form action="/urltranslator/urledit" method="post">

<!-- BEGIN url_list_tpl -->

<table width="100%" border="0">
<tr>
	<th>
	{intl-source_url}
	</th>
	<th>
	{intl-dest_url}
	</th>
</tr>
<!-- BEGIN url_item_tpl -->
<tr>
	<td>
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" size="40" name="SourceURL[]" value="{source_url}" />
	</td>
	<td>
	<input type="text" size="40" name="DestURL[]" value="{dest_url}" />
	</td>
	<td>
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END url_item_tpl -->
</tr>
</table>

<!-- END url_list_tpl -->
</form>

<hr size="4" noshade="noshade" />
<input type="submit" name="NewURL" value="{intl-new}" />
<input type="submit" name="DeleteURL" value="{intl-delete_selected}" />
<hr size="4" noshade="noshade" />

<input type="submit" name="Store" value="{intl-store}" />


