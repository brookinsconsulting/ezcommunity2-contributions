<h1>{intl-additional_list}</h1>

<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/user/additional/">
<!-- BEGIN additional_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN additional_item_tpl -->
<tr  class="{td_class}">
	<td >
	<input type="hidden" name="AdditionalArrayID[]" value="{additional_id}" />
	<input type="text" name="Name[]" value="{additional_name}" />
	</td>

	<td >
	<select name="Type[]">
	<option {1_is_selected} value="1">{intl-textfield}</option>
	<option {2_is_selected} value="2">{intl-radiobox}</option>
	</select>
	</td>

	<td >
<!-- BEGIN fixed_values_tpl --> 
<a href="{www_dir}{index}/user/additional/fixedvalues/{additional_id}">{intl-fixed_values}</a>
<!-- END fixed_values_tpl -->
	</td>

	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{current_category_id}/?MoveDown={article_id}"><img src="{www_dir}/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{current_category_id}/?MoveUp={article_id}"><img src="{www_dir}/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>

	<td align="right">
	<input type="checkbox" name="DeleteArrayID[]" value="{additional_id}" />
	</td>
</tr>
<!-- END additional_item_tpl -->

</table>
<hr noshade="noshade" size="4" />

<!-- END additional_list_tpl -->

<input class="stdbutton" type="submit" name="New" value="{intl-new_additional}" />&nbsp;
<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />&nbsp;
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />
</form>