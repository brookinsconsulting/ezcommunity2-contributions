<h1>{intl-subscription_list}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/bulkmail/subscriptionlist" method="post">

<!-- BEGIN category_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="20%">{intl-category_name}:</th>
	<th width="73%">{intl-category_description}:</th>
	<th width="1%">{intl-subscribe}</th>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	{category_name}
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td class="{td_class}"><input type="checkbox" name="CategoryArrayID[]" value="{category_id}" {is_checked} /></td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_tpl -->

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="okbutton" name="Ok" value="{intl-ok}" /></td>
</tr>
</table>
</form>