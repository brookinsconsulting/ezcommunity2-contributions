<h1>{intl-type_edit}</h1>

<form method="post" action="/datamanager/typeedit/">

<hr size="4" noshade="noshade" />

<p class="boxtext">{intl-type_name}:</p>
<input class="box" type="text" name="TypeName" value="{type_name}" />

<!-- BEGIN type_item_list_tpl -->

<table width="100%" cellpadding="4" cellspacing="2" >
<tr>
	<th>
	<p class="boxtext">{intl-item_name}:</p>
	</th>
</tr>

<!-- BEGIN type_item_tpl -->
<tr>
	<td class="{td_class}">
	<input class="box" type="text" name="ItemName[]" value="{item_name}" />
	<input type="checkbox" name="DeleteItemArray[]" value="{item_id}" />
	<input type="hidden" name="ItemIDArray[]" value="{item_id}" />
	</td>
</tr>
<!-- END type_item_tpl -->

</table>
<!-- END type_item_list_tpl -->

<hr size="4" noshade="noshade" />
<input class="stdbutton" type="submit" name="NewItem" value="{intl-new_item}" />
<input class="stdbutton" type="submit" name="DeleteItems" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input type="hidden" name="TypeID" value="{type_id}" />

<input class="okbutton" type="submit" name="Store" value="{intl-ok}" />

</form>