<h1>{intl-mail} - {current_folder_name}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/mail/folder/{current_folder_id}" enctype="multipart/form-data" >
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th widht="1%">&nbsp;</th>
	<th width="40%">{intl-subject}:</th>
	<th width="26%">{intl-sender}:</th>
	<th width="7%">{intl-size}:</th>
	<th width="24%">{intl-date}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN mail_item_tpl -->
<tr>
	<td class="{td_class}">
	{mail_status}
	</td>
	<td class="{td_class}">
	<a href="/mail/view/{mail_id}">{mail_subject}</a>
	</td>
	<td class="{td_class}">
	{mail_sender}
	</td>
	<td class="{td_class}">
	{mail_size}
	</td>
	<td class="{td_class}">
	{mail_date}
	</td>
	<td class="{td_class}">
	<!-- BEGIN mail_edit_item_tpl -->
	  <a href="/mail/mailedit/{mail_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{mail_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
           <img name="ezb{mail_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	<!-- END mail_edit_item_tpl -->
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="MailArrayID[]" value="{mail_id}" />
	</td>
</tr>
<!-- END mail_item_tpl -->
<!-- BEGIN mail_item_unread_tpl -->
<tr>
	<td class="{td_class}">
	<b>{mail_status}</b>
	</td>
	<td class="{td_class}">
	<b><a href="/mail/view/{mail_id}">{mail_subject}</a></b>
	</td>
	<td class="{td_class}">
	<b>{mail_sender}</b>
	</td>
	<td class="{td_class}">
	<b>{mail_size}</b>
	</td>
	<td class="{td_class}">
	<b>{mail_date}</b>
	</td>
	<td class="{td_class}">
	&nbsp;
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="MailArrayID[]" value="{mail_id}" />
	</td>
</tr>
<!-- END mail_item_unread_tpl -->

<!-- BEGIN mail_render_tpl -->

<!-- END mail_render_tpl -->


</table>

<hr noshade="noshade" size="4">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="Move" value="{intl-move}" /></td>
  <td>&nbsp;</td>
  <td>
    <select name="FolderSelectID">
        <option value="-1">{intl-choose_dest}</option>
    	<!-- BEGIN folder_item_tpl -->
	<option value="{folder_id}">{folder_name}</option>
	<!-- END folder_item_tpl -->
    </select>
  </td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="NewFolder" value="{intl-new_folder}" /></td>
</tr>
</table>

</form>