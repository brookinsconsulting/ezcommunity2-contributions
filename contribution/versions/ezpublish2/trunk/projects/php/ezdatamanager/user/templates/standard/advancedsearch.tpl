<h1>{intl-advanced_search}</h1>

<form method="post" action="/datamanager/advancedsearch/">

<hr size="4" noshade="noshade" />

<p class="boxtext">{intl-item_type}:</p>
<select name="NewItemTypeID" >
<!-- BEGIN item_type_option_tpl -->
<option {selected} value="{type_id}">{type_name}</option>
<!-- END item_type_option_tpl -->
</select>

<input class="stdbutton" type="submit" name="SelectType" value="{intl-select}" />

<p class="boxtext">{intl-item_name}:</p>
<input class="box" type="text" name="ItemNameText" value="{item_name}" />


<!-- BEGIN item_value_list_tpl -->

<table width="100%" cellpadding="4" cellspacing="2" >
<!-- BEGIN item_value_tpl -->
<tr>
	<td class="{td_class}">
	<b>{data_type_name}</b><br />
	<input class="box" type="text" name="TypeItemText[{data_type_id}]" value="{data_type_value}" />
	</td>
</tr>
<!-- END item_value_tpl -->

</table>

<hr size="4" noshade="noshade" />

<input type="hidden" name="ItemTypeID" value="{item_type_id}" />

<input class="okbutton" type="submit" name="Search" value="{intl-search}" />

<!-- END item_value_list_tpl -->

</form>


<!-- BEGIN item_list_tpl -->
<h2>{intl-search_results}:</h2>
<table width="100%" cellpadding="4" cellspacing="2" >
<!-- BEGIN item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/datamanager/itemview/{item_result_id}/">{item_result_name}</a>
	</td>
</tr>
<!-- END item_tpl -->

</table>

<hr size="4" noshade="noshade" />

<!-- END item_list_tpl -->
