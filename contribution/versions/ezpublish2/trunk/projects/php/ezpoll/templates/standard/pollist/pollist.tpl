<h1>{intl-head_line}</h1>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h3>{intl-poll}</h3>
	</td>

	<td>
	<h3>{intl-description}</h3>
	</td>

	<td>
	<h3>{intl-closed}</a></h3>
	</td>
</tr>
<!-- BEGIN poll_item_tpl -->
<tr>
	<td>
	<a href="/poll/votebox/{poll_id}/">{poll_name}</a>
	</td>
	<td>
	{poll_description}
	</td>
	<td>
	{poll_is_closed}
	</td>
</tr>
<!-- END poll_item_tpl -->
</table>
