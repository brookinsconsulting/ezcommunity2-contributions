<form action="/article/articleedit/attributeedit/{action_value}/{this_type_id}" method="post">

<h1>{intl-attributes}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_types_item_tpl -->
{intl-no_types}
<!-- END no_types_item_tpl -->

<!-- BEGIN type_list_tpl -->
<select name="TypeID">
<option value="-1">{intl-no_selected_type}</option>
<!-- BEGIN type_item_tpl -->
<option value="{type_id}" {selected}>{type_name}</option>

<!-- END type_item_tpl -->
</select>&nbsp;<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />
<!-- END type_list_tpl -->
<input type="hidden" name="ArticleID" value="{article_id}" />

<br/>

<hr noshade="noshade" size="4" />
<!-- BEGIN no_attributes_item_tpl -->
{intl-no_attributes_exist}
<!-- END no_attributes_item_tpl -->

<!-- BEGIN no_selected_type_item_tpl -->
{intl-no_type_selected}
<!-- END no_selected_type_item_tpl -->

<!-- BEGIN attribute_list_tpl -->
<h2 class="boxtext">{type_name}d:</h2>
<!-- {type_id} -->

<!-- BEGIN attribute_item_tpl -->
<p class="boxtext">{attribute_name}:</p>
<div class="box">{attribute_value}</div>
<!-- {attribute_id}" -->
<br /><br />
<!-- END attribute_item_tpl -->
<!-- END attribute_list_tpl -->
<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

</form>
