<form action="/message/list/" method="post">

<h1>{intl-messages_for} {user_first_name} {user_last_name} </h1>

<hr size="4" noshade="noshade" />
<br />
<!-- BEGIN message_list_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-message_is_read}:
	</th>
	<th>
	{intl-message_date}:
	</th>
	<th>
	{intl-message_from_user}:
	</th>
	<th>
	{intl-message_subject}:
	</th>
</tr>
<!-- BEGIN message_item_tpl -->
<tr>
        <!-- BEGIN message_read_tpl -->
	<td valign="top">
	{intl-is_read}
	</td>
        <!-- END message_read_tpl -->
        <!-- BEGIN message_unread_tpl -->
	<td valign="top">
	{intl-is_unread}
	</td>
        <!-- END message_unread_tpl -->

	<td valign="top">
	{message_date}
	</td>
	<td valign="top">
	{message_from_user}
	</td>
	<td valign="top">
	<a href="/message/view/{message_id}/">
	{message_subject}
	</a>
	</td>
</tr>

<!-- END message_item_tpl -->
</tr>
</table>
<br />
<!-- END message_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Refresh" value="{intl-refresh}" />

</form>
