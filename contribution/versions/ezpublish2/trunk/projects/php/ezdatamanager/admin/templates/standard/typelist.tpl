<h1>{intl-type_list} - {current_type_name}</h1>

<hr size="4" noshade="noshade" />

<form method="post" action="/datamanager/typelist/">

<!-- BEGIN type_list_tpl -->

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-type_name}:</p>
	</th>
	<th>
	<p class="boxtext">{intl-edit_type}:</p>
	</th>
	<th>
	</th>
</tr>

<!-- BEGIN type_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/datamanager/typelist/{type_id}/" >{type_name}</a>
	</td>
	<td class="{td_class}">
	<a href="/datamanager/typeedit/{type_id}" >{intl-edit_type}</a>
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="TypeIDArray[]" value="{type_id}" />
	</td>

</tr>
<!-- END type_tpl -->

</table>

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="DeleteTypes" value="{intl-delete_selected}" />

<!-- END type_list_tpl -->


<!-- BEGIN item_list_tpl -->

<b><a href="/datamanager/typelist/0/"><< {intl-type_list}</a></b>

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-item_name}:</p>
	</th>
</tr>

<!-- BEGIN item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/datamanager/itemedit/{item_id}/">{item_name}</a>
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="ItemIDArray[]" value="{item_id}" />
	</td>

</tr>
<!-- END item_tpl -->

</table>

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="DeleteItems" value="{intl-delete_selected}" />

<!-- END item_list_tpl -->

<input type="hidden" name="TypeID" value="{current_type_id}" />

</form>
