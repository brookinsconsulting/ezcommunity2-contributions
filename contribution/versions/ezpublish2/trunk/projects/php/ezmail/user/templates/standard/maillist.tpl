<h1>{intl-mail}</h1>

<hr noshade="noshade" size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="40%">{intl-subject}:</th>
	<th width="20%">{intl-sender}:</th>
	<th width="9%">{intl-size}:</th>
	<th width="30%">{intl-date}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN mail_item_tpl -->
<tr>
	<td class="{td_class}">
	{mail_subject}
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
	<input type="checkbox" name="MailArrayID[]" value="mail_id" />
	</td>
</tr>
<!-- END mail_item_tpl -->
</table>