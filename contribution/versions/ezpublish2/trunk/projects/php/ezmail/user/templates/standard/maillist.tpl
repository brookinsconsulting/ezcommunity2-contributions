<h1>{intl-mail} - {current_folder_name}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/mail/folder/{current_folder_id}" enctype="multipart/form-data" >
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="50%">{intl-subject}:</th>
	<th width="30%">{intl-sender}:</th>
	<th width="9%">{intl-size}:</th>
	<th width="10%">{intl-date}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN mail_item_tpl -->
<tr>
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
	<input type="checkbox" name="MailArrayID[]" value="{mail_id}" />
	</td>
</tr>
<!-- END mail_item_tpl -->
</table>

<hr noshade="noshade" size="4">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="Move" value="{intl-move}" /></td>
  <td>&nbsp;</td>
    <select name="FolderSelectID">
        <option value="-1">{intl-choose_dest}</option>
    	<!-- BEGIN folder_item_tpl -->
	<option value="{folder_id}">{folder_name}</option>
	<!-- END folder_item_tpl -->
    </select>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="NewFolder" value="{intl-new_folder}" /></td>
</tr>
</table>

</form>