<h1>{intl-category_list}</h1>

<hr noshade="noshade" size="4">
<form action="/bulkmail/categorylist" method="post">
<!-- BEGIN category_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="20%">{intl-category_name}:</th>
	<th width="73%">{intl-category_description}:</th>
        <th width="5%">{intl-subscription_count}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	{category_name}
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td class="{td_class}">
	{subscription_count}
	</td>
	<td class="{td_class}">
	<a href="/bulkmail/categoryedit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{category_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{category_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}"><input type="checkbox" name="CategoryArrayID[]" value="{category_id}" /></td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_tpl -->

<hr noshade="noshade" size="4">

<!-- BEGIN bulkmail_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="20%">{intl-bulkmail_subject}:</th>
	<th width="79%">{intl-sent_date}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN bulkmail_item_tpl -->
<tr>
	<td class="{td_class}">
	{bulkmail_subject}
	</td>
	<td class="{td_class}">
	{sent_date}
	</td>
	<td class="{td_class}"><input type="checkbox" name="CategoryArrayID[]" value="{bulkmail_id}" /></td>
</tr>
<!-- END bulkmail_item_tpl -->
</table>
<!-- END bulkmail_tpl -->

<hr noshade="noshade" size="4">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="stdbutton" name="New" value="{intl-new}" /></td>
  <td>&nbsp</td>
  <td><input type="submit" class="stdbutton" name="Delete" value="{intl-delete_selected}" /></td>
</tr>
</table>
</form>