<h1>{intl-mail_list}</h1>
<hr noshade="noshade" size="4">

<form action="/bulkmail/drafts" method="post">

<!-- BEGIN bulkmail_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="60%">{intl-bulkmail_subject}:</th>
	<th width="38%">{intl-category}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN bulkmail_item_tpl -->
<tr>
	<td class="{td_class}">
	{bulkmail_subject}
	</td>
	<td class="{td_class}">
	{bulkmail_category}
	</td>
	<td class="{td_class}">
        <a href="/bulkmail/mailedit/{bulkmail_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{bulkmail_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{bulkmail_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
        </td>
	<td class="{td_class}"><input type="checkbox" name="MailArrayID[]" value="{bulkmail_id}" /></td>
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